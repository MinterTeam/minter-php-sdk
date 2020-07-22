<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterConverter;

/**
 * Class MinterCreateCoinTx
 * @package Minter\SDK\MinterCoins
 */
class MinterCreateCoinTx extends MinterCoinTx implements MinterTxInterface
{
    public $name;
    public $symbol;
    public $amount;
    public $reserve;
    public $crr;
    public $maxSupply;

    const TYPE       = 5;
    const COMMISSION = 1000;

    /**
     * MinterCreateCoinTx constructor.
     * @param $name
     * @param $symbol
     * @param $amount
     * @param $reserve
     * @param $crr
     * @param $maxSupply
     */
    public function __construct($name, $symbol, $amount, $reserve, $crr, $maxSupply)
    {
        $this->name      = $name;
        $this->symbol    = $symbol;
        $this->amount    = $amount;
        $this->reserve   = $reserve;
        $this->crr       = $crr;
        $this->maxSupply = $maxSupply;
    }

    /**
     * Prepare tx data for signing
     *
     * @return array
     */
    public function encodeData(): array
    {
        return [
            $this->name,
            MinterConverter::convertCoinName($this->symbol),
            MinterConverter::convertToPip($this->amount),
            MinterConverter::convertToPip($this->reserve),
            $this->crr === 0 ? '' : $this->crr,
            MinterConverter::convertToPip($this->maxSupply)
        ];
    }

    public function decodeData()
    {
        $this->name      = Helper::hex2str($this->name);
        $this->symbol    = Helper::hex2str($this->symbol);
        $this->amount    = MinterConverter::convertToBase(Helper::hexDecode($this->amount));
        $this->reserve   = MinterConverter::convertToBase(Helper::hexDecode($this->reserve));
        $this->crr       = hexdec($this->crr);
        $this->maxSupply = MinterConverter::convertToBase(Helper::hexDecode($this->maxSupply));
    }
}