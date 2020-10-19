<?php

namespace Minter\Contracts;

use Web3p\RLP\Buffer;

interface MinterTxInterface
{
    /**
     * Prepare data tx for signing
     *
     * @return Buffer
     */
    public function encode(): Buffer;

    /**
     * Prepare output tx data
     */
    public function decodeData();

    /**
     * Get transaction type.
     *
     * @return int
     */
    public function getType(): int;
}