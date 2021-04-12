<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterConverter;
use Minter\SDK\MinterPrefix;

/**
 * Class MinterUnbondTx
 * @package Minter\SDK\MinterCoins
 */
class MinterUnbondTx extends MinterCoinTx implements MinterTxInterface
{
    public $publicKey;
    public $coin;
    public $value;

    const TYPE = 8;

    /**
     * MinterUnbondTx constructor.
     * @param $publicKey
     * @param $coin
     * @param $value
     */
    public function __construct($publicKey, $coin, $value)
    {
        $this->publicKey = $publicKey;
        $this->coin      = $coin;
        $this->value     = $value;
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
            MinterConverter::convertToPip($this->value)
        ];
    }

    public function decodeData()
    {
        $this->publicKey = MinterPrefix::PUBLIC_KEY . $this->publicKey;
        $this->coin      = hexdec($this->coin);
        $this->value     = MinterConverter::convertToBase(Helper::hexDecode($this->value));
    }
}