<?php

namespace Minter\Library;

use kornrunner\Keccak;
use Minter\SDK\MinterPrefix;
use Web3p\RLP\Buffer;

/**
 * Class Helper
 * @package Minter\Library
 */
class Helper
{
    /**
     * Decode from hex
     *
     * @param string $hex
     * @return string
     */
    public static function hexDecode(string $hex): string
    {
        $hex = empty($hex) ? '0' : $hex;
        return gmp_strval(gmp_init($hex, 16), 10);
    }

    /**
     * Convert number to hex string
     *
     * @param string $number
     * @return string
     */
    public static function dechex(string $number): string
    {
        $hex = gmp_strval(gmp_init($number, 10), 16);
        return (strlen($hex) % 2 != 0) ? '0' . $hex : $hex;
    }

    /**
     * Pack to hex string and remove nulls
     *
     * @param $data
     * @return string
     */
    public static function hex2str(string $data): string
    {
        return str_replace(chr(0), '', pack('H*', $data));
    }

    /**
     * Detect hex string and convert to bin
     *
     * @param array $data
     * @return array
     */
    public static function hex2binRecursive(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = self::hex2binRecursive($value);
            } elseif (is_string($value) && ctype_xdigit($value)) {
                $data[$key] = hex2bin($value);
            }
        }

        return $data;
    }

    /**
     * Remove MinterWallet prefix from address or transaction
     *
     * @param string $string
     * @return string
     */
    public static function removeWalletPrefix(string $string): string
    {
        return self::removePrefix($string, MinterPrefix::ADDRESS);
    }

    /**
     * Remove prefix
     *
     * @param string $string
     * @param string $prefix
     * @return string
     */
    public static function removePrefix(string $string, string $prefix): string
    {
        return substr($string, strlen($prefix));
    }

    /**
     * Add MinterWallet prefix to address or transaction
     *
     * @param string $string
     * @return string
     */
    public static function addWalletPrefix(string $string): string
    {
        return MinterPrefix::ADDRESS . $string;
    }

    /**
     * Remove 0s from the end
     *
     * @param $number
     * @return string
     */
    public static function niceNumber($number): string
    {
        return rtrim(rtrim($number, '0'), '.');
    }

    /**
     * Add 0 if length of hex string is odd
     *
     * @param string $hexString
     * @return string
     */
    public static function padToEven(string $hexString): string
    {
        return strlen($hexString) % 2 !== 0 ? '0' . $hexString : $hexString;
    }

    /**
     * Create Keccak 256 hash
     *
     * @param array $tx
     * @return string
     */
    public static function createKeccakHash(string $dataString): string
    {
        $binaryTx = hex2bin($dataString);

        return Keccak::hash($binaryTx, 256);
    }

    /**
     * Convert RLP array to hex array
     *
     * @param array $rlp
     * @return array
     */
    public static function rlpArrayToHexArray(array $rlp): array
    {
        return array_map(function($item) {
            if(!is_array($item)) {
                return $item->toString('hex');
            }

            return self::rlpArrayToHexArray($item);
        }, $rlp);
    }

    /**
     * Create buffer from data recursively.
     *
     * @param $data
     * @return array|Buffer
     */
    public static function hex2buffer($data)
    {
        if(is_array($data)) {
            return array_map(function($item) {
                return self::hex2buffer($item);
            }, $data);
        }

        return new Buffer($data, 'hex');
    }

    /**
     * @param string $str
     * @return string
     */
    public static function str2hex(string $str): string
    {
        $str = unpack('H*', $str);

        return array_shift($str);
    }

    /**
     * @param string $str
     * @return Buffer
     */
    public static function str2buffer(string $str): Buffer
    {
        $splitted = str_split($str, 1);
        return new Buffer($splitted);
    }
}
