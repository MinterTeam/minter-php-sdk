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
        'coinToBuy'          => '',
        'valueToBuy'         => '',
        'coinToSell'         => '',
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
            'coinToBuy'          => $this->data['coinToBuy'],
            'valueToBuy'         => MinterConverter::convertToPip($this->data['valueToBuy']),
            'coinToSell'         => $this->data['coinToSell'],
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
            'coinToBuy'          => Helper::hexDecode($txData[0]),
            'valueToBuy'         => MinterConverter::convertToBase(Helper::hexDecode($txData[1])),
            'coinToSell'         => Helper::hexDecode($txData[2]),
            'maximumValueToSell' => MinterConverter::convertToBase(Helper::hexDecode($txData[3]))
        ];
    }
}