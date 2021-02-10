<?php


namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterConverter;

/**
 * Class MinterRemoveLiquidityTx
 * @package Minter\SDK\MinterCoins
 */
class MinterRemoveLiquidityTx extends MinterCoinTx implements MinterTxInterface
{
    public $coin0;
    public $coin1;
    public $liquidity;
    public $minimumVolume0;
    public $minimumVolume1;

    const TYPE = 22;

    /**
     * MinterRemoveLiquidityTx constructor.
     * @param $coin0
     * @param $coin1
     * @param $liquidity
     * @param $minimumVolume0
     * @param $minimumVolume1
     */
    public function __construct($coin0, $coin1, $liquidity, $minimumVolume0, $minimumVolume1)
    {
        $this->coin0          = $coin0;
        $this->coin1          = $coin1;
        $this->liquidity      = $liquidity;
        $this->minimumVolume0 = $minimumVolume0;
        $this->minimumVolume1 = $minimumVolume1;
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
            MinterConverter::convertToPip($this->liquidity),
            MinterConverter::convertToPip($this->minimumVolume0),
            MinterConverter::convertToPip($this->minimumVolume1)
        ];
    }

    function decodeData()
    {
        $this->coin0          = hexdec($this->coin0);
        $this->coin1          = hexdec($this->coin1);
        $this->liquidity      = MinterConverter::convertToBase(Helper::hexDecode($this->liquidity));
        $this->minimumVolume0 = MinterConverter::convertToBase(Helper::hexDecode($this->minimumVolume0));
        $this->minimumVolume1 = MinterConverter::convertToBase(Helper::hexDecode($this->minimumVolume1));
    }
}