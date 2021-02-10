<?php


namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterConverter;

/**
 * Class MinterCreateSwapPoolTx
 * @package Minter\SDK\MinterCoins
 */
class MinterCreateSwapPoolTx extends MinterCoinTx implements MinterTxInterface
{
    public $coin0;
    public $coin1;
    public $volume0;
    public $volume1;

    const TYPE = 34;

    /**
     * MinterCreateSwapPoolTx constructor.
     * @param $coin0
     * @param $coin1
     * @param $volume0
     * @param $volume1
     */
    public function __construct($coin0, $coin1, $volume0, $volume1)
    {
        $this->coin0   = $coin0;
        $this->coin1   = $coin1;
        $this->volume0 = $volume0;
        $this->volume1 = $volume1;
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
            MinterConverter::convertToPip($this->volume1)
        ];
    }

    function decodeData()
    {
        $this->coin0   = hexdec($this->coin0);
        $this->coin1   = hexdec($this->coin1);
        $this->volume0 = MinterConverter::convertToBase(Helper::hexDecode($this->volume0));
        $this->volume1 = MinterConverter::convertToBase(Helper::hexDecode($this->volume1));
    }
}