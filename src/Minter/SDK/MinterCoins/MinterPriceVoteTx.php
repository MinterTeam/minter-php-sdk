<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterConverter;

/**
 * Class MinterPriceVoteTx
 * @package Minter\SDK\MinterCoins
 */
class MinterPriceVoteTx extends MinterCoinTx implements MinterTxInterface
{
    const TYPE       = 19;
    const COMMISSION = 10;

    /**
     * Send coin tx data
     *
     * @var array
     */
    public $data = [
        'price' => '',
    ];

    /**
     * Prepare data for signing
     *
     * @return array
     */
    public function encode(): array
    {
        return [
            'price' => $this->data['price'],
        ];
    }

    /**
     * Prepare output tx data
     *
     * @param array $txData
     * @return array
     */
    public function decode(array $txData): array
    {
        return [
            'price' => hexdec($txData[0])
        ];
    }
}