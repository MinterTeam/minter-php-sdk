<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;

/**
 * Class MinterSetCandidateOnTx
 * @package Minter\SDK\MinterCoins
 */
class MinterSetCandidateOnTx extends MinterCoinTx implements MinterTxInterface
{
    /**
     * Type
     */
    const TYPE = 8;

    /**
     * Fee in PIP
     */
    const COMMISSION = 1000;

    /**
     * Set candidate on tx data
     *
     * @var array
     */
    public $data = [
        'pubkey' => ''
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
        ];
    }
}