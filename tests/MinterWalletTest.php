<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Minter\SDK\MinterWallet;

/**
 * Class for testing MinterWallet
 */
final class MinterWalletTest extends TestCase
{
    /**
     * Predefined private key
     */
    const VALID_PRIVATE_KEY = '21bdd69f4a6d0db9508bc543e26bd23378518c8c409496f9ef3e015ff17fc005';

    /**
     * Predefined public key
     */
    const VALID_PUBLIC_KEY = 'Mpb03a4a53b02fba4023ed1afd2598fa1e5fae220198109b865218598f9456a3be4a1e577e0ddb8c72c55b3a0e7102eb35c9dccc1dd4e32228a369b0a57e15bddf';

    /**
     * Predefined address
     */
    const VALID_ADDRESS = 'Mx17b1240ba6d45258f836b45ae0c4fc1106f5ce59';

    /**
     * Test converting private key to public key.
     */
    public function testPrivateToPublic()
    {
        $this->assertEquals(
            self::VALID_PUBLIC_KEY,
            MinterWallet::privateToPublic(self::VALID_PRIVATE_KEY)
        );
    }

    /**
     * Test retrieve Minter address from public key.
     */
    public function testGetAddressFromPublicKey()
    {
        $this->assertEquals(
            self::VALID_ADDRESS,
            MinterWallet::getAddressFromPublicKey(self::VALID_PUBLIC_KEY)
        );
    }

    /**
     * Test validate address.
     */
    public function testValidateAddress()
    {
        $this->assertTrue(
            MinterWallet::validateAddress(self::VALID_ADDRESS)
        );

        $invalidAddresses = [
            '',
            'Mx17a9b170a65e92b0f814d5769c7fca6b4accd64',
            'Mx17a9b170a65e92b0f814d5769c7fca6b4accd64634',
            '0x17a9b170a65e92b0f814d5769c7fca6b4accd646'
        ];

        foreach ($invalidAddresses as $address) {
            $this->assertFalse(
                MinterWallet::validateAddress($address)
            );
        }
    }

    /**
     * Test creating wallet.
     */
    public function testCreateWallet()
    {
        $wallet = new MinterWallet();

        // check mnemonic words count
        $this->assertEquals(12, str_word_count($wallet->getMnemonic()));

        // check public key length
        $this->assertEquals(130, strlen($wallet->getPublicKey()));

        // check private key length
        $this->assertEquals(64, strlen($wallet->getPrivateKey()));

        // check that address is valid
        $this->assertTrue(MinterWallet::validateAddress($wallet->getAddress()));

        // check that address retrieved from public key
        $this->assertEquals(
            $wallet->getAddress(),
            MinterWallet::getAddressFromPublicKey($wallet->getPublicKey())
        );

        // check that private key and public key matches
        $this->assertEquals(
            $wallet->getPublicKey(),
            MinterWallet::privateToPublic($wallet->getPrivateKey())
        );
    }

    /**
     * Test creating wallet from mnemonic phrase.
     */
    public function testCreateWalletFromMnemonic()
    {
        $wallet = MinterWallet::createFromMnemonic('penalty tube illegal bleak typical sausage glance taste calm stay slide found');

        // check mnemonic words count
        $this->assertEquals(12, str_word_count($wallet->getMnemonic()));

        // check public key length
        $this->assertEquals(130, strlen($wallet->getPublicKey()));

        // check private key length
        $this->assertEquals(64, strlen($wallet->getPrivateKey()));

        // check that address retrieved from public key
        $this->assertEquals('Mx67691076548b20234461ff6fd2bc9c64393eb8fc', $wallet->getAddress());

        // check that private key and public key matches
        $this->assertEquals('4daf02f92bf760b53d3c725d6bcc0da8e55d27ba5350c78d3a88f873e502bd6e', $wallet->getPrivateKey());
    }

    /**
     * Test creating wallet from private key.
     */
    public function testCreateWalletFromPrivateKey()
    {
        $wallet = MinterWallet::createFromPrivate('4daf02f92bf760b53d3c725d6bcc0da8e55d27ba5350c78d3a88f873e502bd6e');

        // check public key length
        $this->assertEquals(130, strlen($wallet->getPublicKey()));

        // check private key length
        $this->assertEquals(64, strlen($wallet->getPrivateKey()));

        // check that address retrieved from public key
        $this->assertEquals('Mx67691076548b20234461ff6fd2bc9c64393eb8fc', $wallet->getAddress());

        // check that private key and public key matches
        $this->assertEquals('4daf02f92bf760b53d3c725d6bcc0da8e55d27ba5350c78d3a88f873e502bd6e', $wallet->getPrivateKey());
    }
}
