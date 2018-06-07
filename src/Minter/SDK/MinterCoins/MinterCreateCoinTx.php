<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterConverter;

/**
 * Class MinterCreateCoinTx
 * @package Minter\SDK\MinterCoins
 */
class MinterCreateCoinTx extends MinterCoinTx implements MinterTxInterface
{
    /**
     * Type
     */
    const TYPE = 3;

    /**
     * Fee in PIP
     */
    const COMMISSION = 100000;

    /**
     * Send coin tx data
     *
     * @var array
     */
    public $data = [
        'name' => '',
        'symbol' => '',
        'initialAmount' => '',
        'initialReserve' => '',
        'crr' => ''
    ];

    /**
     * Prepare tx data for signing
     *
     * @return array
     */
    public function encode(): array
    {
        return [
            // Define name field
            'name' => $this->data['name'],

            // Add nulls before symbol
            'symbol' => MinterConverter::convertCoinName($this->data['symbol']),

            // Convert field from BIP to PIP
            'initialAmount' => MinterConverter::convertValue($this->data['initialAmount'], 'pip'),

            // Convert field from BIP to PIP
            'initialReserve' => MinterConverter::convertValue($this->data['initialReserve'], 'pip'),

            // Define crr field
            'crr' => $this->data['crr']
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
            // Pack name
            'name' => Helper::pack2hex($txData[0]),

            // Pack symbol
            'symbol' => Helper::pack2hex($txData[1]),

            // Convert field from PIP to BIP
            'initialAmount' => MinterConverter::convertValue(Helper::hexDecode($txData[2]), 'bip'),

            // Convert field from PIP to BIP
            'initialReserve' => MinterConverter::convertValue(Helper::hexDecode($txData[3]), 'bip'),

            // Convert crr field from hex string to number
            'crr' => hexdec($txData[4])
        ];
    }
}