<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterConverter;
use Minter\SDK\MinterPrefix;

/**
 * Class MinterMoveStakeTx
 * @package Minter\SDK\MinterCoins
 */
class MinterMoveStakeTx extends MinterCoinTx implements MinterTxInterface
{
    public $from;
    public $to;
    public $coin;
    public $stake;

    const TYPE = 27;

    /**
     * MinterMoveStakeTx constructor.
     * @param $from
     * @param $to
     * @param $coin
     * @param $stake
     */
    public function __construct($from, $to, $coin, $stake)
    {
        $this->from  = $from;
        $this->to    = $to;
        $this->coin  = $coin;
        $this->stake = $stake;
    }

    /**
     * Prepare data for signing
     *
     * @return array
     */
    public function encodeData(): array
    {
        return [
            hex2bin(Helper::removePrefix($this->from, MinterPrefix::PUBLIC_KEY)),
            hex2bin(Helper::removePrefix($this->to, MinterPrefix::PUBLIC_KEY)),
            $this->coin,
            MinterConverter::convertToPip($this->stake)
        ];
    }

    public function decodeData()
    {
        $this->from  = MinterPrefix::PUBLIC_KEY . $this->from;
        $this->to    = MinterPrefix::PUBLIC_KEY . $this->to;
        $this->coin  = hexdec($this->coin);
        $this->stake = MinterConverter::convertToBase(Helper::hexDecode($this->stake));
    }
}