<?php

namespace Minter\SDK;

use Minter\Interfaces\MinterTxInterface;
use Web3p\RLP\Buffer;

class MinterCreateCoinTx extends MinterCoinTx implements MinterTxInterface
{
    /**
     * Send coin tx data
     *
     * @var array
     */
    protected $data = [
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
     * RLP encoded tx data
     *
     * @return \Web3p\RLP\Buffer
     */
    public function serialize(): Buffer
    {
        return $this->rlp->encode([
            'name' => $this->data['name'],
            'symbol' => MinterConverter::convertCoinName($this->data['symbol']),
            'initialAmount' => $this->data['initialAmount'],
            'initialReserve' => $this->data['initialReserve'],
            'crr' => $this->data['crr']
        ]);
    }

    /**
     * Prepare output tx data
     *
     * @param array $txData
     * @return array
     */
    public function convertFromHex(array $txData): array
    {
        return [
            'name' => pack('H*', $txData[0]),
            'symbol' => str_replace(chr(0), '', pack('H*', $txData[1])),
            'initialAmount' => hexdec($txData[2]),
            'initialReserve' => hexdec($txData[3]),
            'crr' => hexdec($txData[4])
        ];
    }
}