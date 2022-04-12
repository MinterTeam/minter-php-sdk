<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterConverter;

/**
 * Class MinterLockTx
 * @package Minter\SDK\MinterCoins
 */
class MinterLockTx extends MinterCoinTx implements MinterTxInterface
{
    public $dueBlock;
    public $coin;
    public $value;

    const TYPE = 38;

    /**
     * @param $dueBlock
     * @param $coin
     * @param $value
     */
    public function __construct($dueBlock, $coin, $value)
    {
        $this->dueBlock = $dueBlock;
        $this->coin     = $coin;
        $this->value    = $value;
    }

    /**
     * Prepare data for signing
     *
     * @return array
     */
    function encodeData(): array
    {
        return [
            $this->dueBlock,
            $this->coin,
            MinterConverter::convertToPip($this->value),
        ];
    }

    function decodeData()
    {
        $this->dueBlock = hexdec($this->dueBlock);
        $this->coin     = hexdec($this->coin);
        $this->value    = MinterConverter::convertToBase(Helper::hexDecode($this->value));
    }
}