<?php

namespace Minter\SDK;

use InvalidArgumentException;
use Minter\Contracts\MinterTxInterface;
use Minter\SDK\MinterCoins\MinterBuyCoinTx;
use Minter\SDK\MinterCoins\MinterChangeOwnerTx;
use Minter\SDK\MinterCoins\MinterCreateCoinTx;
use Minter\SDK\MinterCoins\MinterCreateMultisigTx;
use Minter\SDK\MinterCoins\MinterDeclareCandidacyTx;
use Minter\SDK\MinterCoins\MinterDelegateTx;
use Minter\SDK\MinterCoins\MinterEditCandidateTx;
use Minter\SDK\MinterCoins\MinterMultiSendTx;
use Minter\SDK\MinterCoins\MinterRecreateCoinTx;
use Minter\SDK\MinterCoins\MinterRedeemCheckTx;
use Minter\SDK\MinterCoins\MinterSellAllCoinTx;
use Minter\SDK\MinterCoins\MinterSellCoinTx;
use Minter\SDK\MinterCoins\MinterSendCoinTx;
use Minter\SDK\MinterCoins\MinterSetCandidateOffTx;
use Minter\SDK\MinterCoins\MinterSetCandidateOnTx;
use Minter\SDK\MinterCoins\MinterSetHaltBlockTx;
use Minter\SDK\MinterCoins\MinterUnbondTx;
use Web3p\RLP\RLP;;
use Minter\Library\Helper;
use Minter\SDK\MinterCoins\MinterCoinTx;

/**
 * Class MinterTx
 * @package Minter\SDK
 */
class MinterTx extends MinterTxSigner
{
    /** @var RLP */
    private $rlp;

    const FEE_DEFAULT_MULTIPLIER = 1000000000000000;
    const PAYLOAD_COMMISSION     = 2;
    const SIGNATURE_SINGLE_TYPE  = 1;
    const SIGNATURE_MULTI_TYPE   = 2;
    const MAINNET_CHAIN_ID       = 1;
    const TESTNET_CHAIN_ID       = 2;
    const DEFAULT_GAS_PRICE      = 1;
    const BASE_COIN_ID           = 0;

    /**
     * MinterTx constructor.
     * @param int               $nonce
     * @param MinterTxInterface $txData
     */
    public function __construct(int $nonce, MinterTxInterface $txData)
    {
        $this->setNonce($nonce);
        $this->setData($txData);

        if (!$this->getChainID()) {
            $this->setChainID(self::MAINNET_CHAIN_ID);
        }

        if (!$this->getGasCoin()) {
            $this->setGasCoin(self::BASE_COIN_ID);
        }

        if (!$this->getGasPrice()) {
            $this->setGasPrice(self::DEFAULT_GAS_PRICE);
        }

        parent::__construct();
    }

    /**
     * Sign tx
     *
     * @param string $privateKey
     * @return string
     */
    public function sign(string $privateKey): string
    {
        $this->setSignatureType(self::SIGNATURE_SINGLE_TYPE);
        return MinterPrefix::TRANSACTION . $this->signTransaction($privateKey);
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
        $this->setSignatureType(self::SIGNATURE_MULTI_TYPE);
        return MinterPrefix::TRANSACTION . $this->signTransactionWithMultisig($multisigAddress, $privateKeys);
    }

    /**
     * @param string $multisigAddress
     * @param array  $signatures
     * @return string
     */
    public function signMultisigBySigns(string $multisigAddress, array $signatures): string
    {
        $this->setSignatureType(self::SIGNATURE_MULTI_TYPE);
        return MinterPrefix::TRANSACTION . $this->signTransactionWithSignatures($multisigAddress, $signatures);
    }

    /**
     * Create transaction signature by private key
     *
     * @param string $privateKey
     * @return string
     */
    public function createSignature(string $privateKey): string
    {
        if ($this->getSignatureType() === null) {
            $this->setSignatureType(self::SIGNATURE_SINGLE_TYPE);
        }

        return $this->createEncodedSignature($privateKey);
    }

    /**
     * Get fee of transaction in base coin
     *
     * @return string
     */
    public function getFee(): string
    {
        // get transaction data fee
        $gas = $this->getData()->getFee();

        if ($this->getGasCoin() !== self::BASE_COIN_ID) {
            throw new InvalidArgumentException('Cannot calculate transaction commission with the custom gas coin');
        }

        // multiplied gas price
        $gasPrice = bcmul($gas, self::FEE_DEFAULT_MULTIPLIER, 0);

        // commission for payload and serviceData bytes
        $commission = bcadd(
            strlen($this->payload) * bcmul(self::PAYLOAD_COMMISSION, self::FEE_DEFAULT_MULTIPLIER, 0),
            strlen($this->serviceData) * bcmul(self::PAYLOAD_COMMISSION, self::FEE_DEFAULT_MULTIPLIER, 0)
        );

        $fee = bcadd($gasPrice, $commission, 0);
        return MinterConverter::convertToBase($fee);
    }

    /**
     * Decode transaction
     *
     * @param string $tx
     * @return MinterTx
     */
    public static function decode(string $tx): MinterTx
    {
        $data = Helper::hex2rlp($tx);
        if (count($data) !== 10) {
            throw new InvalidArgumentException('Incorrect transaction raw');
        }

        list($nonce, $chainId, $gasPrice, $gasCoin, $type, $data, $payload, $serviceData, $signatureType, $signatureData) = $data;

        /** @var MinterTxInterface $txData */
        $txData = MinterCoinTx::TYPE_TO_DATA[hexdec($type)];
        $txData = new $txData(...$data);
        $txData->decodeData();

        $tx = new MinterTx(hexdec($nonce), $txData);
        $tx->setGasPrice(hexdec($gasPrice));
        $tx->setGasCoin(hexdec($gasCoin));
        $tx->setChainID(hexdec($chainId));
        $tx->setSignatureType(hexdec($signatureType));
        $tx->setPayload(Helper::hex2str($payload));
        $tx->setServiceData(Helper::hex2str($serviceData));

        $signature = new MinterSignature($tx->getSignatureType(), $signatureData);
        $tx->setSignatureData($signature);

        return $tx;
    }

    /**
     * Get sender Minter address
     *
     * @return string
     */
    public function getSenderAddress(): string
    {
        if ($this->getSignatureType() === self::SIGNATURE_SINGLE_TYPE) {
            return MinterWallet::getAddressFromPublicKey($this->recoverPublicKey());
        }

        return $this->getSignatureData()->getMultisigAddress();
    }

    /**
     * @param int $nonce
     */
    public function setNonce(int $nonce)
    {
        $this->nonce = $nonce;
    }

    /**
     * @param int $gasPrice
     */
    public function setGasPrice(int $gasPrice): void
    {
        $this->gasPrice = $gasPrice;
    }

    /**
     * @param int $gasCoin
     */
    public function setGasCoin(int $gasCoin): void
    {
        $this->gasCoin = $gasCoin;
    }

    /**
     * @param mixed $data
     */
    public function setData($data): void
    {
        $this->data = $data;
    }

    /**
     * @param int $signatureType
     */
    public function setSignatureType(int $signatureType): void
    {
        $this->signatureType = $signatureType;
    }

    /**
     * @param MinterSignature $signatureData
     */
    public function setSignatureData(MinterSignature $signatureData): void
    {
        $this->signatureData = $signatureData;
    }

    /**
     * @param int $chainID
     */
    public function setChainID(int $chainID): void
    {
        $this->chainID = $chainID;
    }

    /**
     * @param mixed $payload
     */
    public function setPayload($payload): void
    {
        $this->payload = $payload;
    }

    /**
     * @param mixed $serviceData
     */
    public function setServiceData($serviceData): void
    {
        $this->serviceData = $serviceData;
    }

    /**
     * @return mixed
     */
    public function getNonce()
    {
        return $this->nonce;
    }

    /**
     * @return mixed
     */
    public function getChainID()
    {
        return $this->chainID;
    }


    /**
     * @return mixed
     */
    public function getGasCoin()
    {
        return $this->gasCoin;
    }

    /**
     * @return mixed
     */
    public function getGasPrice()
    {
        return $this->gasPrice;
    }

    /**
     * @return mixed
     */
    public function getSignatureType()
    {
        return $this->signatureType;
    }

    /**
     * @return mixed
     */
    public function getServiceData()
    {
        return $this->serviceData;
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @return MinterSignature
     */
    public function getSignatureData(): MinterSignature
    {
        return $this->signatureData;
    }

    /**
     * @return MinterSendCoinTx|MinterBuyCoinTx|MinterSellCoinTx|MinterSellAllCoinTx|MinterDelegateTx|MinterUnbondTx|MinterMultiSendTx|MinterCreateMultisigTx|MinterCreateCoinTx|MinterRecreateCoinTx|MinterChangeOwnerTx|MinterDeclareCandidacyTx|MinterSetCandidateOnTx|MinterSetCandidateOffTx|MinterEditCandidateTx|MinterRedeemCheckTx|MinterSetHaltBlockTx
     */
    public function getData(): MinterTxInterface
    {
        return $this->data;
    }
}
