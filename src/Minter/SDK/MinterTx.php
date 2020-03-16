<?php

namespace Minter\SDK;

use InvalidArgumentException;
use Exception;
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
 *
 * @property int nonce
 * @property int chainId
 * @property int gasPrice
 * @property int gasCoin
 * @property int type
 * @property array data
 * @property string payload
 * @property string serviceData
 * @property int signatureType
 * @property array signatureData
 * @property string from
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
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->tx[$name];
    }

    /**
     * Sign tx
     *
     * @param string $privateKey
     * @return string
     */
    public function sign(string $privateKey): string
    {
        $this->tx['signatureType'] = self::SIGNATURE_SINGLE_TYPE;

        $tx = $this->encodeTxToRlp($this->tx);
        $hash = $this->createTxHash($tx);

        $signature = ECDSA::sign($hash, $privateKey);
        $signature = Helper::hex2buffer($signature);

        $tx['signatureData'] = $this->rlp->encode($signature);
        $this->txSigned = $this->rlp->encode($tx);

        return MinterPrefix::TRANSACTION . $this->txSigned;
    }

    /**
     * Sign with multi-signature
     *
     * @param string $multisigAddress
     * @param array  $privateKeys
     * @return string
     */
    public function signMultisig(string $multisigAddress, array $privateKeys): string
    {
        $this->tx['signatureType'] = self::SIGNATURE_MULTI_TYPE;

        $tx = $this->encodeTxToRlp($this->tx);
        $hash = $this->createTxHash($tx);

        $signatures = [];
        foreach ($privateKeys as $privateKey) {
            $signature = ECDSA::sign($hash, $privateKey);
            $signatures[] = Helper::hex2buffer($signature);
        }

        $multisigAddress = Helper::removeWalletPrefix($multisigAddress);
        $multisigAddress = hex2bin($multisigAddress);

        $tx['signatureData'] = $this->rlp->encode([$multisigAddress, $signatures]);
        $this->txSigned = $this->rlp->encode($tx);

        return MinterPrefix::TRANSACTION . $this->txSigned;
    }

    /**
     * @param string $multisigAddress
     * @param array  $signatures
     * @return string
     */
    public function signMultisigBySigns(string $multisigAddress, array $signatures): string
    {
        $this->tx['signatureType'] = self::SIGNATURE_MULTI_TYPE;
        $tx = $this->encodeTxToRlp($this->tx);

        foreach ($signatures as $key => $signature) {
            $signatures[$key] = $this->rlp->decode('0x' . $signature);
        }

        $multisigAddress = Helper::removeWalletPrefix($multisigAddress);
        $multisigAddress = hex2bin($multisigAddress);

        $tx['signatureData'] = $this->rlp->encode([$multisigAddress, $signatures]);
        $this->txSigned = $this->rlp->encode($tx);

        return MinterPrefix::TRANSACTION . $this->txSigned;
    }

    /**
     * @param array $tx
     * @return string
     */
    private function createTxHash(array $tx): string
    {
        return Helper::createKeccakHash(
            $this->rlp->encode($tx)
        );
    }

    /**
     * Create transaction signature by private key
     *
     * @param string $privateKey
     * @return string
     */
    public function createSignature(string $privateKey): string
    {
        $tx = $this->encodeTxToRlp($this->tx);
        $tx['signatureType'] = $this->tx['signatureType'] ?? self::SIGNATURE_MULTI_TYPE;

        $hash = $this->createTxHash($tx);
        $signature = ECDSA::sign($hash, $privateKey);
        $signature = Helper::hex2buffer($signature);
        $signature = $this->rlp->encode($signature);

        return $signature;
    }

    /**
     * Get sender Minter address
     *
     * @param array $tx
     * @return string
     */
    public function getSenderAddress(array $tx): string
    {
        $publicKey = $this->recoverPublicKey($tx);
        return MinterWallet::getAddressFromPublicKey($publicKey);
    }

    /**
     * Recover public key
     *
     * @param array $tx
     * @return string
     */
    private function recoverPublicKey(array $tx): string
    {
        $signature = array_pop($tx); // remove signature data from tx
        $tx = Helper::hex2binRecursive($tx);
        $tx = $this->encodeTxToRlp($tx);
        $hash = $this->createTxHash($tx);

        // recover public key
        $publicKey = ECDSA::recover($hash, $signature['r'], $signature['s'], $signature['v']);

        return MinterPrefix::PUBLIC_KEY . $publicKey;
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

        $tx = hash('sha256', hex2bin($this->txSigned));

        return MinterPrefix::TRANSACTION_HASH . substr($tx, 0, 40);
    }

    /**
     * Get fee of transaction in PIP
     *
     * @return string
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
     */
    private function decode(string $tx): array
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
     */
    private function encode(array $tx, bool $isHexFormat = false): array
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
    private function rlpToHex(string $data): array
    {
        $data = $this->rlp->decode('0x' . $data);
        $data = Helper::rlpArrayToHexArray($data);

        return $data;
    }

    /**
     * Convert tx data to rlp
     *
     * @param array $tx
     * @return array
     */
    private function encodeTxToRlp(array $tx): array
    {
        $tx['payload'] = Helper::str2buffer($tx['payload']);
        $tx['gasCoin'] = MinterConverter::convertCoinName($tx['gasCoin']);
        $tx['data'] = $this->rlp->encode($tx['data']);

        return $tx;
    }
}
