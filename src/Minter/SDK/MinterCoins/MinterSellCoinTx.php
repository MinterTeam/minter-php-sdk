<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterConverter;

/**
 * Class MinterSellCoinTx
 * @package Minter\SDK\MinterCoins
 */
class MinterSellCoinTx extends MinterCoinTx implements MinterTxInterface
{
    /**
     * Type
     */
    const TYPE = 2;

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
        'coinToSell' => '',
        'valueToSell' => '',
        'coinToBuy' => '',
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
            'coinToSell' => MinterConverter::convertCoinName($this->data['coinToSell']),

            // Convert field from BIP to PIP
            'valueToSell' => MinterConverter::convertValue($this->data['valueToSell'], 'pip'),

            // Add nulls before symbol
            'coinToBuy' => MinterConverter::convertCoinName($this->data['coinToBuy'])
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
            'coinToSell' => Helper::pack2hex($txData[0]),

            // Convert field from PIP to BIP
            'valueToSell' => MinterConverter::convertValue(Helper::hexDecode($txData[1]), 'bip'),

            // Pack symbol
            'coinToBuy' => Helper::pack2hex($txData[2]),
        ];
    }
}