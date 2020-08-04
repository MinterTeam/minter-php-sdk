<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterPrefix;

/**
 * Class MinterSetHaltBlockTx
 * @package Minter\SDK\MinterCoins
 */
class MinterSetHaltBlockTx extends MinterCoinTx implements MinterTxInterface
{
    /**
     * Send coin tx data
     *
     * @var array
     */
    public $data = [
        'pubkey' => '',
        'height' => '',
    ];

    const TYPE       = 15;
    const COMMISSION = 1000;

    /**
     * Prepare data for signing
     *
     * @return array
     */
    public function encode(): array
    {
        return [
            'pubkey' => hex2bin(Helper::removePrefix($this->data['pubkey'], MinterPrefix::PUBLIC_KEY)),
            'height' => $this->data['height'],
        ];
    }

    public function decode(array $txData): array
    {
        return [
            'pubkey' => MinterPrefix::PUBLIC_KEY . $txData[0],
            'height' => (int) hexdec($txData[1])
        ];
    }
}