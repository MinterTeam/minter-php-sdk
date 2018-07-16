<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterConverter;
use Minter\SDK\MinterPrefix;

/**
 * Class MinterDeclareCandidacyTx
 * @package Minter\SDK\MinterCoins
 */
class MinterDeclareCandidacyTx extends MinterCoinTx implements MinterTxInterface
{
    /**
     * Type
     */
    const TYPE = 5;

    /**
     * Fee in PIP
     */
    const COMMISSION = '1000000000000000000';

    /**
     * Declare candidacy tx data
     *
     * @var array
     */
    public $data = [
        'address' => '',
        'pubkey' => '',
        'commission' => '',
        'coin' => '',
        'stake' => ''
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
            'address' => hex2bin(
                Helper::removeWalletPrefix($this->data['address'])
            ),

            // Remove Minter wallet prefix and convert hex string to binary
            'pubkey' => hex2bin(
                Helper::removePrefix($this->data['pubkey'], MinterPrefix::PUBLIC_KEY)
            ),

            // Define commission field
            'commission' => $this->data['commission'],

            // Convert coin name
            'coin' => MinterConverter::convertCoinName($this->data['coin']),

            // Convert stake field from BIP to PIP
            'stake' => MinterConverter::convertValue($this->data['stake'], 'pip')
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
            'address' => Helper::addWalletPrefix($txData[0]),

            // Add Minter wallet prefix to string
            'pubkey' => MinterPrefix::PUBLIC_KEY . $txData[1],

            // Decode hex string to number
            'commission' => Helper::hexDecode($txData[2]),

            // Pack coin name
            'coin' => Helper::pack2hex($txData[3]),

            // Convert stake from PIP to BIP
            'stake' => MinterConverter::convertValue(Helper::hexDecode($txData[4]), 'bip')
        ];
    }
}