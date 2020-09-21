<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterConverter;
use Minter\SDK\MinterWallet;

/**
 * Class MinterEditCoinOwnerTx
 * @package Minter\SDK\MinterCoins
 */
class MinterEditCoinOwnerTx extends MinterCoinTx implements MinterTxInterface
{
    const TYPE       = 17;
    const COMMISSION = 10000000;

    public $data = [
        'symbol'   => '',
        'newOwner' => ''
    ];

    /**
     * Prepare tx data for signing
     *
     * @return array
     */
    public function encode(): array
    {
        return [
            'symbol'   => MinterConverter::convertCoinName($this->data['symbol']),
            'newOwner' => hex2bin(Helper::removeWalletPrefix($this->data['newOwner'])),
        ];
    }

    public function decode(array $txData): array
    {
        return [
            'symbol'   => Helper::hex2str($txData[0]),
            'newOwner' => Helper::addWalletPrefix($txData[1])
        ];
    }
}