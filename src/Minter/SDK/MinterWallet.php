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
     * Amount of entropy bits
     */
    const BIP44_ENTROPY_BITS = 128;

    /**
     * Address path for creating wallet from the seed
     */
    const BIP44_SEED_ADDRESS_PATH = "m/44'/60'/0'/0/0";

    /**
     * Create Minter wallet
     *
     * @return array
     * @throws \Exception
     */
    public static function create(): array
    {
        $entropy = BIP39::generateEntropy(self::BIP44_ENTROPY_BITS);
        $mnemonic = BIP39::entropyToMnemonic($entropy);
        $seed = BIP39::mnemonicToSeedHex($mnemonic, '');
        $privateKey = BIP44::fromMasterSeed($seed)->derive(self::BIP44_SEED_ADDRESS_PATH)->privateKey;

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