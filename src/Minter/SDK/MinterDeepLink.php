<?php

namespace Minter\SDK;

use Minter\Contracts\MinterTxInterface;
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
     */
    public function setPayload(string $payload): void
    {
        $this->payload = $payload;
    }

    /**
     * @param string|int $nonce
     */
    public function setNonce($nonce): void
    {
        $this->nonce = $nonce;
    }

    /**
     * @param string $gasCoin
     */
    public function setGasCoin(string $gasCoin): void
    {
        $this->gasCoin = $gasCoin;
    }

    /**
     * @param string|int $gasPrice
     */
    public function setGasPrice($gasPrice): void
    {
        $this->gasPrice = $gasPrice;
    }

    /**
     * @return string
     */
    public function encode(): string
    {
        $payload        = str_split($this->payload, 1);
        $payload        = new Buffer($payload);
        $gasCoin        = $this->gasCoin ? MinterConverter::convertCoinName($this->gasCoin) : '';
        $rlpEncodedData = $this->rlp->encode($this->data->data);

        $deepLink = [
            'type'     => $this->data->getType(),
            'data'     => $rlpEncodedData,
            'payload'  => $payload,
            'nonce'    => $this->nonce,
            'gasPrice' => $this->gasPrice,
            'gasCoin'  => $gasCoin
        ];

        return $this->rlp->encode($deepLink)->toString('hex');
    }
}