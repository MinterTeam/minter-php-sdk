<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterPrefix;

/**
 * Class MinterSetCandidateOffTx
 * @package Minter\SDK\MinterCoins
 */
class MinterSetCandidateOffTx extends MinterCoinTx implements MinterTxInterface
{
    /**
     * Type
     */
    const TYPE = 9;

    /**
     * Fee in PIP
     */
    const COMMISSION = '100000000000000000';

    /**
     * Set candidate off tx data
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
                Helper::removePrefix($this->data['pubkey'], MinterPrefix::PUBLIC_KEY)
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
            'pubkey' => MinterPrefix::PUBLIC_KEY . $txData[0],
        ];
    }
}