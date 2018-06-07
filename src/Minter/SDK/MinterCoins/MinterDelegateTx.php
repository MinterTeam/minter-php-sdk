<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterConverter;

/**
 * Class MinterDelegateTx
 * @package Minter\SDK\MinterCoins
 */
class MinterDelegateTx extends MinterCoinTx implements MinterTxInterface
{
    /**
     * Type
     */
    const TYPE = 5;

    /**
     * Fee in PIP
     */
    const COMMISSION = 10000;

    /**
     * Delegate tx data
     *
     * @var array
     */
    public $data = [
        'pubkey' => '',
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
            'pubkey' => hex2bin(
                Helper::removeWalletPrefix($this->data['pubkey'])
            ),

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
            'pubkey' => Helper::addWalletPrefix($txData[0]),

            // Convert stake from PIP to BIP
            'stake' => MinterConverter::convertValue(Helper::hexDecode($txData[1]), 'bip')
        ];
    }
}