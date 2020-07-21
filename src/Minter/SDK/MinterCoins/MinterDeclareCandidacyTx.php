<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterConverter;
use Minter\SDK\MinterPrefix;

/**
 * Class MinterDeclareCandidacyTx
 * @package Minter\SDK\MinterCoins
 */
class MinterDeclareCandidacyTx extends MinterCoinTx implements MinterTxInterface
{
    const TYPE       = 6;
    const COMMISSION = 10000;

    public $address;
    public $publicKey;
    public $commission;
    public $coin;
    public $stake;

    /**
     * MinterDeclareCandidacyTx constructor.
     * @param $address
     * @param $publicKey
     * @param $commission
     * @param $coin
     * @param $stake
     */
    public function __construct($address, $publicKey, $commission, $coin, $stake)
    {
        $this->address    = $address;
        $this->publicKey  = $publicKey;
        $this->commission = $commission;
        $this->coin       = $coin;
        $this->stake      = $stake;
    }

    /**
     * Prepare data for signing
     *
     * @return array
     */
    public function encodeData(): array
    {
        return [
            hex2bin(Helper::removeWalletPrefix($this->address)),
            hex2bin(Helper::removePrefix($this->publicKey, MinterPrefix::PUBLIC_KEY)),
            $this->commission === 0 ? '' : $this->commission,
            $this->coin,
            MinterConverter::convertToPip($this->stake)
        ];
    }

    public function decodeData()
    {
        $this->address    = Helper::addWalletPrefix($this->address);
        $this->publicKey  = MinterPrefix::PUBLIC_KEY . $this->publicKey;
        $this->commission = Helper::hexDecode($this->commission);
        $this->coin       = hexdec($this->coin);
        $this->stake      = MinterConverter::convertToBase(Helper::hexDecode($this->stake));
    }
}