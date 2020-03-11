<?php

namespace Minter\SDK;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Web3p\RLP\Buffer;
use Web3p\RLP\RLP;

/**
 * Class MinterDeepLink
 * @package Minter\SDK
 */
class MinterDeepLink
{
    /** @var MinterTxInterface */
    private $data;

    /** @var string|null */
    private $payload;

    /** @var int|null */
    private $gasPrice;

    /** @var string|null */
    private $gasCoin;

    /** @var int|null */
    private $nonce;

    /** @var RLP */
    private $rlp;

    /** @var string */
    private $checkPassword;

    /** @var string */
    private $uri;

    /** @var string */
    private const LINK_BASE_URL = 'https://bip.to/tx';

    /**
     * MinterDeepLink constructor.
     * @param MinterTxInterface $txData
     */
    public function __construct(MinterTxInterface $txData)
    {
        $this->data = $txData;
        $this->rlp  = new RLP();
    }

    /**
     * @param string $payload
     * @return MinterDeepLink
     */
    public function setPayload(string $payload): MinterDeepLink
    {
        $this->payload = $payload;
        return $this;
    }

    /**
     * @param string|int $nonce
     * @return MinterDeepLink
     */
    public function setNonce($nonce): MinterDeepLink
    {
        $this->nonce = $nonce;
        return $this;
    }

    /**
     * @param string $gasCoin
     * @return MinterDeepLink
     */
    public function setGasCoin(string $gasCoin): MinterDeepLink
    {
        $this->gasCoin = $gasCoin;
        return $this;
    }

    /**
     * @param string|int $gasPrice
     * @return MinterDeepLink
     */
    public function setGasPrice($gasPrice): MinterDeepLink
    {
        $this->gasPrice = $gasPrice;
        return $this;
    }

    /**
     * @param string $p
     * @return MinterDeepLink
     */
    public function setCheckPassword(string $p): MinterDeepLink
    {
        $this->checkPassword = $p;
        return $this;
    }

    /**
     * @param string $uri
     * @return MinterDeepLink
     */
    public function setHost(string $uri): MinterDeepLink
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->uri ?? self::LINK_BASE_URL;
    }

    /**
     * @return string
     */
    public function encode(): string
    {
        $payload        = Helper::str2buffer($this->payload ?? '');
        $gasCoin        = $this->gasCoin ? MinterConverter::convertCoinName($this->gasCoin) : '';
        $rlpEncodedData = $this->rlp->encode($this->data->data);
        $txType         = $this->data->getType();

        $deepLink = [
            'type'     => $txType,
            'data'     => $rlpEncodedData,
            'payload'  => $payload,
            'nonce'    => $this->nonce,
            'gasPrice' => $this->gasPrice,
            'gasCoin'  => $gasCoin
        ];

        $data = $this->rlp->encode($deepLink);
        $data = hex2bin($data);
        $data = Helper::base64urlEncode($data);

        $url = $this->getHost() . '/' . $data;
        if ($this->checkPassword) {
            $checkPassword = Helper::str2buffer($this->checkPassword);
            $checkPassword = Helper::base64urlEncode($checkPassword);
            $url           .= "?p=" . $checkPassword;
        }

        return $url;
    }
}