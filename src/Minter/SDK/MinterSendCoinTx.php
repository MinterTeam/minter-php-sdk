<?php

namespace Minter\SDK;

use Minter\Interfaces\MinterTxInterface;

class MinterSendCoinTx extends MinterCoinTx implements MinterTxInterface
{
    /**
     * Type
     */
    const TYPE = 1;

    /**
     * Fee in PIP
     */
    const COMMISSION = 1000;

    /**
     * Send coin tx data
     *
     * @var array
     */
    public $data = [
        'coin' => '',
        'to' => '',
        'value' => ''
    ];

    /**
     * MinterSendCoinTx constructor.
     * @param $data
     * @param bool $convert
     * @throws \Exception
     */
    public function __construct($data, $convert = false)
    {
        parent::__construct($data, $convert);
    }

    /**
     * Prepare data for signing
     *
     * @return array
     */
    public function encode(): array
    {
        return [
            'coin' => MinterConverter::convertCoinName($this->data['coin']),
            'to' => hex2bin(substr($this->data['to'], 2, strlen($this->data['to']))),
            'value' => MinterConverter::convertValue($this->data['value'], 'pip')
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
            'coin' => str_replace(chr(0), '', pack('H*', $txData[0])),
            'to' => 'Mx' . $txData[1],
            'value' => MinterConverter::convertValue(hexdec($txData[2]), 'bip')
        ];
    }
}