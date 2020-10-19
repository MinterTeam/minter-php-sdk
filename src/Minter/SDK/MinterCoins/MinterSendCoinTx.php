<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterConverter;

/**
 * Class MinterSendCoinTx
 * @package Minter\SDK\MinterCoins
 */
class MinterSendCoinTx extends MinterCoinTx implements MinterTxInterface
{
    public $coin;
    public $to;
    public $value;

    const TYPE       = 1;
    const COMMISSION = 10;

    /**
     * MinterSendCoinTx constructor.
     * @param $coin
     * @param $to
     * @param $value
     */
    public function __construct($coin, $to, $value)
    {
        $this->coin  = $coin;
        $this->to    = $to;
        $this->value = $value;
    }

    public function encodeData(): array
    {
        return [
            $this->coin,
            hex2bin(Helper::removeWalletPrefix($this->to)),
            MinterConverter::convertToPip($this->value)
        ];
    }

    public function decodeData()
    {
        $this->coin  = hexdec($this->coin);
        $this->to    = Helper::addWalletPrefix($this->to);
        $this->value = MinterConverter::convertToBase(Helper::hexDecode($this->value));
    }
}