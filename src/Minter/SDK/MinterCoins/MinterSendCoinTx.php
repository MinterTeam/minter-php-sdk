<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterConverter;

/**
 * Class MinterSendCoinTx
 * @package Minter\SDK\MinterCoins
 */
class MinterSendCoinTx extends MinterCoinTx implements MinterTxInterface
{
    /**
     * Type
     */
    const TYPE = 1;

    /**
     * Fee units
     */
    const COMMISSION = 10;

    /**
     * Send coin tx data
     *
     * @var array
     */
    public $data = [
        'coin' => '',
        'to' => '',
        'value' => ''
    ];

    /**
     * Prepare data for signing
     *
     * @return array
     */
    public function encode(): array
    {
        return [
            'coin' => $this->data['coin'],

            'to' => hex2bin(
                Helper::removeWalletPrefix($this->data['to'])
            ),

            'value' => MinterConverter::convertToPip($this->data['value'])
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
            'coin' => Helper::hexDecode($txData[0]),

            'to' => Helper::addWalletPrefix($txData[1]),

            'value' => MinterConverter::convertToBase(Helper::hexDecode($txData[2]))
        ];
    }
}