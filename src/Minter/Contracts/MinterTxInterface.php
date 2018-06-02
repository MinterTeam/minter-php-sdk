<?php

namespace Minter\Contracts;

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
     * Prepare data tx for signing
     *
     * @return array
     */
    public function encode(): array;

    /**
     * Prepare output tx data
     *
     * @param array $txData
     * @return array
     */
    public function decode(array $txData): array;
}