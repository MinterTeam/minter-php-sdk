<?php

namespace Minter\SDK;

use Minter\Interfaces\MinterTxInterface;

class MinterCreateCoinTx extends MinterCoinTx implements MinterTxInterface
{
    /**
     * Type
     */
    const TYPE = 3;

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
            'name' => pack('H*', $txData[0]),
            'symbol' => str_replace(chr(0), '', pack('H*', $txData[1])),
            'initialAmount' => MinterConverter::convertValue(hexdec($txData[2]), 'bip'),
            'initialReserve' => MinterConverter::convertValue(hexdec($txData[3]), 'bip'),
            'crr' => hexdec($txData[4])
        ];
    }
}