<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterConverter;
use Minter\SDK\MinterPrefix;

/**
 * Class MinterUnbondTx
 * @package Minter\SDK\MinterCoins
 */
class MinterUnbondTx extends MinterCoinTx implements MinterTxInterface
{
    /**
     * Type
     */
    const TYPE = 8;

    /**
     * Fee units
     */
    const COMMISSION = 200;

    /**
     * Unbond tx data
     *
     * @var array
     */
    public $data = [
        'pubkey' => '',
        'coin' => '',
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
            // Remove Minter wallet prefix and convert hex string to binary
            'pubkey' => hex2bin(
                Helper::removePrefix($this->data['pubkey'], MinterPrefix::PUBLIC_KEY)
            ),

            // Add nulls before coin name
            'coin' => $this->data['coin'],

            // Convert from BIP to PIP
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
            // Add Minter wallet prefix to string
            'pubkey' => MinterPrefix::PUBLIC_KEY . $txData[0],

            // Pack binary to string
            'coin' => hexdec($txData[1]),

            // Convert value from PIP to BIP
            'value' => MinterConverter::convertToBase(Helper::hexDecode($txData[2]))
        ];
    }
}