<?php

namespace Minter\SDK;

use Minter\Library\Helper;
use UnexpectedValueException;

/**
 * Class MinterConverter
 * @package Minter\SDK
 */
class MinterConverter
{
    /**
     * PIP in BIP
     */
    const DEFAULT = '1000000000000000000';

    /**
     * Convert input value to PIPs.
     *
     * @param string $num
     * @return string
     */
    public static function convertValueToPips(string $num): string
    {
        // Input value is already in PIPs:
        if (strlen($num) >= strlen(self::DEFAULT)) {
            return $num;
        }

        return bcmul(self::DEFAULT, $num, 0);
    }

    /**
     * Convert value
     *
     * @param string $num
     * @param string $to
     * @return string
     */
    public static function convertValue(string $num, string $to)
    {
        switch ($to) {
            case 'pip':
                return self::convertValueToPips($num);
                break;

            case 'bip':
                return Helper::niceNumber(bcdiv($num, self::DEFAULT, 25));
                break;

            default:
                throw new UnexpectedValueException("Convertation possible only to \"pip\" or \"bip\", requested: {$to}");
                break;
        }
    }

    /**
     * Add nulls to coin name
     *
     * @param string $symbol
     * @return string
     */
    public static function convertCoinName(string $symbol): string
    {
        return $symbol  . str_repeat(chr(0), 10 - strlen($symbol));
    }
}
