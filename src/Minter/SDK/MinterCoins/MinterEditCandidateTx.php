<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterPrefix;

/**
 * Class MinterEditCandidateTx
 * @package Minter\SDK\MinterCoins
 */
class MinterEditCandidateTx extends MinterCoinTx implements MinterTxInterface
{
    /**
     * Type
     */
    const TYPE = 14;

    /**
     * Fee units
     */
    const COMMISSION = 10000;

    /**
     * Edit candidate tx data
     *
     * @var array
     */
    public $data = [
        'pubkey' => '',
        'new_pubkey' => '',
        'reward_address' => '',
        'owner_address' => '',
        'control_address' => ''
    ];

    /**
     * Prepare data for signing
     *
     * @return array
     */
    public function encode(): array
    {
        return [
            // Remove Minter public key prefix and convert hex string to binary
            'pubkey' => hex2bin(
                Helper::removePrefix($this->data['pubkey'], MinterPrefix::PUBLIC_KEY)
            ),

            // Remove Minter public key prefix and convert hex string to binary
            'new_pubkey' => hex2bin(
                Helper::removePrefix($this->data['new_pubkey'], MinterPrefix::PUBLIC_KEY)
            ),

            // Remove Minter wallet prefix and convert hex string to binary
            'reward_address' => hex2bin(
                Helper::removeWalletPrefix($this->data['reward_address'])
            ),

            // Remove Minter wallet prefix and convert hex string to binary
            'owner_address' => hex2bin(
                Helper::removeWalletPrefix($this->data['owner_address'])
            ),

            // Remove Minter wallet prefix and convert hex string to binary
            'control_address' => hex2bin(
                Helper::removeWalletPrefix($this->data['control_address'])
            )
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

            // Add Minter wallet prefix to string
            'new_pubkey' => MinterPrefix::PUBLIC_KEY . $txData[1],

            // Add Minter wallet prefix to string
            'reward_address' => Helper::addWalletPrefix($txData[2]),

            // Add Minter wallet prefix to string
            'owner_address' => Helper::addWalletPrefix($txData[3]),

            // Add Minter wallet prefix to string
            'control_address' => Helper::addWalletPrefix($txData[4])
        ];
    }
}