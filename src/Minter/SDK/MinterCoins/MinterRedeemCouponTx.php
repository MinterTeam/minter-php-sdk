<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;

/**
 * Class MinterRedeemCouponTx
 * @package Minter\SDK\MinterCoins
 */
class MinterRedeemCouponTx extends MinterCoinTx implements MinterTxInterface
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
     * Minter Redeem Coupon tx data
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
                Helper::removeWalletPrefix($this->data['check'])
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
            'check' => Helper::addWalletPrefix($txData[0]),

            // Define proof field
            'proof' => $txData[1],
        ];
    }
}