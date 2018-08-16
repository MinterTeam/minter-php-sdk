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
     * Sign message
     *
     * @param string $message
     * @param string $privateKey
     * @return array
     */
    public static function sign(string $message, string $privateKey): array
    {
        if(function_exists('secp256k1_context_create')) {
            return self::signViaExtensions($message, $privateKey);
        }

        return self::signViaPurePHP($message, $privateKey);
    }

    /**
     * Recover public key
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

        if(function_exists('secp256k1_context_create')) {
            return self::recoverViaExtension($msg, $r, $s, $recovery);
        }

        return self::recoverViaPurePHP($msg, $r, $s, $recovery);
    }

    /**
     * Convert private key to public key
     *
     * @param string $privateKey
     * @return string
     */
    public static function privateToPublic(string $privateKey): string
    {
        $publicKey = null;

        if(function_exists('secp256k1_context_create')) {
            $context = secp256k1_context_create(SECP256K1_CONTEXT_SIGN | SECP256K1_CONTEXT_VERIFY);

            // convert key to binary
            $privateKey = hex2bin($privateKey);

            /** @var $publicKeyResource */
            $publicKeyResource = null;
            secp256k1_ec_pubkey_create($context, $publicKeyResource, $privateKey);

            $publicKey = null;
            secp256k1_ec_pubkey_serialize($context, $publicKey, $publicKeyResource, false);

            $publicKey = bin2hex($publicKey);
        }
        else {
            // create elliptic curve and get public key
            $ellipticCurve = new EC('secp256k1');
            $keyPair = new KeyPair($ellipticCurve, [
                'priv' => $privateKey,
                'privEnc' => 'hex'
            ]);

            $publicKey = $keyPair->getPublic('hex');
        }

        return substr($publicKey, 2, 130);
    }

    /**
     * Sign via PHP extension
     *
     * @param $message
     * @param $privateKey
     * @return array
     */
    public static function signViaExtensions($message, $privateKey): array
    {
        // convert params to binary
        $privateKey = hex2bin($privateKey);
        $message = hex2bin($message);

        // create curve context
        $context = secp256k1_context_create(SECP256K1_CONTEXT_SIGN | SECP256K1_CONTEXT_VERIFY);

        /** @var resource $signatureSource */
        $signatureSource = '';
        secp256k1_ecdsa_sign_recoverable($context, $signatureSource, $message, $privateKey);

        $signature = null;
        $recoveryParam = null;
        secp256k1_ecdsa_recoverable_signature_serialize_compact($context, $signatureSource, $signature, $recoveryParam);

        $signature = bin2hex($signature);

        $r = ltrim(substr($signature, 0, 64), '0');
        $s = ltrim(substr($signature, 64, 64), '0');

        return self::encodeSign($r, $s, $recoveryParam);
    }

    /**
     * Sign using pure PHP library
     *
     * @param $message
     * @param $privateKey
     * @return array
     */
    public static function signViaPurePHP($message, $privateKey): array
    {
        // create elliptic curve and sign
        $ellipticCurve = new EC('secp256k1');
        $signature = $ellipticCurve->sign($message, $privateKey, 'hex', ['canonical' => true]);

        $r = $signature->r->toString('hex');
        $s = $signature->s->toString('hex');
        $recovery = $signature->recoveryParam;

        return self::encodeSign($r, $s, $recovery);
    }

    /**
     * Recover public key via PHP extension
     *
     * @param string $msg
     * @param $r
     * @param $s
     * @param int $recovery
     * @return string
     */
    public static function recoverViaExtension(string $msg, $r, $s, int $recovery): string
    {
        // conver to binary
        $msg = hex2bin($msg);

        // define the signature
        $signature = [
            'r' => hex2bin(str_repeat('0', 64 - strlen($r)) . $r),
            's' => hex2bin(str_repeat('0', 64 - strlen($s)) . $s),
            'recoveryParam' => $recovery
        ];

        // create context for curve
        $context = secp256k1_context_create(SECP256K1_CONTEXT_SIGN | SECP256K1_CONTEXT_VERIFY);

        /** @var resource $signatureSource */
        $signatureResource = null;
        secp256k1_ecdsa_recoverable_signature_parse_compact(
            $context,
            $signatureResource,
            $signature['r'] . $signature['s'],
            $signature['recoveryParam']
        );

        /** @var resource $publicKeyResource */
        $publicKeyResource = null;
        secp256k1_ecdsa_recover($context, $publicKeyResource, $signatureResource, $msg);

        $publicKey = '';
        secp256k1_ec_pubkey_serialize($context, $publicKey, $publicKeyResource, false);

        return substr(bin2hex($publicKey), 2, 130);
    }

    /**
     * Recover public key via pure PHP library
     *
     * @param string $msg
     * @param string $r
     * @param string $s
     * @param int $recovery
     * @return string
     */
    public static function recoverViaPurePHP(string $msg, string $r, string $s, int $recovery): string
    {
        // define the signature
        $signature = [
            'r' => $r,
            's' => $s,
            'recoveryParam' => $recovery
        ];

        // create elliptic curve
        $ellipticCurve = new EC('secp256k1');
        $point = $ellipticCurve->recoverPubKey($msg, $signature, $recovery, 'hex');

        $keyPair = new KeyPair($ellipticCurve, [
            'pub' => $point,
            'pubEnc' => 'hex'
        ]);

        return substr($keyPair->getPublic('hex'), 2, 130);
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
