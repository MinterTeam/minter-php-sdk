<?php


namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterConverter;

/**
 * Class MinterSellSwapPoolTx
 * @package Minter\SDK\MinterCoins
 */
class MinterSellSwapPoolTx extends MinterCoinTx implements MinterTxInterface
{
    public $coinToBuy;
    public $coinToSell;
    public $valueToSell;
    public $minimumValueToBuy;

    const TYPE = 23;

    /**
     * MinterSellSwapPoolTx constructor.
     * @param $coinToSell
     * @param $valueToSell
     * @param $coinToBuy
     * @param $minimumValueToBuy
     */
    public function __construct($coinToSell, $valueToSell, $coinToBuy, $minimumValueToBuy)
    {
        $this->coinToBuy         = $coinToBuy;
        $this->coinToSell        = $coinToSell;
        $this->valueToSell       = $valueToSell;
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
            MinterConverter::convertToPip($this->valueToSell),
            $this->coinToBuy,
            MinterConverter::convertToPip($this->minimumValueToBuy)
        ];
    }

    public function decodeData()
    {
        $this->coinToSell        = hexdec($this->coinToSell);
        $this->valueToSell       = MinterConverter::convertToBase(Helper::hexDecode($this->valueToSell));
        $this->coinToBuy         = hexdec($this->coinToBuy);
        $this->minimumValueToBuy = MinterConverter::convertToBase(Helper::hexDecode($this->minimumValueToBuy));
    }
}