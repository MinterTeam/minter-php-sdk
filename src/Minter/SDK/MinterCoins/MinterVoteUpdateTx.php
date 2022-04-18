<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterPrefix;

/**
 * Class MinterVoteUpdateTx
 * @package Minter\SDK\MinterCoins
 */
class MinterVoteUpdateTx extends MinterCoinTx implements MinterTxInterface
{
    public $publicKey;
    public $version;
    public $height;

    const TYPE = 33;

    /**
     * @param $version
     * @param $publicKey
     * @param $height
     */
    public function __construct($version, $publicKey, $height)
    {
        $this->publicKey = $publicKey;
        $this->version   = $version;
        $this->height    = $height;
    }

    /**
     * Prepare data for signing
     *
     * @return array
     */
    public function encodeData(): array
    {
        return [
            $this->version,
            hex2bin(Helper::removePrefix($this->publicKey, MinterPrefix::PUBLIC_KEY)),
            $this->height
        ];
    }

    public function decodeData()
    {
        $this->publicKey = MinterPrefix::PUBLIC_KEY . $this->publicKey;
        $this->height    = hexdec($this->height);
        $this->version   = Helper::hex2str($this->version);
    }
}