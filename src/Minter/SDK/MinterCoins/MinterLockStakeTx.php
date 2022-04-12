<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;

/**
 * Class MinterLockStakeTx
 * @package Minter\SDK\MinterCoins
 */
class MinterLockStakeTx extends MinterCoinTx implements MinterTxInterface
{
    const TYPE = 37;

    public function __construct()
    {

    }

    /**
     * Prepare data for signing
     *
     * @return array
     */
    function encodeData(): array
    {
        return [];
    }

    function decodeData()
    {

    }
}