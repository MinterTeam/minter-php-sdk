<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterConverter;

/**
 * Class MinterSellAllCoinTx
 * @package Minter\SDK\MinterCoins
 */
class MinterSellAllCoinTx extends MinterCoinTx implements MinterTxInterface
{
    public $coinToBuy;
    public $coinToSell;
    public $minimumValueToBuy;

    const TYPE       = 3;
    const COMMISSION = 100;

    /**
     * MinterSellAllCoinTx constructor.
     * @param $coinToSell
     * @param $coinToBuy
     * @param $minimumValueToBuy
     */
    public function __construct($coinToSell, $coinToBuy, $minimumValueToBuy)
    {
        $this->coinToBuy         = $coinToBuy;
        $this->coinToSell        = $coinToSell;
        $this->minimumValueToBuy = $minimumValueToBuy;
    }

    /**
     * Prepare tx data for signing
     *
     * @return array
     */
    public function encodeData(): array
    {
        return [
            $this->coinToSell,
            $this->coinToBuy,
            MinterConverter::convertToPip($this->minimumValueToBuy)
        ];
    }

    public function decodeData()
    {
        $this->coinToSell        = hexdec($this->coinToSell);
        $this->coinToBuy         = hexdec($this->coinToBuy);
        $this->minimumValueToBuy = MinterConverter::convertToBase(Helper::hexDecode($this->minimumValueToBuy));
    }
}