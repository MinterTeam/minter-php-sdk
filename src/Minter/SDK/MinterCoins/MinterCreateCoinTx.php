<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterConverter;

class MinterCreateCoinTx extends MinterCoinTx implements MinterTxInterface
{
    /**
     * Type
     */
    const TYPE = 3;

    /**
     * Fee in PIP
     */
    const COMMISSION = 100000;

    /**
     * Send coin tx data
     *
     * @var array
     */
    public $data = [
        'name' => '',
        'symbol' => '',
        'initialAmount' => '',
        'initialReserve' => '',
        'crr' => ''
    ];

    /**
     * MinterCreateCoinTx constructor.
     * @param $data
     * @param bool $convert
     * @throws \Exception
     */
    public function __construct($data, $convert = false)
    {
        parent::__construct($data, $convert);
    }

    /**
     * Prepare tx data for signing
     *
     * @return array
     */
    public function encode(): array
    {
        return [
            'name' => $this->data['name'],
            'symbol' => MinterConverter::convertCoinName($this->data['symbol']),
            'initialAmount' => MinterConverter::convertValue($this->data['initialAmount'], 'pip'),
            'initialReserve' => MinterConverter::convertValue($this->data['initialReserve'], 'pip'),
            'crr' => $this->data['crr']
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
            'name' => Helper::pack2hex($txData[0]),
            'symbol' => Helper::pack2hex($txData[1]),
            'initialAmount' => MinterConverter::convertValue(
                Helper::hexDecode($txData[2]),
                'bip'
            ),
            'initialReserve' => MinterConverter::convertValue(
                Helper::hexDecode($txData[3])
                , 'bip'
            ),
            'crr' => hexdec($txData[4])
        ];
    }
}