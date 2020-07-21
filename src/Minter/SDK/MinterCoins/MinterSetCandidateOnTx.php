<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterPrefix;

/**
 * Class MinterSetCandidateOnTx
 * @package Minter\SDK\MinterCoins
 */
class MinterSetCandidateOnTx extends MinterCoinTx implements MinterTxInterface
{
    public $publicKey;

    const TYPE       = 10;
    const COMMISSION = 100;

    /**
     * MinterSetCandidateOnTx constructor.
     * @param $publicKey
     */
    public function __construct($publicKey)
    {
        $this->publicKey = $publicKey;
    }

    /**
     * Prepare data for signing
     *
     * @return array
     */
    public function encodeData(): array
    {
        return [
            hex2bin(
                Helper::removePrefix($this->publicKey, MinterPrefix::PUBLIC_KEY)
            ),
        ];
    }

    public function decodeData()
    {
        $this->publicKey = MinterPrefix::PUBLIC_KEY . $this->publicKey;
    }
}