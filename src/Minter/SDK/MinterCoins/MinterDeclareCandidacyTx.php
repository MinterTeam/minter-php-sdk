<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterConverter;

/**
 * Class MinterDeclareCandidacyTx
 * @package Minter\SDK\MinterCoins
 */
class MinterDeclareCandidacyTx extends MinterCoinTx implements MinterTxInterface
{
    /**
     * Type
     */
    const TYPE = 4;

    /**
     * Fee in PIP
     */
    const COMMISSION = 100000;

    /**
     * Declare candidacy tx data
     *
     * @var array
     */
    public $data = [
        'address' => '',
        'pubkey' => '',
        'commission' => '',
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
                Helper::removeWalletPrefix($this->data['pubkey'])
            ),

            // Define commission field
            'commission' => $this->data['commission'],

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
            'pubkey' => Helper::addWalletPrefix($txData[1]),

            // Decode hex string to number
            'commission' => Helper::hexDecode($txData[2]),

            // Convert stake from PIP to BIP
            'stake' => MinterConverter::convertValue(Helper::hexDecode($txData[3]), 'bip')
        ];
    }
}