<?php

namespace Minter\SDK;

use Minter\Interfaces\MinterTxInterface;
use Web3p\RLP\Buffer;
use Web3p\RLP\RLP;

class MinterSendCoinTx extends MinterCoinTx implements MinterTxInterface
{
    /**
     * Send coin tx data
     *
     * @var array
     */
    protected $data = [
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
     * RLP encoded tx data
     *
     * @return \Web3p\RLP\Buffer
     */
    public function serialize(): Buffer
    {
        return $this->rlp->encode([
            'coin' => MinterConverter::convertCoinName($this->data['coin']),
            'to' => hex2bin(substr($this->data['to'], 2, strlen($this->data['to']))),
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
            'coin' => str_replace(chr(0), '', pack('H*', $txData[0])),
            'to' => 'Mx' . $txData[1],
            'value' => hexdec($txData[2])
        ];
    }
}