<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterConverter;

/**
 * Class MinterChangeOwnerTx
 * @package Minter\SDK\MinterCoins
 */
class MinterChangeOwnerTx extends MinterCoinTx implements MinterTxInterface
{
    public $symbol;
    public $newOwner;

    const TYPE       = 17;
    const COMMISSION = 10000000;

    /**
     * MinterChangeOwnerTx constructor.
     * @param $symbol
     * @param $newOwner
     */
    public function __construct($symbol, $newOwner)
    {
        $this->symbol   = $symbol;
        $this->newOwner = $newOwner;
    }

    /**
     * Prepare tx data for signing
     *
     * @return array
     */
    public function encodeData(): array
    {
        return [
            MinterConverter::convertCoinName($this->symbol),
            hex2bin(Helper::removeWalletPrefix($this->newOwner)),
        ];
    }

    public function decodeData()
    {
        $this->symbol   = Helper::hexDecode($this->symbol);
        $this->newOwner = Helper::addWalletPrefix($this->newOwner);
    }
}