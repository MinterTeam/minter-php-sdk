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
        'coinToSell'        => '',
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
            'coinToBuy'         => hexdec($txData[1]),
            'minimumValueToBuy' => MinterConverter::convertToBase(Helper::hexDecode($txData[2]))
        ];
    }
}