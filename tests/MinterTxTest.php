<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Minter\SDK\MinterTx;
use Minter\SDK\MinterWallet;

/**
 * Class for testing MinterTx
 */
final class MinterTxTest extends TestCase
{
    /**
     * Transaction structure
     */
    const TX = [
        'nonce' => 1,
        'chainId' => 2,
        'gasPrice' => 1,
        'gasCoin' => 'MNT',
        'type' => 1,
        'data' => [
            'to' => 'Mx1b685a7c1e78726c48f619c497a07ed75fe00483',
            'value' => '1',
            'coin' => 'MNT'
        ],
        'payload' => '',
        'serviceData' => '',
        'signatureType' => 1
    ];

    /**
     * Intermediate transaction representation
     */
    const INTERMEDIATE_TX = [
        'nonce' => 1,
        'chainId' => 2,
        'gasPrice' => 1,
        'gasCoin' => 'MNT',
        'type' => 1,
        'data' => [
            '4d4e5400000000000000',
            '1b685a7c1e78726c48f619c497a07ed75fe00483',
            '0de0b6b3a7640000'
        ],
        'payload' => '',
        'serviceData' => '',
        'signatureType' => 1,
        'signatureData' => [
            'v' => 28,
            'r' => '1f36e51600baa1d89d2bee64def9ac5d88c518cdefe45e3de66a3cf9fe410de4',
            's' => '1bc2228dc419a97ded0efe6848de906fbe6c659092167ef0e7dcb8d15024123a'
        ]
    ];

    /**
     * Sender Minter address
     */
    const SENDER_ADDRESS = 'Mx31e61a05adbd13c6b625262704bc305bf7725026';

    /**
     * Private key for transaction
     */
    const PRIVATE_KEY = '07bc17abdcee8b971bb8723e36fe9d2523306d5ab2d683631693238e0f9df142';

    /**
     * Predefined valid transaction
     */
    const VALID_TX = '0xf8840102018a4d4e540000000000000001aae98a4d4e5400000000000000941b685a7c1e78726c48f619c497a07ed75fe00483880de0b6b3a7640000808001b845f8431ca01f36e51600baa1d89d2bee64def9ac5d88c518cdefe45e3de66a3cf9fe410de4a01bc2228dc419a97ded0efe6848de906fbe6c659092167ef0e7dcb8d15024123a';

    /**
     * Predefined valid hash
     */
    const VALID_HASH = 'Mt13b73500c171006613fa8e82cc8b29857af1d63a';

    /**
     * Test signing.
     */
    public function testSign()
    {
        $tx = new MinterTx(self::TX);
        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertEquals(self::VALID_TX, $signature);
    }

    /**
     * Test get sender address.
     */
    public function testGetSenderAddress()
    {
        $tx = new MinterTx(self::VALID_TX);
        $address = $tx->getSenderAddress(self::INTERMEDIATE_TX);

        $this->assertEquals(self::SENDER_ADDRESS, $address);
    }

    /**
     * Test get hash by transaction.
     */
    public function testGetHash()
    {
        // test getting hash after decoding
        $tx = new MinterTx(self::VALID_TX);
        $this->assertEquals(self::VALID_HASH, $tx->getHash());

        // test getting hash after encoding
        $tx = new MinterTx(self::TX);
        $tx->sign(self::PRIVATE_KEY);
        $this->assertEquals(self::VALID_HASH, $tx->getHash());
    }
}
