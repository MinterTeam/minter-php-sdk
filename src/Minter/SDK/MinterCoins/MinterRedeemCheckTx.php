<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterPrefix;

/**
 * Class MinterRedeemCheckTx
 * @package Minter\SDK\MinterCoins
 */
class MinterRedeemCheckTx extends MinterCoinTx implements MinterTxInterface
{
    public $check;
    public $proof;

    const TYPE = 9;

    /**
     * MinterRedeemCheckTx constructor.
     * @param $check
     * @param $proof
     */
    public function __construct($check, $proof)
    {
        $this->check = $check;
        $this->proof = $proof;
    }

    /**
     * Prepare data for signing
     *
     * @return array
     */
    public function encodeData(): array
    {
        return [
            hex2bin(Helper::removePrefix($this->check, MinterPrefix::CHECK)),
            hex2bin($this->proof)
        ];
    }

    public function decodeData()
    {
        $this->check = MinterPrefix::CHECK . $this->check;
        $this->proof = (string) $this->proof;
    }
}