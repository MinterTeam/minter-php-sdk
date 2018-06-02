<?php

namespace Minter\SDK;

use kornrunner\Keccak;
use Elliptic\EC;
use Minter\Library\Helper;
use Minter\SDK\MinterCoins\MinterConvertCoinTx;
use Minter\SDK\MinterCoins\MinterCreateCoinTx;
use Minter\SDK\MinterCoins\MinterSendCoinTx;
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
        'serviceData',
        'v',
        'r',
        's'
    ];

    /**
     * @var string
     */
    protected $txSigned;

    /**
     * bits for recovery param in elliptic curve
     */
    const V_BITS = 27;

    /**
     * Fee in PIP
     */
    const PAYLOAD_COMMISSION = 500;

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
            $this->txSigned = $tx;
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

        // encode data array to RPL
        $tx = $this->txDataRlpEncode($this->tx);

        // create kessak hash from transaction
        $keccak = $this->createKeccakHash($tx);

        // create elliptic curve and sign
        $ellipticCurve = new EC('secp256k1');
        $signature = $ellipticCurve->sign($keccak, $privateKey, 'hex', ['canonical' => true]);

        // prepare special V R S bytes and add them to transaction
        $tx = array_merge($tx, $this->prepareVRS($signature));

        // add "Mx" prefix to transaction
        $this->txSigned = Helper::addWalletPrefix(
            $this->rlp->encode($tx)->toString('hex')
        );

        return $this->txSigned;
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
        // prepare short transaction
        $shortTx = array_diff_key($tx, ['v' => '', 'r' => '', 's' => '']);
        $shortTx = Helper::hex2binRecursive($shortTx);
        $shortTx = $this->txDataRlpEncode($shortTx);

        // create kessak hash from transaction
        $msg = $this->createKeccakHash($shortTx);

        // define the recovery param
        $recoveryParam = $tx['v'] === MinterTx::V_BITS ? 0 : 1;

        // define the signature
        $signature = [
            'r' => $tx['r'],
            's' => $tx['s'],
            'recoveryParam' => $recoveryParam
        ];

        // create elliptic curve
        $ellipticCurve = new EC('secp256k1');
        $point = $ellipticCurve->recoverPubKey($msg, $signature, $recoveryParam, 'hex');

        // generate public key from point
        return MinterWallet::generatePublicKey([
            'pub' => $point,
            'pubEnc' => 'hex'
        ]);
    }

    /**
     * Get hash of transaction
     *
     * @return string
     */
    public function getHash(): string
    {
        if(!$this->txSigned) {
            throw new \Exception('You need to sign transaction before');
        }

        // prepare transaction
        $tx = Helper::removeWalletPrefix($this->txSigned);
        $tx = hex2bin(dechex(strlen($tx) / 2) . $tx);

        // make RIPEMD160 hash of transaction
        return Helper::addWalletPrefix(
            hash('ripemd160', $tx)
        );
    }

    /**
     * Get fee of transaction in PIP
     *
     * @return int
     * @throws \Exception
     */
    public function getFee(): int
    {
        switch ($this->type) {
            case MinterSendCoinTx::TYPE:
                $gas = MinterSendCoinTx::COMMISSION;
                break;

            case MinterConvertCoinTx::TYPE:
                $gas = MinterConvertCoinTx::COMMISSION;
                break;

            case MinterCreateCoinTx::TYPE:
                $gas = MinterCreateCoinTx::COMMISSION;
                break;

            default:
                throw new \Exception('Unknown transaction type');
                break;
        }

        return $gas + (strlen($this->payload) / 2) * self::PAYLOAD_COMMISSION;
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
        // pack RLP to hex string
        $tx = $this->rlpToHex($tx);

        // pack data of transaction to hex string
        $dataIndex = array_search('data', $this->structure);
        $tx[$dataIndex] = $this->rlpToHex($tx[$dataIndex]);

        // encode transaction data
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
            elseif($field === 'payload' || $field === 'serviceData') {
                $result[$field] = Helper::pack2hex($tx[$key]);
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