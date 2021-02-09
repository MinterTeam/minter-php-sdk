<?php


namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterConverter;

/**
 * Class MinterBuySwapPoolTx
 * @package Minter\SDK\MinterCoins
 */
class MinterBuySwapPoolTx extends MinterCoinTx implements MinterTxInterface
{
    public $coinToBuy;
    public $coinToSell;
    public $valueToBuy;
    public $maximumValueToSell;

    const TYPE = 24;

    /**
     * MinterBuySwapPoolTx constructor.
     * @param $coinToBuy
     * @param $valueToBuy
     * @param $coinToSell
     * @param $maximumValueToSell
     */
    public function __construct($coinToBuy, $valueToBuy, $coinToSell, $maximumValueToSell)
    {
        $this->coinToBuy          = $coinToBuy;
        $this->coinToSell         = $coinToSell;
        $this->valueToBuy         = $valueToBuy;
        $this->maximumValueToSell = $maximumValueToSell;
    }

    public function encodeData(): array
    {
        return [
            $this->coinToBuy,
            MinterConverter::convertToPip($this->valueToBuy),
            $this->coinToSell,
            MinterConverter::convertToPip($this->maximumValueToSell)
        ];
    }

    public function decodeData()
    {
        $this->coinToBuy          = hexdec($this->coinToBuy);
        $this->valueToBuy         = MinterConverter::convertToBase(Helper::hexDecode($this->valueToBuy));
        $this->coinToSell         = hexdec($this->coinToSell);
        $this->maximumValueToSell = MinterConverter::convertToBase(Helper::hexDecode($this->maximumValueToSell));
    }
}