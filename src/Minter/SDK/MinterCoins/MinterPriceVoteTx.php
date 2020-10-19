<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;

/**
 * Class MinterPriceVoteTx
 * @package Minter\SDK\MinterCoins
 */
class MinterPriceVoteTx extends MinterCoinTx implements MinterTxInterface
{
    public $price;

    const TYPE       = 19;
    const COMMISSION = 10;

    /**
     * MinterPriceVoteTx constructor.
     * @param $price
     */
    public function __construct($price)
    {
        $this->price = $price;
    }

    public function encodeData(): array
    {
        return [
            $this->price,
        ];
    }

    public function decodeData()
    {
        $this->price = hexdec($this->price);
    }
}