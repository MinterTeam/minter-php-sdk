<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterConverter;

/**
 * Class MinterConvertCoinTx
 * @package Minter\SDK\MinterCoins
 */
class MinterConvertCoinTx extends MinterCoinTx implements MinterTxInterface
{
    /**
     * Type
     */
    const TYPE = 2;

    /**
     * Fee in PIP
     */
    const COMMISSION = 10000;

    /**
     * Send coin tx data
     *
     * @var array
     */
    public $data = [
        'coin_from' => '',
        'coin_to' => '',
        'value' => ''
    ];

    /**
     * Prepare tx data for signing
     *
     * @return array
     */
    public function encode(): array
    {
        return [
            // Add nulls before the coin name
            'coin_from' => MinterConverter::convertCoinName($this->data['coin_from']),

            // Add nulls before the coin name
            'coin_to' => MinterConverter::convertCoinName($this->data['coin_to']),

            // Convert value from BIP to PIP
            'value' => MinterConverter::convertValue($this->data['value'], 'pip')
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
            // Pack binary to hex
            'coin_from' => Helper::pack2hex($txData[0]),

            // Pack binary to hex
            'coin_to' => Helper::pack2hex($txData[1]),

            // Convert value from PIP to BIP
            'value' => MinterConverter::convertValue(Helper::hexDecode($txData[2]), 'bip')
        ];
    }
}