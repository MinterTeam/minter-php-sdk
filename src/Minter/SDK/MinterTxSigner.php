<?php

namespace Minter\SDK;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\ECDSA;
use Minter\Library\Helper;
use Web3p\RLP\Buffer;
use Web3p\RLP\RLP;

/**
 * Class MinterTxSigner
 * @package Minter\SDK
 */
class MinterTxSigner
{
    protected $nonce;
    protected $chainID;
    protected $gasPrice;
    protected $gasCoin;
    protected $payload;
    protected $serviceData;
    protected $signatureType;

    /** @var MinterSignature */
    protected $signatureData;

    /** @var MinterTxInterface */
    protected $data;

    /** @var RLP */
    private $rlp;

    /**
     * MinterTxSigner constructor.
     */
    public function __construct()
    {
        $this->rlp = new RLP();
    }

    /**
     * @param string $privateKey
     * @return Buffer
     */
    public function createEncodedSignature(string $privateKey)
    {
        $tx        = $this->encodeRLP();
        $hash      = Helper::createKeccakHash($tx);
        $signature = $this->createSignatureByHash($hash, $privateKey);

        return $this->rlp->encode($signature);
    }

    /**
     * @param string $privateKey
     * @return string
     */
    protected function signTransaction(string $privateKey): string
    {
        $tx        = $this->encodeRLP();
        $hash      = Helper::createKeccakHash($tx);
        $signature = $this->createSignatureByHash($hash, $privateKey);

        return $this->encodeRLP($signature);
    }

    /**
     * @param string $multisigAddress
     * @param array  $privateKeys
     * @return string
     */
    protected function signTransactionWithMultisig(string $multisigAddress, array $privateKeys): string
    {
        $tx   = $this->encodeRLP();
        $hash = Helper::createKeccakHash($tx);

        $signatures = [];
        foreach ($privateKeys as $privateKey) {
            $signatures[] = $this->createSignatureByHash($hash, $privateKey);
        }

        return $this->signMultisig($multisigAddress, $signatures);
    }

    /**
     * @param string $multisigAddress
     * @param array  $signatures
     * @return string
     */
    protected function signTransactionWithSignatures(string $multisigAddress, array $signatures): string
    {
        foreach ($signatures as $key => $signature) {
            $signatures[$key] = $this->rlp->decode('0x' . $signature);
        }

        return $this->signMultisig($multisigAddress, $signatures);
    }

    /**
     * Recover sender public key from signature
     *
     * @return string
     */
    protected function recoverPublicKey(): string
    {
        $tx        = $this->encodeRLP();
        $hash      = Helper::createKeccakHash($tx);
        $signature = $this->signatureData->getSignatures()[0];

        return MinterPrefix::PUBLIC_KEY . ECDSA::recover($hash, $signature);
    }

    /**
     * @param string $multisigAddress
     * @param array  $signatures
     * @return string
     */
    private function signMultisig(string $multisigAddress, array $signatures): string
    {
        $multisigAddress = Helper::removeWalletPrefix($multisigAddress);
        $multisigAddress = hex2bin($multisigAddress);

        return $this->encodeRLP([$multisigAddress, $signatures]);
    }

    /**
     * @param string $hash
     * @param string $privateKey
     * @return array|Buffer
     */
    private function createSignatureByHash(string $hash, string $privateKey)
    {
        $signature = ECDSA::sign($hash, $privateKey);
        $signature = Helper::hex2buffer($signature);
        return $signature;
    }

    /**
     * Encode transaction to RLP
     *
     * @param array|null $signature
     * @return Buffer
     */
    private function encodeRLP(?array $signature = null): Buffer
    {
        $tx = [
            $this->nonce,
            $this->chainID,
            $this->gasPrice,
            $this->gasCoin,
            $this->data->getType(),
            $this->data->encode(),
            Helper::str2buffer($this->payload ?? ''),
            $this->serviceData ?? '',
            $this->signatureType
        ];

        if ($signature) {
            $tx[] = $this->rlp->encode($signature);
        }

        return $this->rlp->encode($tx);
    }
}