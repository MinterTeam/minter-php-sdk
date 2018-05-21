<?php

namespace Minter\SDK;

use BitWasp\BitcoinLib\BIP39\BIP39;
use kornrunner\Keccak;
use Elliptic\EC;
use Elliptic\EC\KeyPair;
use BIP\BIP44;

class MinterWallet
{
    /**
     * Prefix for address
     */
    const PREFIX = 'Mx';

    /**
     * Create Minter wallet
     *
     * @return array
     * @throws \Exception
     */
    public static function create(): array
    {
        $entropy = BIP39::generateEntropy(128);
        $mnemonic = BIP39::entropyToMnemonic($entropy);
        $seed = BIP39::mnemonicToSeedHex($mnemonic, '');
        $privateKey = BIP44::fromMasterSeed($seed)->derive("m/44'/60'/0'/0/0")->privateKey;

        $publicKey = self::generatePublicKey([
            'priv' => $privateKey,
            'privEnc' => 'hex'
        ]);

        $address = self::getAddressFromPublicKey($publicKey);

        return [
            'address' => $address,
            'private_key' => $privateKey,
            'mnemonic' => $mnemonic,
            'seed' => $seed
        ];
    }
    
    /**
     * Generate public key
     *
     * @param array $options
     * @return string
     */
    public static function generatePublicKey(array $options): string
    {
        $ec = new EC('secp256k1');
        $keyPair = new KeyPair($ec, $options);

        return substr($keyPair->getPublic('hex'), 2, 130);
    }

    /**
     * Retrieve address from public key
     *
     * @param string $publicKey
     * @return string
     * @throws \Exception
     */
    public static function getAddressFromPublicKey(string $publicKey): string
    {
        $hash = Keccak::hash(hex2bin($publicKey), 256);

        return self::PREFIX . substr($hash, -40);
    }

    /**
     * Validate that address is valid Minter address
     *
     * @param string $address
     * @return bool
     */
    public static function validateAddress(string $address): bool
    {
        return strlen($address) === 42 && substr($address, 0, 2) === self::PREFIX && ctype_xdigit(substr($address, -40));
    }
}