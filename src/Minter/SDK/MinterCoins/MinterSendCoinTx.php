<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterConverter;
use Minter\SDK\MinterWallet;

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
            'to' => $this->prepareAddress(),
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
            'coin' => Helper::pack2hex($txData[0]),
            'to' => Helper::addWalletPrefix($txData[1]),
            'value' => MinterConverter::convertValue(
                Helper::hexDecode($txData[2]),
                'bip'
            )
        ];
    }

    /**
     *  Remove MinterWallet prefix and pack hex address to binary
     *
     * @return bool|string
     */
    protected function prepareAddress()
    {
        return hex2bin(
            Helper::removeWalletPrefix($this->data['to'])
        );
    }
}