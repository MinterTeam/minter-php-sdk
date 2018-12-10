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
    const VALID_SIGNATURE = '0xf88f01018a4d4e540000000000000007b6f5a00eb98ea04ae466d8d38f490db3c99b3996a90e24243952ce9822c6dc1e2c1a438a4d4e5400000000000000888ac7230489e80000808001b845f8431ca07d7e28a5dc1e5ceca0c0b52c22331d813b9eed8a4a6bfad8b8a3c96cad72eddfa020358b98ceceaca7b36d034b738916a410af531a88c1a4cb7c95990b9a0c703e';

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
            'serviceData' => '',
            'signatureType' => MinterTx::SIGNATURE_SINGLE_TYPE
        ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
