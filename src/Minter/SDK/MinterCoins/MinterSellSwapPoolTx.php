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
    public $coins;
    public $valueToSell;
    public $minimumValueToBuy;

    const TYPE = 23;

    /**
     * MinterSellSwapPoolTx constructor.
     * @param array $coins
     * @param       $valueToSell
     * @param       $minimumValueToBuy
     */
    public function __construct(array $coins, $valueToSell, $minimumValueToBuy)
    {
        $this->coins             = $coins;
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
            $this->coins,
            MinterConverter::convertToPip($this->valueToSell),
            MinterConverter::convertToPip($this->minimumValueToBuy)
        ];
    }

    public function decodeData()
    {
        $this->valueToSell       = MinterConverter::convertToBase(Helper::hexDecode($this->valueToSell));
        $this->minimumValueToBuy = MinterConverter::convertToBase(Helper::hexDecode($this->minimumValueToBuy));
        $this->coins             = array_map(function ($value) {
            return hexdec($value);
        }, $this->coins);
    }
}