<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterConverter;
use Minter\SDK\MinterPrefix;

/**
 * Class MinterEditCandidatePublicKeyTx
 * @package Minter\SDK\MinterCoins
 */
class MinterEditCandidatePublicKeyTx extends MinterCoinTx implements MinterTxInterface
{
    const TYPE       = 20;
    const COMMISSION = 100000000;

    /**
     * Send coin tx data
     *
     * @var array
     */
    public $data = [
        'publicKey' => '',
    ];

    /**
     * Prepare data for signing
     *
     * @return array
     */
    public function encode(): array
    {
        return [
            'publicKey' => hex2bin(Helper::removePrefix($this->data['publicKey'], MinterPrefix::PUBLIC_KEY)),
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
            'publicKey' => MinterPrefix::PUBLIC_KEY . $txData[0]
        ];
    }

}