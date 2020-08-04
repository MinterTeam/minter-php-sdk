<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterConverter;
use Minter\SDK\MinterPrefix;

/**
 * Class MinterDelegateTx
 * @package Minter\SDK\MinterCoins
 */
class MinterDelegateTx extends MinterCoinTx implements MinterTxInterface
{
    /**
     * Type
     */
    const TYPE = 7;

    /**
     * Fee units
     */
    const COMMISSION = 200;

    /**
     * Delegate tx data
     *
     * @var array
     */
    public $data = [
        'pubkey' => '',
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
            'pubkey' => hex2bin(
                Helper::removePrefix($this->data['pubkey'], MinterPrefix::PUBLIC_KEY)
            ),

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
            'pubkey' => MinterPrefix::PUBLIC_KEY . $txData[0],

            'coin' => hexdec($txData[1]),

            // Convert stake from PIP to BIP
            'stake' => MinterConverter::convertToBase(Helper::hexDecode($txData[2]))
        ];
    }
}