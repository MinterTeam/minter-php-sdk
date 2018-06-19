<?php
declare(strict_types=1);

use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterSetCandidateOnTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterSetCandidateOnTx
 */
final class MinterSetCandidateOnTxTest extends TestCase
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
    const VALID_SIGNATURE = 'f86b010108a2e1a00eb98ea04ae466d8d38f490db3c99b3996a90e24243952ce9822c6dc1e2c1a4380801ba0476c75739d054211b575576a2edf6b5766776420b0886202ceed8b24e4139e4ea0055fab7f20def30f1e7bac79229bb77889c1dbfb7a016a3b3a461e8486978348';

    /**
     * Test to decode data for MinterSetCandidateOnTx
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
            'type' => MinterSetCandidateOnTx::TYPE,
            'data' => self::DATA,
            'payload' => '',
            'serviceData' => ''
        ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
