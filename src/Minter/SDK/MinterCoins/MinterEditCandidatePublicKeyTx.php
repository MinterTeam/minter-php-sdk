<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterPrefix;

/**
 * Class MinterEditCandidatePublicKeyTx
 * @package Minter\SDK\MinterCoins
 */
class MinterEditCandidatePublicKeyTx extends MinterCoinTx implements MinterTxInterface
{
    public $publicKey;
    public $newPublicKey;

    const TYPE       = 20;
    const COMMISSION = 100000000;

    /**
     * MinterEditCandidatePublicKeyTx constructor.
     * @param $publicKey
     * @param $newPublicKey
     */
    public function __construct($publicKey, $newPublicKey)
    {
        $this->publicKey    = $publicKey;
        $this->newPublicKey = $newPublicKey;
    }

    /**
     * Prepare data for signing
     *
     * @return array
     */
    public function encodeData(): array
    {
        return [
            hex2bin(Helper::removePrefix($this->publicKey, MinterPrefix::PUBLIC_KEY)),
            hex2bin(Helper::removePrefix($this->newPublicKey, MinterPrefix::PUBLIC_KEY)),
        ];
    }

    public function decodeData()
    {
        $this->publicKey    = MinterPrefix::PUBLIC_KEY . $this->publicKey;
        $this->newPublicKey = MinterPrefix::PUBLIC_KEY . $this->newPublicKey;
    }
}