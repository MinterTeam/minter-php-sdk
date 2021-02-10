<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterConverter;
use Minter\SDK\MinterPrefix;

/**
 * Class MinterDelegateTx
 * @package Minter\SDK\MinterCoins
 */
class MinterDelegateTx extends MinterCoinTx implements MinterTxInterface
{
    public $publicKey;
    public $coin;
    public $stake;

    const TYPE = 7;

    /**
     * MinterDelegateTx constructor.
     * @param $publicKey
     * @param $coin
     * @param $stake
     */
    public function __construct($publicKey, $coin, $stake)
    {
        $this->publicKey = $publicKey;
        $this->coin      = $coin;
        $this->stake     = $stake;
    }

    /**
     * Prepare data for signing
     *
     * @return array
     */
    public function encodeData(): array
    {
        return [
            hex2bin(Helper::removePrefix($this->publicKey, MinterPrefix::PUBLIC_KEY)),
            $this->coin,
            MinterConverter::convertToPip($this->stake)
        ];
    }

    public function decodeData()
    {
        $this->publicKey = MinterPrefix::PUBLIC_KEY . $this->publicKey;
        $this->coin      = hexdec($this->coin);
        $this->stake     = MinterConverter::convertToBase(Helper::hexDecode($this->stake));
    }
}