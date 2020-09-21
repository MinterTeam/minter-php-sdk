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
    const TYPE       = 18;
    const COMMISSION = 1000;

    /**
     * Declare candidacy tx data
     *
     * @var array
     */
    public $data = [
        'threshold' => '',
        'weights'   => [],
        'addresses' => []
    ];

    /**
     * Prepare data for signing
     *
     * @return array
     */
    public function encode(): array
    {
        $addresses = [];
        foreach ($this->data['addresses'] as $address) {
            $address     = Helper::removeWalletPrefix($address);
            $addresses[] = hex2bin($address);
        }

        $weights = [];
        foreach ($this->data['weights'] as $weight) {
            $weights[] = $weight === 0 ? '' : $weight;
        }

        $threshold = $this->data['threshold'] === 0 ? '' : $this->data['threshold'];

        return [
            'threshold' => $threshold,
            'weights'   => $weights,
            'addresses' => $addresses,
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
        list($txThreshold, $txWeights, $txAddresses) = $txData;

        $threshold = (int) Helper::hexDecode($txThreshold);

        $weights = [];
        foreach ($txWeights as $weight) {
            $weights[] = (int) Helper::hexDecode($weight);
        }

        $addresses = [];
        foreach ($txAddresses as $address) {
            $addresses[] = Helper::addWalletPrefix($address);
        }

        return [
            'threshold' => $threshold,
            'weights'   => $weights,
            'addresses' => $addresses
        ];
    }
}