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
    public $fromPubKey;
    public $toPubKey;
    public $coin;
    public $value;

    const TYPE = 27;

    /**
     * @param $fromPubKey
     * @param $toPubKey
     * @param $coin
     * @param $value
     */
    public function __construct($fromPubKey, $toPubKey, $coin, $value)
    {
        $this->fromPubKey = $fromPubKey;
        $this->toPubKey   = $toPubKey;
        $this->coin       = $coin;
        $this->value      = $value;
    }

    /**
     * Prepare data for signing
     *
     * @return array
     */
    function encodeData(): array
    {
        return [
            hex2bin(Helper::removePrefix($this->fromPubKey, MinterPrefix::PUBLIC_KEY)),
            hex2bin(Helper::removePrefix($this->toPubKey, MinterPrefix::PUBLIC_KEY)),
            $this->coin,
            MinterConverter::convertToPip($this->value),
        ];
    }

    function decodeData()
    {
        $this->fromPubKey = MinterPrefix::PUBLIC_KEY . $this->fromPubKey;
        $this->toPubKey   = MinterPrefix::PUBLIC_KEY . $this->toPubKey;
        $this->coin       = hexdec($this->coin);
        $this->value      = MinterConverter::convertToBase(Helper::hexDecode($this->value));
    }
}