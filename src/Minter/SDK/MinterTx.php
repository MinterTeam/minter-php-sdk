<?php

namespace Minter\SDK;

use InvalidArgumentException;
use Exception;
use Web3p\RLP\Buffer;
use Web3p\RLP\RLP;
use Minter\Library\ECDSA;
use Minter\Library\Helper;
use Minter\SDK\MinterCoins\{
    MinterCoinTx,
    MinterCreateMultisigTx,
    MinterDelegateTx,
    MinterEditCandidateTx,
    MinterMultiSendTx,
    MinterRedeemCheckTx,
    MinterSellAllCoinTx,
    MinterSetCandidateOffTx,
    MinterSetCandidateOnTx,
    MinterCreateCoinTx,
    MinterDeclareCandidacyTx,
    MinterSendCoinTx,
    MinterUnbondTx,
    MinterSellCoinTx,
    MinterBuyCoinTx};

/**
 * Class MinterTx
 * @package Minter\SDK
 */
class MinterTx
{
    /**
     * Transaction
     *
     * @var array
     */
    protected $tx;

    /** @var RLP */
    protected $rlp;

    /**
     * Minter transaction structure
     *
     * @var array
     */
    protected $structure = [
        'nonce',
        'chainId',
        'gasPrice',
        'gasCoin',
        'type',
        'data',
        'payload',
        'serviceData',
        'signatureType',
        'signatureData'
    ];

    /**
     * @var string
     */
    protected $txSigned;

    /**
     * Transaction data
     * @var MinterCoinTx
     */
    protected $txDataObject;

    /** Fee in PIP */
    const PAYLOAD_COMMISSION = 2;

    /** All gas price multiplied by FEE DEFAULT (PIP) */
    const FEE_DEFAULT_MULTIPLIER = 1000000000000000;

    /** Type of single signature for the transaction */
    const SIGNATURE_SINGLE_TYPE = 1;

    /** Type of multi signature for the transaction */
    const SIGNATURE_MULTI_TYPE = 2;

    /** Mainnet chain id */
    const MAINNET_CHAIN_ID = 1;

    /** Testnet chain id */
    const TESTNET_CHAIN_ID = 2;

    /** @var int */
    const DEFAULT_GAS_PRICE = 1;

    /** @var array */
    const DEFAULT_GAS_COINS = [
        self::MAINNET_CHAIN_ID => 'BIP',
        self::TESTNET_CHAIN_ID => 'MNT'
    ];

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
            $this->txSigned = Helper::removePrefix($tx, MinterPrefix::TRANSACTION);
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
        // encode data array to RPL
        $this->tx['signatureType'] = self::SIGNATURE_SINGLE_TYPE;
        $tx = $this->txDataRlpEncode($this->tx);
	    $tx['payload'] = Helper::str2buffer($tx['payload']);

        // create keccak hash from transaction
        $keccak = Helper::createKeccakHash(
            $this->rlp->encode($tx)->toString('hex')
        );

        // prepare special [V, R, S] signature bytes and add them to transaction
        $signature = ECDSA::sign($keccak, $privateKey);
        $tx['signatureData'] = $this->rlp->encode(
            Helper::hex2buffer($signature)
        );

        // pack transaction to hex string
        $this->txSigned = $this->rlp->encode($tx)->toString('hex');

        return MinterPrefix::TRANSACTION . $this->txSigned;
    }

    /**
     * Sign with multi-signature
     *
     * @param string $multisigAddress
     * @param array  $privateKeys
     * @return string
     * @throws Exception
     */
    public function signMultisig(string $multisigAddress, array $privateKeys): string
    {
        // encode data array to RPL
        $this->tx['signatureType'] = self::SIGNATURE_MULTI_TYPE;
        $tx = $this->txDataRlpEncode($this->tx);
        $tx['payload'] = Helper::str2buffer($tx['payload']);

        // create keccak hash from transaction
        $keccak = Helper::createKeccakHash(
            $this->rlp->encode($tx)->toString('hex')
        );

        $signatures = [];
        foreach ($privateKeys as $privateKey) {
            $signature = ECDSA::sign($keccak, $privateKey);
            $signatures[] = Helper::hex2buffer($signature);
        }

        $multisigAddress = hex2bin(Helper::removeWalletPrefix($multisigAddress));
        $tx['signatureData'] = $this->rlp->encode([$multisigAddress, $signatures]);

        // pack transaction to hex string
        $this->txSigned = $this->rlp->encode($tx)->toString('hex');

        return MinterPrefix::TRANSACTION . $this->txSigned;
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
        $shortTx = array_diff_key($tx, ['signatureData' => '']);
        $shortTx = Helper::hex2binRecursive($shortTx);
        $shortTx = $this->txDataRlpEncode($shortTx);

        // create kessak hash from transaction
        $msg = Helper::createKeccakHash(
            $this->rlp->encode($shortTx)->toString('hex')
        );

        // recover public key
        $signature = $tx['signatureData'];
        $publicKey = ECDSA::recover($msg, $signature['r'], $signature['s'], $signature['v']);

        return MinterPrefix::PUBLIC_KEY . $publicKey;
    }

    /**
     * Get hash of transaction
     *
     * @return string
     * @throws Exception
     */
    public function getHash(): string
    {
        if(!$this->txSigned) {
            throw new \Exception('You need to sign transaction before');
        }

        // create SHA256 of tx
        $tx = hash('sha256', hex2bin($this->txSigned));

        // return first 40 symbols
        return MinterPrefix::TRANSACTION_HASH . substr($tx, 0, 40);
    }

    /**
     * Get fee of transaction in PIP
     *
     * @return string
     * @throws \Exception
     */
    public function getFee(): string
    {
        if(!$this->txDataObject) {
            throw new Exception('You need to sign transaction before the calculating free');
        }

        // get transaction data fee
        $gas = $this->txDataObject->getFee();

        // multiplied gas price
        $gasPrice = bcmul($gas, self::FEE_DEFAULT_MULTIPLIER, 0);

        // commission for payload and serviceData bytes
        $commission = bcadd(
            strlen($this->payload) * bcmul(self::PAYLOAD_COMMISSION, self::FEE_DEFAULT_MULTIPLIER, 0),
            strlen($this->serviceData) * bcmul(self::PAYLOAD_COMMISSION, self::FEE_DEFAULT_MULTIPLIER, 0)
        );

        return bcadd($gasPrice, $commission, 0);
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
        $tx = array_combine($this->structure, $tx);

        // pack data of transaction to hex string
        $tx['data'] = $this->rlpToHex($tx['data']);
        $tx['signatureData'] = $this->rlpToHex($tx['signatureData']);

        // encode transaction data
        $decodedTx = $this->prepareResult($tx);
        return $this->encode($decodedTx, true);
    }

    /**
     * Encode transaction data
     *
     * @param array $tx
     * @param bool  $isHexFormat
     * @return array
     * @throws InvalidArgumentException
     * @throws Exception
     */
    protected function encode(array $tx, bool $isHexFormat = false): array
    {
        // fill with default values if not present
        $tx['payload']     = $tx['payload']     ?? '';
        $tx['serviceData'] = $tx['serviceData'] ?? '';
        $tx['gasPrice']    = $tx['gasPrice']    ?? self::DEFAULT_GAS_PRICE;
        $tx['gasCoin']     = $tx['gasCoin']     ?? self::DEFAULT_GAS_COINS[$tx['chainId']];

        // make right order in transaction params
        $txFields = array_flip($this->structure);
        $txFields = array_intersect_key($txFields, $tx);
        $tx = array_replace($txFields, $tx);

        switch ($tx['type']) {
            case MinterSendCoinTx::TYPE:
                $this->txDataObject = new MinterSendCoinTx($tx['data'], $isHexFormat);
                break;

            case MinterSellCoinTx::TYPE:
                $this->txDataObject = new MinterSellCoinTx($tx['data'], $isHexFormat);
                break;

            case MinterSellAllCoinTx::TYPE:
                $this->txDataObject = new MinterSellAllCoinTx($tx['data'], $isHexFormat);
                break;

            case MinterBuyCoinTx::TYPE:
                $this->txDataObject = new MinterBuyCoinTx($tx['data'], $isHexFormat);
                break;

            case MinterCreateCoinTx::TYPE:
                $this->txDataObject = new MinterCreateCoinTx($tx['data'], $isHexFormat);
                break;

            case MinterDeclareCandidacyTx::TYPE:
                $this->txDataObject = new MinterDeclareCandidacyTx($tx['data'], $isHexFormat);
                break;

            case MinterDelegateTx::TYPE:
                $this->txDataObject = new MinterDelegateTx($tx['data'], $isHexFormat);
                break;

            case MinterUnbondTx::TYPE:
                $this->txDataObject = new MinterUnbondTx($tx['data'], $isHexFormat);
                break;

            case MinterRedeemCheckTx::TYPE:
                $this->txDataObject = new MinterRedeemCheckTx($tx['data'], $isHexFormat);
                break;

            case MinterSetCandidateOnTx::TYPE:
                $this->txDataObject = new MinterSetCandidateOnTx($tx['data'], $isHexFormat);
                break;

            case MinterSetCandidateOffTx::TYPE:
                $this->txDataObject = new MinterSetCandidateOffTx($tx['data'], $isHexFormat);
                break;

            case MinterCreateMultisigTx::TYPE:
                $this->txDataObject = new MinterCreateMultisigTx($tx['data'], $isHexFormat);
                break;

            case MinterMultiSendTx::TYPE:
                $this->txDataObject = new MinterMultiSendTx($tx['data'], $isHexFormat);
                break;

            case MinterEditCandidateTx::TYPE:
                $this->txDataObject = new MinterEditCandidateTx($tx['data'], $isHexFormat);
                break;

            default:
                throw new InvalidArgumentException('Unknown transaction type');
                break;
        }

        $tx['data'] = $this->txDataObject->data;

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
        $tx = [
            'nonce' => hexdec($tx['nonce']),
            'chainId' => hexdec($tx['chainId']),
            'gasPrice' => hexdec($tx['gasPrice']),
            'gasCoin' => MinterConverter::convertCoinName(Helper::hex2str($tx['gasCoin'])),
            'type' => hexdec($tx['type']),
            'data' => $tx['data'],
            'payload' => Helper::hex2str($tx['payload']),
            'serviceData' => Helper::hex2str($tx['serviceData']),
            'signatureType' => hexdec($tx['signatureType']),
            'signatureData' => $tx['signatureData']
        ];

        if($tx['signatureType'] === self::SIGNATURE_SINGLE_TYPE) {
            list($v, $r, $s) = $tx['signatureData'];
            $tx['signatureData'] = ['v' => hexdec($v), 'r' => $r, 's' => $s];
            $tx['from'] = $this->getSenderAddress($tx);
        }

        if($tx['signatureType'] === self::SIGNATURE_MULTI_TYPE) {
            list($multisigAddress, $signatures) = $tx['signatureData'];
            $tx['signatureData'] = [$multisigAddress];

            $signatures = array_map(function($signature) {
                list($v, $r, $s) = $signature;
                return ['v' => hexdec($v), 'r' => $r, 's' => $s];
            }, $signatures);

            $tx['signatureData'][] = $signatures;
            $tx['from'] = Helper::addWalletPrefix($multisigAddress);
        }

        return $tx;
    }

    /**
     * Convert array items from rlp to hex
     *
     * @param string $data
     * @return array
     */
    protected function rlpToHex(string $data): array
    {
        $data = $this->rlp->decode('0x' . $data);

        foreach ($data as $key => $value) {
            if(is_array($value)) {
                $data[$key] = Helper::rlpArrayToHexArray($value);
            } else {
                $data[$key] = $value->toString('hex');
            }
        }

        return (array) $data;
    }

    /**
     * Convert tx data to rlp
     *
     * @param array $tx
     * @return array
     */
    protected function txDataRlpEncode(array $tx): array
    {
        $tx['gasCoin'] = MinterConverter::convertCoinName($tx['gasCoin']);
        $tx['data'] = $this->rlp->encode($tx['data']);

        return $tx;
    }

    /**
     * Validate transaction structure
     *
     * @param array $tx
     */
    protected function validateTx(array $tx): void
    {
        // get keys of tx and prepare structure keys
        $length = count($this->structure) - 1;
        $tx = array_slice(array_keys($tx), 0, $length);
        $structure = array_slice($this->structure, 0, $length);

        // compare
        if(!empty(array_diff_key($tx, $structure))) {
            throw new InvalidArgumentException('Invalid transaction structure params');
        }
    }
}
