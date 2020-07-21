<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;

/**
 * Class MinterMultiSendTx
 * @package Minter\SDK\MinterCoins
 */
class MinterMultiSendTx extends MinterCoinTx implements MinterTxInterface
{
    public $list;

    const TYPE       = 13;
    const COMMISSION = 5;

    /**
     * MinterMultiSendTx constructor.
     * @param $list
     */
    public function __construct($list)
    {
        $this->list = $list;
    }

    /**
     * Prepare tx data for signing
     *
     * @return array
     */
    public function encodeData(): array
    {
        foreach ($this->list as $key => $data) {
            $this->list[$key] = new MinterSendCoinTx(...$data);
        }

        return $this->list;
    }

    public function decodeData()
    {
        foreach ($this->list as $key => $data) {
            $send = new MinterSendCoinTx(...$data);
            $send->decodeData();

            $this->list[$key] = $send;
        }
    }

    public function getFee()
    {
        return MinterSendCoinTx::COMMISSION + (count($this->list) - 1) * self::COMMISSION;
    }
}