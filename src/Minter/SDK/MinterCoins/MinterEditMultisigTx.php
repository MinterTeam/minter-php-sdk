<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;

/**
 * Class MinterEditMultisigTx
 * @package Minter\SDK\MinterCoins
 */
class MinterEditMultisigTx extends MinterCoinTx implements MinterTxInterface
{
    public $weights;
    public $addresses;
    public $threshold;

    const TYPE       = 18;
    const COMMISSION = 1000;

    /**
     * MinterEditMultisigTx constructor.
     * @param       $threshold
     * @param array $weights
     * @param array $addresses
     */
    public function __construct($threshold, $weights, $addresses)
    {
        $this->threshold = $threshold;
        $this->weights   = $weights;
        $this->addresses = $addresses;
    }

    /**
     * Prepare tx data for signing
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
            $addresses
        ];
    }

    public function decodeData()
    {
        $threshold = hexdec($this->threshold);

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