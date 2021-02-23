<?php


namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterConverter;

/**
 * Class MinterSellAllSwapPoolTx
 * @package Minter\SDK\MinterCoins
 */
class MinterSellAllSwapPoolTx extends MinterCoinTx implements MinterTxInterface
{
    public $coins;
    public $minimumValueToBuy;

    const TYPE = 25;

    /**
     * MinterSellAllSwapPoolTx constructor.
     * @param array $coins
     * @param       $minimumValueToBuy
     */
    public function __construct(array $coins, $minimumValueToBuy)
    {
        $this->coins             = $coins;
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
            MinterConverter::convertToPip($this->minimumValueToBuy)
        ];
    }

    public function decodeData()
    {
        $this->minimumValueToBuy = MinterConverter::convertToBase(Helper::hexDecode($this->minimumValueToBuy));
        $this->coins             = array_map(function ($value) {
            return hexdec($value);
        }, $this->coins);
    }
}