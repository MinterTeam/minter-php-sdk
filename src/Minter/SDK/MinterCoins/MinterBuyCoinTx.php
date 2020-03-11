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
    const TYPE = 4;

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
        'coinToBuy' => '',
        'valueToBuy' => '',
        'coinToSell' => '',
        'maximumValueToSell' => ''
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
            'valueToBuy' => MinterConverter::convertToPip($this->data['valueToBuy']),

            // Add nulls before symbol
            'coinToSell' => MinterConverter::convertCoinName($this->data['coinToSell']),

            // Convert field from BIP to PIP
            'maximumValueToSell' => MinterConverter::convertToPip($this->data['maximumValueToSell'])
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
            'coinToBuy' => Helper::hex2str($txData[0]),

            // Convert field from PIP to BIP
            'valueToBuy' => MinterConverter::convertToBase(Helper::hexDecode($txData[1])),

            // Pack symbol
            'coinToSell' => Helper::hex2str($txData[2]),

            // Convert field from PIP to BIP
            'maximumValueToSell' => MinterConverter::convertToBase(Helper::hexDecode($txData[3]))
        ];
    }
}