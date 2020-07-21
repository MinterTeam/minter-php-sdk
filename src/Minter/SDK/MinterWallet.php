<?php

namespace Minter\SDK;

use BitWasp\BitcoinLib\BIP39\BIP39;
use kornrunner\Keccak;
use BIP\BIP44;
use Minter\Library\ECDSA;
use Minter\Library\Helper;

/**
 * Class MinterWallet
 * @package Minter\SDK
 */
class MinterWallet
{
    private $mnemonic;
    private $privateKey;
    private $publicKey;
    private $address;

    /**
     * Amount of entropy bits
     */
    const BIP44_ENTROPY_BITS = 128;

    /**
     * Address path for creating wallet from the seed
     */
    const BIP44_SEED_ADDRESS_PATH = "m/44'/60'/0'/0/0";

    /**
     * Generate new random Minter wallet
     *
     * MinterWallet constructor.
     */
    public function __construct()
    {
        $this->mnemonic   = self::generateMnemonic();
        $this->privateKey = self::mnemonicToPrivateKey($this->mnemonic);
        $this->publicKey  = self::privateToPublic($this->privateKey);
        $this->address    = self::getAddressFromPublicKey($this->publicKey);
    }

    /**
     * Create Minter wallet by private key
     *
     * @param string $privateKey
     * @return MinterWallet
     */
    public static function createFromPrivate(string $privateKey): MinterWallet
    {
        $wallet             = new MinterWallet();
        $wallet->privateKey = $privateKey;
        $wallet->publicKey  = self::privateToPublic($wallet->getPrivateKey());
        $wallet->address    = self::getAddressFromPublicKey($wallet->getPublicKey());
    }

    /**
     * Create Minter wallet by mnemonic phrase
     *
     * @param string $mnemonic
     * @return MinterWallet
     */
    public static function createFromMnemonic(string $mnemonic): MinterWallet
    {
        $wallet             = new MinterWallet();
        $wallet->mnemonic   = $mnemonic;
        $wallet->privateKey = self::mnemonicToPrivateKey($wallet->getMnemonic());
        $wallet->publicKey  = self::privateToPublic($wallet->getPrivateKey());
        $wallet->address    = self::getAddressFromPublicKey($wallet->getPublicKey());
    }

    /**
     * Generate public key
     *
     * @param string $privateKey
     * @return string
     */
    public static function privateToPublic(string $privateKey): string
    {
        return MinterPrefix::PUBLIC_KEY . ECDSA::privateToPublic($privateKey);
    }

    /**
     * Retrieve address from public key
     *
     * @param string $publicKey
     * @return string
     */
    public static function getAddressFromPublicKey(string $publicKey): string
    {
        $publicKey = Helper::removePrefix($publicKey, MinterPrefix::PUBLIC_KEY);
        $hash = Keccak::hash(hex2bin($publicKey), 256);
        return MinterPrefix::ADDRESS . substr($hash, -40);
    }

    /**
     * Generate mnemonic phrase from entropy.
     *
     * @return string
     */
    public static function generateMnemonic(): string
    {
        return BIP39::entropyToMnemonic(
            BIP39::generateEntropy(self::BIP44_ENTROPY_BITS)
        );
    }

    /**
     * Get private key from mnemonic.
     *
     * @param string $mnemonic
     * @return string
     */
    public static function mnemonicToPrivateKey(string $mnemonic): string
    {
        $seed = BIP39::mnemonicToSeedHex($mnemonic, '');
        return BIP44::fromMasterSeed($seed)->derive(self::BIP44_SEED_ADDRESS_PATH)->privateKey;
    }

    /**
     * Validate that address is valid Minter address
     *
     * @param string $address
     * @return bool
     */
    public static function validateAddress(string $address): bool
    {
        return strlen($address) === 42 && substr($address, 0, 2) === MinterPrefix::ADDRESS && ctype_xdigit(substr($address, -40));
    }

    /**
     * @return string
     */
    public function getMnemonic(): string
    {
        return $this->mnemonic;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getPrivateKey(): string
    {
        return $this->privateKey;
    }

    /**
     * @return string
     */
    public function getPublicKey(): string
    {
        return $this->publicKey;
    }
}