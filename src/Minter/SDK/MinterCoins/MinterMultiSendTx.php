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

    const TYPE = 13;

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
        $list = ['list' => []];

        foreach ($this->list as $key => $data) {
            /** @var $data MinterSendCoinTx */
           $list['list'][$key] = $data->encodeData();
        }

        return $list;
    }

    public function decodeData()
    {
        foreach ($this->list as $key => $data) {
            $send = new MinterSendCoinTx(...$data);
            $send->decodeData();

            $this->list[$key] = $send;
        }
    }
}