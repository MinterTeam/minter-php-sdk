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
    const TYPE = 5;

    /**
     * Fee units
     */
    const COMMISSION = 1000;

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
        'crr' => '',
        'maxSupply' => ''
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
            'initialAmount' => MinterConverter::convertToPip($this->data['initialAmount']),

            // Convert field from BIP to PIP
            'initialReserve' => MinterConverter::convertToPip($this->data['initialReserve']),

            // Define crr field
            'crr' => $this->data['crr'] === 0 ? '' : $this->data['crr'],

            // Convert field from BIP to PIP
            'maxSupply' => MinterConverter::convertToPip($this->data['maxSupply'])
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
            'name' => Helper::hex2str($txData[0]),

            // Pack symbol
            'symbol' => Helper::hex2str($txData[1]),

            // Convert field from PIP to BIP
            'initialAmount' => MinterConverter::convertToBase(Helper::hexDecode($txData[2])),

            // Convert field from PIP to BIP
            'initialReserve' => MinterConverter::convertToBase(Helper::hexDecode($txData[3])),

            // Convert crr field from hex string to number
            'crr' => hexdec($txData[4]),

            // Convert field from BIP to PIP
            'maxSupply' => MinterConverter::convertToBase(Helper::hexDecode($txData[5]))
        ];
    }
}