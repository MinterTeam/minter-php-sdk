<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterDelegateTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterDelegateTx
 */
final class MinterDelegateTxTest extends TestCase
{
    /**
     * Predefined private key
     */
    const PRIVATE_KEY = '6e1df6ec69638d152f563c5eca6c13cdb5db4055861efc11ec1cdd578afd96bf';

    /**
     * Predefined data
     */
    const DATA = [
        'pubkey' => 'Mp0eb98ea04ae466d8d38f490db3c99b3996a90e24243952ce9822c6dc1e2c1a43',
        'coin' => 'MNT',
        'stake' => '10'
    ];

    /**
     * Predefined valid signature
     */
    const VALID_SIGNATURE = 'f88a01018a4d4e540000000000000007b6f5a00eb98ea04ae466d8d38f490db3c99b3996a90e24243952ce9822c6dc1e2c1a438a4d4e5400000000000000888ac7230489e8000080801ca0ac5286aef668ff5b5a08768aaaf1c15fff0a89b9f4bb88683931fca35052411ca01e66dff2b0465cb4f9905f16be22834b696d2a429e9add8277ec3135faf1ac67';

    /**
     * Test to decode data for MinterDelegateTx
     */
    public function testDecode(): void
    {
        $tx = new MinterTx(self::VALID_SIGNATURE);

        $this->assertSame($tx->data, self::DATA);
    }

    /**
     * Test signing MinterDelegateTx
     */
    public function testSign(): void
    {
        $tx = new MinterTx([
            'nonce' => 1,
            'gasPrice' => 1,
            'gasCoin' => 'MNT',
            'type' => MinterDelegateTx::TYPE,
            'data' => self::DATA,
            'payload' => '',
            'serviceData' => ''
        ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
