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
        'coinToSell'        => '',
        'valueToSell'       => '',
        'coinToBuy'         => '',
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
            'coinToSell'        => $this->data['coinToSell'],
            'valueToSell'       => MinterConverter::convertToPip($this->data['valueToSell']),
            'coinToBuy'         => $this->data['coinToBuy'],
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
            'coinToSell'        => hexdec($txData[0]),
            'valueToSell'       => MinterConverter::convertToBase(Helper::hexDecode($txData[1])),
            'coinToBuy'         => hexdec($txData[2]),
            'minimumValueToBuy' => MinterConverter::convertToBase(Helper::hexDecode($txData[3]))
        ];
    }
}