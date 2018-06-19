<?php
declare(strict_types=1);

use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterSetCandidateOffTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterSetCandidateOffTx
 */
final class MinterSetCandidateOffTxTest extends TestCase
{
    /**
     * Predefined private key
     */
    const PRIVATE_KEY = '05ddcd4e6f7d248ed1388f0091fe345bf9bf4fc2390384e26005e7675c98b3c1';

    /**
     * Predefined data
     */
    const DATA = [
        'pubkey' => 'Mp0eb98ea04ae466d8d38f490db3c99b3996a90e24243952ce9822c6dc1e2c1a43'
    ];

    /**
     * Predefined valid signature
     */
    const VALID_SIGNATURE = 'f86b010109a2e1a00eb98ea04ae466d8d38f490db3c99b3996a90e24243952ce9822c6dc1e2c1a4380801ba0cb30ae55441809b191f0294af1348514f51d03a4c5950b25546f043441af0060a07ecd8fc89c9b6491a7db82a6412b63a941079cf1ee8ea3fe1e201f1fa815b13d';

    /**
     * Test to decode data for MinterSetCandidateOffTx
     */
    public function testDecode(): void
    {
        $tx = new MinterTx(self::VALID_SIGNATURE);

        $this->assertSame($tx->data, self::DATA);
    }

    /**
     * Test signing MinterSetCandidateOnTx
     */
    public function testSign(): void
    {
        $tx = new MinterTx([
            'nonce' => 1,
            'gasPrice' => 1,
            'type' => MinterSetCandidateOffTx::TYPE,
            'data' => self::DATA,
            'payload' => '',
            'serviceData' => ''
        ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
