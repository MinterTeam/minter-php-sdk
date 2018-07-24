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
    const VALID_SIGNATURE = 'f87601018a4d4e54000000000000000ba2e1a00eb98ea04ae466d8d38f490db3c99b3996a90e24243952ce9822c6dc1e2c1a4380801ba06e7c31b9354ee66187de39c9089b6d94053a86ce918de9d15d4eb153b41f8020a054659a4beb46576e10fc04dd34a85fb0bb0b56a3ed75fab023de9f74988c6988';

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
            'gasCoin' => 'MNT',
            'type' => MinterSetCandidateOnTx::TYPE,
            'data' => self::DATA,
            'payload' => '',
            'serviceData' => ''
        ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
