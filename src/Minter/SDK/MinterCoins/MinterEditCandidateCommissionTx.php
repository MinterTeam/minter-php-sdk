<?php


namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterPrefix;

/**
 * Class MinterEditCandidateCommissionTx
 * @package Minter\SDK\MinterCoins
 */
class MinterEditCandidateCommissionTx extends MinterCoinTx implements MinterTxInterface
{
    public $publicKey;
    public $commission;

    const TYPE = 26;

    /**
     * MinterEditCandidateCommissionTx constructor.
     * @param $publicKey
     * @param $commission
     */
    public function __construct($publicKey, $commission)
    {
        $this->publicKey  = $publicKey;
        $this->commission = $commission;
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
            $this->commission === 0 ? '' : $this->commission
        ];
    }

    public function decodeData()
    {
        $this->publicKey  = MinterPrefix::PUBLIC_KEY . $this->publicKey;
        $this->commission = (int) Helper::hexDecode($this->commission);
    }
}