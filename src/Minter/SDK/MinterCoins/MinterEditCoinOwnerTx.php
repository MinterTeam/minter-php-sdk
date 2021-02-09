<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterConverter;

/**
 * Class MinterEditCoinOwnerTx
 * @package Minter\SDK\MinterCoins
 */
class MinterEditCoinOwnerTx extends MinterCoinTx implements MinterTxInterface
{
    public $symbol;
    public $newOwner;

    const TYPE = 17;

    /**
     * MinterEditCoinOwnerTx constructor.
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
        $this->symbol   = Helper::hex2str($this->symbol);
        $this->newOwner = Helper::addWalletPrefix($this->newOwner);
    }
}