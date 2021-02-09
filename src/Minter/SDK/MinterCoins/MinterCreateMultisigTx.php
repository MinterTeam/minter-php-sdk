<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;

/**
 * Class MinterCreateMultisigTx
 * @package Minter\SDK\MinterCoins
 */
class MinterCreateMultisigTx extends MinterCoinTx implements MinterTxInterface
{
    public $threshold;
    public $weights;
    public $addresses;

    const TYPE = 12;

    /**
     * MinterCreateMultisigTx constructor.
     * @param $threshold
     * @param $weights
     * @param $addresses
     */
    public function __construct($threshold, $weights, $addresses)
    {
        $this->threshold = $threshold;
        $this->weights   = $weights;
        $this->addresses = $addresses;
    }

    /**
     * Prepare data for signing
     *
     * @return array
     */
    public function encodeData(): array
    {
        $addresses = [];
        foreach ($this->addresses as $address) {
            $address     = Helper::removeWalletPrefix($address);
            $addresses[] = hex2bin($address);
        }

        $weights = [];
        foreach ($this->weights as $weight) {
            $weights[] = $weight === 0 ? '' : $weight;
        }

        $threshold = $this->threshold === 0 ? '' : $this->threshold;

        return [
            $threshold,
            $weights,
            $addresses,
        ];
    }

    public function decodeData()
    {
        $threshold = (int)Helper::hexDecode($this->threshold);

        $weights = [];
        foreach ($this->weights as $weight) {
            $weights[] = hexdec($weight);
        }

        $addresses = [];
        foreach ($this->addresses as $address) {
            $addresses[] = Helper::addWalletPrefix($address);
        }

        $this->threshold = $threshold;
        $this->weights   = $weights;
        $this->addresses = $addresses;
    }
}