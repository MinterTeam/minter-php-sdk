<?php


namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterConverter;

/**
 * Class MinterAddLiquidityTx
 * @package Minter\SDK\MinterCoins
 */
class MinterAddLiquidityTx extends MinterCoinTx implements MinterTxInterface
{
    public $coin0;
    public $coin1;
    public $volume0;
    public $maximumVolume1;

    const TYPE = 21;

    /**
     * MinterAddLiquidityTx constructor.
     * @param $coin0
     * @param $coin1
     * @param $volume0
     * @param $maximumVolume1
     */
    public function __construct($coin0, $coin1, $volume0, $maximumVolume1)
    {
        $this->coin0          = $coin0;
        $this->coin1          = $coin1;
        $this->volume0        = $volume0;
        $this->maximumVolume1 = $maximumVolume1;
    }

    /**
     * Prepare data for signing
     *
     * @return array
     */
    function encodeData(): array
    {
        return [
            $this->coin0,
            $this->coin1,
            MinterConverter::convertToPip($this->volume0),
            MinterConverter::convertToPip($this->maximumVolume1)
        ];
    }

    function decodeData()
    {
        $this->coin0           = hexdec($this->coin0);
        $this->coin1           = hexdec($this->coin1);
        $this->volume0         = MinterConverter::convertToBase(Helper::hexDecode($this->volume0));
        $this->maximumVolume1  = MinterConverter::convertToBase(Helper::hexDecode($this->maximumVolume1));
    }
}