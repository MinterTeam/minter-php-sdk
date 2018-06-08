<?php

namespace Minter\Library;

use kornrunner\Keccak;
use Minter\SDK\MinterWallet;
use Elliptic\EC;

class Helper
{
    /**
     * bits for recovery param in elliptic curve
     */
    const V_BITS = 27;

    /**
     * Decode from hex
     *
     * @param string $hex
     * @return string
     */
    public static function hexDecode(string $hex): string
    {
        return gmp_strval(gmp_init($hex, 16), 10);
    }

    /**
     * Pack to hex string and remove nulls
     *
     * @param $data
     * @return string
     */
    public static function pack2hex(string $data): string
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
        return substr($string, strlen(MinterWallet::PREFIX));
    }

    /**
     * Add MinterWallet prefix to address or transaction
     *
     * @param string $string
     * @return string
     */
    public static function addWalletPrefix(string $string): string
    {
        return MinterWallet::PREFIX . $string;
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
     * @throws \Exception
     */
    public static function createKeccakHash(string $dataString): string
    {
        $binaryTx = hex2bin($dataString);

        return Keccak::hash($binaryTx, 256);
    }

    /**
     * Format signature V R S parameters
     *
     * @param EC\Signature $signature
     * @return array
     */
    public static function formatSignatureParams(EC\Signature $signature): array
    {
        $r = self::padToEven($signature->r->toString('hex'));
        $s = self::padToEven($signature->s->toString('hex'));

        return [
            'v' => $signature->recoveryParam + self::V_BITS,
            'r' => hex2bin($r),
            's' => hex2bin($s)
        ];
    }
}
