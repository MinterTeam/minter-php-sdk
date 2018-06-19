<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterPrefix;

/**
 * Class MinterRedeemCheckTx
 * @package Minter\SDK\MinterCoins
 */
class MinterRedeemCheckTx extends MinterCoinTx implements MinterTxInterface
{
    /**
     * Type
     */
    const TYPE = 7;

    /**
     * Fee in PIP
     */
    const COMMISSION = 1000;

    /**
     * Minter Redeem check tx data
     *
     * @var array
     */
    public $data = [
        'check' => '',
        'proof' => ''
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
            'check' => hex2bin(
                Helper::removePrefix($this->data['check'], MinterPrefix::CHECK)
            ),

            // Convert hex string to binary
            'proof' => hex2bin($this->data['proof'])
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
            // Add Minter wallet prefix to hex string
            'check' => MinterPrefix::CHECK . $txData[0],

            // Define proof field
            'proof' => $txData[1],
        ];
    }
}