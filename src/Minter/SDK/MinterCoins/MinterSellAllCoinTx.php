<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterConverter;

/**
 * Class MinterSellAllCoinTx
 * @package Minter\SDK\MinterCoins
 */
class MinterSellAllCoinTx extends MinterCoinTx implements MinterTxInterface
{
    /**
     * Type
     */
    const TYPE = 3;

    /**
     * Fee units
     */
    const COMMISSION = 100;

    /**
     * Send coin tx data
     *
     * @var array
     */
    public $data = [
        'coinToSell' => '',
        'coinToBuy' => '',
        'minimumValueToBuy' => ''
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

            // Add nulls before symbol
            'coinToBuy' => MinterConverter::convertCoinName($this->data['coinToBuy']),

            // Convert field from BIP to PIP
            'minimumValueToBuy' => MinterConverter::convertValue($this->data['minimumValueToBuy'], 'pip')
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

            // Pack symbol
            'coinToBuy' => Helper::pack2hex($txData[1]),

            // Convert field from PIP to BIP
            'minimumValueToBuy' => MinterConverter::convertValue(Helper::hexDecode($txData[2]), 'bip')
        ];
    }
}