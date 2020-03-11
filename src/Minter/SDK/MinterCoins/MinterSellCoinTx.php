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
        'valueToSell' => '',
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

            // Convert field from BIP to PIP
            'valueToSell' => MinterConverter::convertToPip($this->data['valueToSell']),

            // Add nulls before symbol
            'coinToBuy' => MinterConverter::convertCoinName($this->data['coinToBuy']),

            // Convert field from BIP to PIP
            'minimumValueToBuy' => MinterConverter::convertToPip($this->data['minimumValueToBuy'])
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
            'coinToSell' => Helper::hex2str($txData[0]),

            // Convert field from PIP to BIP
            'valueToSell' => MinterConverter::convertToBase(Helper::hexDecode($txData[1])),

            // Pack symbol
            'coinToBuy' => Helper::hex2str($txData[2]),

            // Convert field from PIP to BIP
            'minimumValueToBuy' => MinterConverter::convertToBase(Helper::hexDecode($txData[3]))
        ];
    }
}