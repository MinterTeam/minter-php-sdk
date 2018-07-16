<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterDeclareCandidacyTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterDeclareCandidacyTx
 */
final class MinterDeclareCandidacyTxTest extends TestCase
{
    /**
     * Predefined private key
     */
    const PRIVATE_KEY = '6e1df6ec69638d152f563c5eca6c13cdb5db4055861efc11ec1cdd578afd96bf';

    /**
     * Predefined data
     */
    const DATA = [
        'address' => 'Mx9f7fd953c2c69044b901426831ed03ee0bd0597a',
        'pubkey' => 'Mp0eb98ea04ae466d8d38f490db3c99b3996a90e24243952ce9822c6dc1e2c1a43',
        'commission' => '10',
        'coin' => 'MNT',
        'stake' => '5'
    ];

    /**
     * Predefined valid signature
     */

    const VALID_SIGNATURE = 'f897010105b84df84b949f7fd953c2c69044b901426831ed03ee0bd0597aa00eb98ea04ae466d8d38f490db3c99b3996a90e24243952ce9822c6dc1e2c1a430a8a4d4e5400000000000000884563918244f4000080801ba0e0ebd13986a77b3621465219c642d18db5864fcbda9bd5cb1630127f81dbe895a036d1451f8db6d32925e2c364bd2e4c7462e7037c90dccc80f9c3bbcb3d9addbc';

    /**
     * Test to decode data for MinterDeclareCandidacyTx
     */
    public function testDecode(): void
    {
        $tx = new MinterTx(self::VALID_SIGNATURE);

        $this->assertSame($tx->data, self::DATA);
    }

    /**
     * Test signing MinterDeclareCandidacyTx
     */
    public function testSign(): void
    {
        $tx = new MinterTx([
            'nonce' => 1,
            'gasPrice' => 1,
            'type' => MinterDeclareCandidacyTx::TYPE,
            'data' => self::DATA,
            'payload' => '',
            'serviceData' => ''
        ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
