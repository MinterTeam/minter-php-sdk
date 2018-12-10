<?php

namespace Minter\SDK;

/**
 * Define Minter prefixes
 *
 * Class MinterPrefix
 * @package Minter\SDK
 */
class MinterPrefix
{
    /**
     * Minter wallet address prefix
     */
    const ADDRESS = 'Mx';

    /**
     * Minter public key prefix
     */
    const PUBLIC_KEY = 'Mp';

    /**
     * Minter redeem check prefix
     */
    const CHECK = 'Mc';

    /**
     * Minter transaction hash prefix
     */
    const TRANSACTION_HASH = 'Mt';

    /**
     * Minter transaction prefix
     */
    const TRANSACTION = '0x';
}