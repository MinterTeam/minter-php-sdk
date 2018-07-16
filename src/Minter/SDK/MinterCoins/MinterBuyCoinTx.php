<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterConverter;

/**
 * Class MinterBuyCoinTx
 * @package Minter\SDK\MinterCoins
 */
class MinterBuyCoinTx extends MinterCoinTx implements MinterTxInterface
{
    /**
     * Type
     */
    const TYPE = 3;

    /**
     * Fee in PIP
     */
    const COMMISSION = '100000000000000000';

    /**
     * Send coin tx data
     *
     * @var array
     */
    public $data = [
        'coinToBuy' => '',
        'valueToBuy' => '',
        'coinToSell' => '',
    ];

    /**
     * Prepare tx data for signing
     *
     * @return array
     */
    public function encode(): array
    {
        return [
            // Add nulls before symbol
            'coinToBuy' => MinterConverter::convertCoinName($this->data['coinToBuy']),

            // Convert field from BIP to PIP
            'valueToBuy' => MinterConverter::convertValue($this->data['valueToBuy'], 'pip'),

            // Add nulls before symbol
            'coinToSell' => MinterConverter::convertCoinName($this->data['coinToSell'])
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
            // Pack symbol
            'coinToBuy' => Helper::pack2hex($txData[0]),

            // Convert field from PIP to BIP
            'valueToBuy' => MinterConverter::convertValue(Helper::hexDecode($txData[1]), 'bip'),

            // Pack symbol
            'coinToSell' => Helper::pack2hex($txData[2]),
        ];
    }
}