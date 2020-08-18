<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;

/**
 * Class MinterEditMultisigOwnersTx
 * @package Minter\SDK\MinterCoins
 */
class MinterEditMultisigOwnersTx extends MinterCoinTx implements MinterTxInterface
{
    public $multisigAddress;
    public $weights;
    public $addresses;

    const TYPE       = 18;
    const COMMISSION = 1000;

    /**
     * MinterEditMultisigOwnersTx constructor.
     * @param string $multisigAddress
     * @param array  $weights
     * @param array  $addresses
     */
    public function __construct(string $multisigAddress, array $weights, array $addresses)
    {
        $this->multisigAddress = $multisigAddress;
        $this->weights         = $weights;
        $this->addresses       = $addresses;
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

        return [
            hex2bin(Helper::removeWalletPrefix($this->multisigAddress)),
            $weights,
            $addresses
        ];
    }

    public function decodeData()
    {
        $weights = [];
        foreach ($this->weights as $weight) {
            $weights[] = hexdec($weight);
        }

        $addresses = [];
        foreach ($this->addresses as $address) {
            $addresses[] = Helper::addWalletPrefix($address);
        }

        $this->multisigAddress = Helper::addWalletPrefix($this->multisigAddress);
        $this->weights         = $weights;
        $this->addresses       = $addresses;
    }
}