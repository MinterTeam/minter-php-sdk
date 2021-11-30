<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterConverter;

/**
 * Class MinterAddLimitOrderTx
 * @package Minter\SDK\MinterCoins
 */
class MinterAddLimitOrderTx extends MinterCoinTx implements MinterTxInterface
{
    public $coinToSell;
    public $coinToBuy;
    public $valueToSell;
    public $valueToBuy;

    const TYPE = 35;

    /**
     * @param $coinToSell
     * @param $valueToSell
     * @param $coinToBuy
     * @param $valueToBuy
     */
    public function __construct($coinToSell, $valueToSell, $coinToBuy, $valueToBuy)
    {
        $this->coinToSell  = $coinToSell;
        $this->coinToBuy   = $coinToBuy;
        $this->valueToSell = $valueToSell;
        $this->valueToBuy  = $valueToBuy;
    }

    /**
     * Prepare data for signing
     *
     * @return array
     */
    function encodeData(): array
    {
        return [
            $this->coinToSell,
            MinterConverter::convertToPip($this->valueToSell),
            $this->coinToBuy,
            MinterConverter::convertToPip($this->valueToBuy)
        ];
    }

    function decodeData()
    {
        $this->coinToSell  = hexdec($this->coinToSell);
        $this->coinToBuy   = hexdec($this->coinToBuy);
        $this->valueToSell = MinterConverter::convertToBase(Helper::hexDecode($this->valueToSell));
        $this->valueToBuy  = MinterConverter::convertToBase(Helper::hexDecode($this->valueToBuy));
    }
}