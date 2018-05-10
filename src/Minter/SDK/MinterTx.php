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
        'payload',
        'v',
        'r',
        's'
    ];

    /**
     * bits for recovery param in elliptic curve
     */
    const V_BITS = 27;

    /**
     * MinterTx constructor.
     * @param $tx
     * @throws \Exception
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

        $tx = $this->txDataRlpEncode($this->tx);

        $keccak = $this->createKeccakHash($tx);
        $ellipticCurve = new EC('secp256k1');
        $signature = $ellipticCurve->sign($keccak, $privateKey, 'hex', ['canonical' => true]);

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
        $shortTx = $this->txDataRlpEncode($shortTx);

        $msg = $this->createKeccakHash($shortTx);

        $recoveryParam = $tx['v'] === MinterTx::V_BITS ? 0 : 1;

        $signature = [
            'r' => $tx['r'],
            's' => $tx['s'],
            'recoveryParam' => $recoveryParam
        ];

        $ellipticCurve = new EC('secp256k1');
        $point = $ellipticCurve->recoverPubKey($msg, $signature, $recoveryParam, 'hex');

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
     * @throws \Exception
     */
    protected function decode(string $tx): array
    {
        $tx = $this->rlpToHex($tx);

        $dataIndex = array_search('data', $this->structure);
        $tx[$dataIndex] = $this->rlpToHex($tx[$dataIndex]);

        return $this->encode($this->prepareResult($tx), true);
    }

    /**
     * Encode transaction data
     *
     * @param array $tx
     * @param bool $isHexFormat
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
        $result = [];
        foreach($this->structure as $key => $field) {
            if(in_array($field, ['r', 's', 'data'])) {
                $result[$field] = $tx[$key];
            }
            elseif($field === 'payload') {
                $result[$field] = str_replace(chr(0), '', pack('H*', $tx[$key]));
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
    protected function rlpToHex(string $data): array
    {
        $data = $this->rlp->decode(
            '0x' . str_replace(MinterWallet::PREFIX, '', $data)
        );

        foreach ($data as $key => $value) {
            $data[$key] = $value->toString('hex');
        }

        return (array) $data;
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
            'v' => $signature->recoveryParam + MinterTx::V_BITS,
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

    /**
     * Convert tx data to rlp
     *
     * @param array $tx
     * @return array
     */
    protected function txDataRlpEncode(array $tx): array
    {
        $tx['data'] = $this->rlp->encode($tx['data']);

        return $tx;
    }
}