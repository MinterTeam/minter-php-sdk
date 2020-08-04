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
    const TYPE       = 17;
    const COMMISSION = 10000000;

    public $data = [
        'symbol'   => '',
        'newOwner' => ''
    ];

    /**
     * Prepare tx data for signing
     *
     * @return array
     */
    public function encode(): array
    {
        return [
            'symbol'   => MinterConverter::convertCoinName($this->data['symbol']),
            'newOwner' => hex2bin(Helper::removeWalletPrefix($this->data['newOwner'])),
        ];
    }

    public function decode(array $txData): array
    {
        return [
            'symbol'   => $txData[0],
            'newOwner' => $txData[1]
        ];
    }
}