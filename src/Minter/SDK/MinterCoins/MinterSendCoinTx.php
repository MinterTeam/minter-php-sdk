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
            // Add nulls before coin name
            'coin' => MinterConverter::convertCoinName($this->data['coin']),

            // Remove Minter wallet prefix and convert hex string to binary
            'to' => hex2bin(
                Helper::removeWalletPrefix($this->data['to'])
            ),

            // Convert from BIP to PIP
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
            // Pack binary to string
            'coin' => Helper::hex2str($txData[0]),

            // Add Minter wallet prefix to string
            'to' => Helper::addWalletPrefix($txData[1]),

            // Convert value from PIP to BIP
            'value' => MinterConverter::convertValue(Helper::hexDecode($txData[2]), 'bip')
        ];
    }
}