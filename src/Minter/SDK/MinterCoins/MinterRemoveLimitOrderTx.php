<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;

/**
 * Class MinterRemoveLimitOrderTx
 * @package Minter\SDK\MinterCoins
 */
class MinterRemoveLimitOrderTx extends MinterCoinTx implements MinterTxInterface
{
    public $id;

    const TYPE = 36;

    /**
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Prepare data for signing
     *
     * @return array
     */
    function encodeData(): array
    {
        return [
            $this->id,
        ];
    }

    function decodeData()
    {
        $this->id = hexdec($this->id);
    }
}