<?php

namespace Minter\SDK;

use kornrunner\Keccak;
use Elliptic\EC;
use Web3p\RLP\RLP;

class MinterTx
{
    /**
     * txData
     *
     * @var array
     */
    protected $tx;

    /**
     * @var RLP
     */
    protected $rlp;

    /**
     * tx structure
     *
     * @var array
     */
    protected $structure = [
        'nonce',
        'gasPrice',
        'type',
        'data',
        'v',
        'r',
        's'
    ];

    /**
     * MinterTx constructor.
     * @param $tx
     */
    public function __construct($tx)
    {
        $this->tx = $tx;
        $this->rlp = new RLP;

        if(is_string($tx)) {
            $this->tx = $this->decode($tx);
        }

        if(is_array($tx)) {
            $this->tx = $this->encode($tx);
        }
    }

    /**
     * Get
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        $method = 'get' . ucfirst($name);

        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], []);
        }

        return $this->tx[$name];
    }

    /**
     * Get sender Minter address
     *
     * @param array $tx
     * @return string
     * @throws \Exception
     */
    public function getSenderAddress(array $tx): string
    {
        return MinterWallet::getAddressFromPublicKey(
            $this->recoverPublicKey($tx)
        );
    }

    /**
     * Sign tx
     *
     * @param string $privateKey
     * @return string
     * @throws \Exception
     */
    public function sign(string $privateKey): string
    {
        if(!is_array($this->tx)) {
            throw new \Exception('Undefined tx');
        }

        $tx = $this->tx;
        $tx['data'] = $this->rlp->encode($tx['data']);

        $keccak = $this->createKeccakHash($tx);
        $ec = new EC('secp256k1');
        $signature = $ec->sign($keccak, $privateKey, 'hex', ['canonical' => true]);

        $tx = array_merge($tx, $this->prepareVRS($signature));
        $tx = MinterWallet::PREFIX . $this->rlp->encode($tx)->toString('hex');

        return $tx;
    }

    /**
     * Recover public key
     *
     * @param array $tx
     * @return string
     * @throws \Exception
     */
    public function recoverPublicKey(array $tx): string
    {
        $shortTx = array_diff_key($tx, ['v' => '', 'r' => '', 's' => '']);
        $shortTx = $this->detectHex2bin($shortTx);
        $shortTx['data'] = $this->rlp->encode($shortTx['data']);
        $msg = $this->createKeccakHash($shortTx);

        $recoveryParam = $tx['v'] === 27 ? 0 : 1;
        $signature = [
            'r' => $tx['r'],
            's' => $tx['s'],
            'recoveryParam' => $recoveryParam
        ];

        $ec = new EC('secp256k1');
        $point = $ec->recoverPubKey($msg, $signature, $recoveryParam, 'hex');

        return MinterWallet::generatePublicKey([
            'pub' => $point,
            'pubEnc' => 'hex'
        ]);
    }

    /**
     * Create Keccak 256 hash
     *
     * @param array $tx
     * @return string
     * @throws \Exception
     */
    protected function createKeccakHash(array $tx): string
    {
        $binaryTx = hex2bin(
            $this->rlp->encode($tx)->toString('hex')
        );

        return Keccak::hash($binaryTx, 256);
    }

    /**
     * Decode tx
     *
     * @param string $tx
     * @return array
     */
    protected function decode(string $tx): array
    {
        $tx = $this->rplToHex(bin2hex(base64_decode($tx)));
        $dataIndex = array_search('data', $this->structure);
        $tx[$dataIndex] = $this->rplToHex($tx[$dataIndex]);

        return $this->encode($this->prepareResult($tx), true);
    }

    /**
     * Encode transaction data
     *
     * @param array $tx
     * @return array
     * @throws \Exception
     */
    protected function encode(array $tx, bool $isHexFormat = false): array
    {
        switch ($tx['type']) {
            case MinterSendCoinTx::TYPE:
                $dataTx = new MinterSendCoinTx($tx['data'], $isHexFormat);
                break;

            case MinterConvertCoinTx::TYPE:
                $dataTx = new MinterConvertCoinTx($tx['data'], $isHexFormat);
                break;

            case MinterCreateCoinTx::TYPE:
                $dataTx = new MinterCreateCoinTx($tx['data'], $isHexFormat);
                break;

            default:
                throw new \Exception('Unknown transaction type');
                break;
        }

        $tx['data'] = $dataTx->data;

        return $tx;
    }

    /**
     * Prepare output result
     *
     * @param array $tx
     * @return array
     * @throws \Exception
     */
    protected function prepareResult(array $tx): array
    {
        $dataIndex = array_search('data', $this->structure);

        $result = [];
        foreach($this->structure as $key => $field) {
            if($field === 'r' || $field === 's' || $field === 'data') {
                $result[$field] = $tx[$key];
            }
            else {
                $result[$field] = hexdec($tx[$key]);
            }
        }

        $result['from'] = $this->getSenderAddress($result);

        return $result;
    }

    /**
     * Convert array items from rlp to hex
     *
     * @param string $data
     * @return array
     */
    protected function rplToHex(string $data): array
    {
        $data = $this->rlp->decode('0x' . $data);

        foreach ($data as $key => $value) {
            $data[$key] = $value->toString('hex');
        }

        return $data;
    }

    /**
     * Prepare V R S for tx
     *
     * @param EC\Signature $signature
     * @return array
     */
    protected function prepareVRS(EC\Signature $signature): array
    {
        $r = $signature->r->toString('hex');
        if(strlen($r) % 2 !== 0) $r = '0' . $r;

        $s = $signature->s->toString('hex');
        if(strlen($s) % 2 !== 0) $s = '0' . $s;

        return [
            'v' => $signature->recoveryParam + 27,
            'r' => hex2bin($r),
            's' => hex2bin($s)
        ];
    }

    /**
     * Detect hex string and convert to bin
     *
     * @param array $data
     * @return array
     */
    protected function detectHex2bin(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->detectHex2bin($value);
            } elseif (is_string($value) && ctype_xdigit($value)) {
                $data[$key] = hex2bin($value);
            }
        }

        return $data;
    }
}