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
    const VALID_SIGNATURE = 'f87601018a4d4e54000000000000000aa2e1a00eb98ea04ae466d8d38f490db3c99b3996a90e24243952ce9822c6dc1e2c1a4380801ba050022f81c7f0c9bd562c2c061c1dd3d9061c2ab9286c5a6e68ed6f0fd5accaf5a00f8dbe26570e325cd919425b4eefea6b9ac0a4c93513b47c6e968e4312defaa5';

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
