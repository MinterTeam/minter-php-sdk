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
    public $coins;
    public $valueToBuy;
    public $maximumValueToSell;

    const TYPE = 24;

    /**
     * MinterBuySwapPoolTx constructor.
     * @param $coins
     * @param $valueToBuy
     * @param $maximumValueToSell
     */
    public function __construct($coins, $valueToBuy, $maximumValueToSell)
    {
        $this->coins              = $coins;
        $this->valueToBuy         = $valueToBuy;
        $this->maximumValueToSell = $maximumValueToSell;
    }

    public function encodeData(): array
    {
        return [
            $this->coins,
            MinterConverter::convertToPip($this->valueToBuy),
            MinterConverter::convertToPip($this->maximumValueToSell)
        ];
    }

    public function decodeData()
    {
        $this->valueToBuy         = MinterConverter::convertToBase(Helper::hexDecode($this->valueToBuy));
        $this->maximumValueToSell = MinterConverter::convertToBase(Helper::hexDecode($this->maximumValueToSell));
        $this->coins              = array_map(function ($value) {
            return hexdec($value);
        }, $this->coins);
    }
}