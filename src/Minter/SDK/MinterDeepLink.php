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

    /** @var string */
    private $checkPassword;

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
     * @param string $p
     */
    public function setCheckPassword(string $p): void
    {
        $this->checkPassword = $p;
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

        $params = ['d' => $this->rlp->encode($deepLink)->toString('hex')];
        if($this->checkPassword) {
            $checkPassword = str_split($this->checkPassword, 1);
            $checkPassword = new Buffer($checkPassword);
            $params['p']   = $this->rlp->encode($checkPassword)->toString('hex');
        }

        return self::LINK_BASE_URL . '?' . http_build_query($params);
    }
}