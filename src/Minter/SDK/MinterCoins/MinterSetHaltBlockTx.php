<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterPrefix;

/**
 * Class MinterSetHaltBlockTx
 * @package Minter\SDK\MinterCoins
 */
class MinterSetHaltBlockTx extends MinterCoinTx implements MinterTxInterface
{
    public $publicKey;
    public $height;

    const TYPE       = 15;
    const COMMISSION = 1000;

    /**
     * MinterUnbondTx constructor.
     * @param $publicKey
     * @param $height
     */
    public function __construct($publicKey, $height)
    {
        $this->publicKey = $publicKey;
        $this->height    = $height;
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
            $this->height,
        ];
    }

    public function decodeData()
    {
        $this->publicKey = MinterPrefix::PUBLIC_KEY . $this->publicKey;
        $this->height    = (int)hexdec($this->height);
    }
}