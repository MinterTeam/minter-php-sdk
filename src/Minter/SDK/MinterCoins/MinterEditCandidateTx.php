<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterPrefix;

/**
 * Class MinterEditCandidateTx
 * @package Minter\SDK\MinterCoins
 */
class MinterEditCandidateTx extends MinterCoinTx implements MinterTxInterface
{
    public $publicKey;
    public $rewardAddress;
    public $ownerAddress;
    public $controlAddress;

    const TYPE       = 14;
    const COMMISSION = 10000;

    /**
     * MinterEditCandidateTx constructor.
     * @param $publicKey
     * @param $rewardAddress
     * @param $ownerAddress
     * @param $controlAddress
     */
    public function __construct($publicKey, $rewardAddress, $ownerAddress, $controlAddress)
    {
        $this->publicKey      = $publicKey;
        $this->rewardAddress  = $rewardAddress;
        $this->ownerAddress   = $ownerAddress;
        $this->controlAddress = $controlAddress;
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
            hex2bin(Helper::removeWalletPrefix($this->rewardAddress)),
            hex2bin(Helper::removeWalletPrefix($this->ownerAddress)),
            hex2bin(Helper::removeWalletPrefix($this->controlAddress))
        ];
    }

    public function decodeData()
    {
        $this->publicKey      = MinterPrefix::PUBLIC_KEY . $this->publicKey;
        $this->rewardAddress  = Helper::addWalletPrefix($this->rewardAddress);
        $this->ownerAddress   = Helper::addWalletPrefix($this->ownerAddress);
        $this->controlAddress = Helper::addWalletPrefix($this->controlAddress);
    }
}