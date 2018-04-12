<?php

namespace Minter\SDK;

use Minter\Interfaces\MinterTxInterface;
use Web3p\RLP\Buffer;

class MinterConvertCoinTx extends MinterCoinTx implements MinterTxInterface
{
    /**
     * Send coin tx data
     *
     * @var array
     */
    protected $data = [
        'coin_from' => '',
        'coin_to' => '',
        'value' => ''
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
            'coin_from' => MinterConverter::convertCoinName($this->data['coin_from']),
            'coin_to' => MinterConverter::convertCoinName($this->data['coin_to']),
            'value' => MinterConverter::convertValue($this->data['value'], 'pip')
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
            'coin_from' => str_replace(chr(0), '', pack('H*', $txData[0])),
            'coin_to' => str_replace(chr(0), '', pack('H*', $txData[1])),
            'value' => hexdec($txData[2])
        ];
    }
}