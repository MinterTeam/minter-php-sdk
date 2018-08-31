<?php

namespace Minter\Library;

use Elliptic\EC;
use Elliptic\EC\KeyPair;

/**
 * Class ECDSA
 * @package Minter\Library
 */
class ECDSA
{
    /**
     * bits for recovery param in elliptic curve
     */
    const V_BITS = 27;

    /**
     * Convert private key to public key using pure PHP
     *
     * @param string $privateKey
     * @return string
     */
    public static function privateToPublic(string $privateKey): string
    {
        // create elliptic curve and get public key
        $ellipticCurve = new EC('secp256k1');
        $keyPair = new KeyPair($ellipticCurve, [
            'priv' => $privateKey,
            'privEnc' => 'hex'
        ]);

        $publicKey = $keyPair->getPublic('hex');

        return substr($publicKey, 2, 130);
    }

    /**
     * Sign using pure PHP library
     *
     * @param string $message
     * @param string $privateKey
     * @return array
     */
    public static function sign(string $message, string $privateKey): array
    {
        // create elliptic curve and sign
        $ellipticCurve = new EC('secp256k1');
        $signature = $ellipticCurve->sign($message, $privateKey, 'hex', ['canonical' => true]);

        // convert to hex
        $r = $signature->r->toString('hex');
        $s = $signature->s->toString('hex');
        $recovery = $signature->recoveryParam;

        return self::encodeSign($r, $s, $recovery);
    }


    /**
     * Recover public key using pure PHP library
     *
     * @param string $msg
     * @param string $r
     * @param string $s
     * @param int $recovery
     * @return string
     */
    public static function recover(string $msg, string $r, string $s, int $recovery): string
    {
        // define the recovery param
        $recovery = $recovery === self::V_BITS ? 0 : 1;

        // define the signature
        $signature = [
            'r' => $r,
            's' => $s,
            'recoveryParam' => $recovery
        ];

        // create elliptic curve
        $ellipticCurve = new EC('secp256k1');
        $point = $ellipticCurve->recoverPubKey($msg, $signature, $recovery, 'hex');

        // create key pair from point
        $key = new KeyPair($ellipticCurve, [
            'pub' => $point,
            'pubEnc' => 'hex'
        ]);

        return substr($key->getPublic('hex'), 2, 130);
    }

    /**
     * Encore result params (V, R , S)
     *
     * @param string $r
     * @param string $s
     * @param int $recovery
     * @return array
     */
    protected static function encodeSign(string $r, string $s, int $recovery): array
    {
        $r = Helper::padToEven($r);
        $s = Helper::padToEven($s);

        return [
            'v' => $recovery + self::V_BITS,
            'r' => hex2bin($r),
            's' => hex2bin($s)
        ];
    }
}
