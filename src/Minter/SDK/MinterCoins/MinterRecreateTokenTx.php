<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Minter\Library\Helper;
use Minter\SDK\MinterConverter;

/**
 * Class MinterRecreateTokenTx
 * @package Minter\SDK\MinterCoins
 */
class MinterRecreateTokenTx extends MinterCoinTx implements MinterTxInterface
{
    public $name;
    public $symbol;
    public $initialAmount;
    public $maxSupply;
    public $mintable;
    public $burnable;

    const TYPE = 31;

    /**
     * MinterRecreateTokenTx constructor.
     * @param $name
     * @param $symbol
     * @param $initialAmount
     * @param $maxSupply
     * @param $mintable
     * @param $burnable
     */
    public function __construct($name, $symbol, $initialAmount, $maxSupply, $mintable, $burnable)
    {
        $this->name          = $name;
        $this->symbol        = $symbol;
        $this->initialAmount = $initialAmount;
        $this->maxSupply     = $maxSupply;
        $this->mintable      = $mintable;
        $this->burnable      = $burnable;
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
            MinterConverter::convertToPip($this->initialAmount),
            MinterConverter::convertToPip($this->maxSupply),
            (int) $this->mintable,
            (int) $this->burnable
        ];
    }

    public function decodeData()
    {
        $this->name          = Helper::hex2str($this->name);
        $this->symbol        = Helper::hex2str($this->symbol);
        $this->initialAmount = MinterConverter::convertToBase(Helper::hexDecode($this->initialAmount));
        $this->maxSupply       = MinterConverter::convertToBase(Helper::hexDecode($this->maxSupply));
        $this->mintable      = (bool) hexdec($this->mintable);
        $this->burnable      = (bool) hexdec($this->burnable);
    }
}
