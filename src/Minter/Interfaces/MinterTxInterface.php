<?php

namespace Minter\Interfaces;

use Web3p\RLP\Buffer;

interface MinterTxInterface
{
    /**
     * getter
     *
     * @param $name
     * @return mixed
     */
    public function __get($name);

    /**
     * RLP encoded tx data
     *
     * @return \Web3p\RLP\Buffer
     */
    public function serialize(): Buffer;

    /**
     * Prepare output tx data
     *
     * @param array $txData
     * @return array
     */
    public function convertFromHex(array $txData): array;
}