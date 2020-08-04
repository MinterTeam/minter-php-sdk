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
    const TYPE = 6;

    /**
     * Fee units
     */
    const COMMISSION = 10000;

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
            'commission' => $this->data['commission'] === 0 ? '' : $this->data['commission'],

            'coin' => $this->data['coin'],

            // Convert stake field from BIP to PIP
            'stake' => MinterConverter::convertToPip($this->data['stake'])
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

            'coin' => hexdec($txData[3]),

            // Convert stake from PIP to BIP
            'stake' => MinterConverter::convertToBase(Helper::hexDecode($txData[4]))
        ];
    }
}