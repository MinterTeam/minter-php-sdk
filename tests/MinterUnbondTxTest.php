<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterUnbondTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterUnbondTx
 */
final class MinterUnbondTxTest extends TestCase
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
        'value' => '10'
    ];

    /**
     * Predefined valid signature
     */
    const VALID_SIGNATURE = '0xf88f01018a4d4e540000000000000008b6f5a00eb98ea04ae466d8d38f490db3c99b3996a90e24243952ce9822c6dc1e2c1a438a4d4e5400000000000000888ac7230489e80000808001b845f8431ba0250c4ef7c13a9421503c1109c3119eca4739463299755c58900cd00d6f43f94ca078746cc2fa6deeb8e3e568260e606ac37f7670f2d73cb28c02142c2fc5bfcf5f';

    /**
     * Test to decode data for MinterUnbondTx
     */
    public function testDecode(): void
    {
        $tx = new MinterTx(self::VALID_SIGNATURE);

        $this->assertSame($tx->data, self::DATA);
    }

    /**
     * Test signing MinterUnbondTx
     */
    public function testSign(): void
    {
        $tx = new MinterTx([
            'nonce' => 1,
            'gasPrice' => 1,
            'gasCoin' => 'MNT',
            'type' => MinterUnbondTx::TYPE,
            'data' => self::DATA,
            'payload' => '',
            'serviceData' => '',
            'signatureType' => MinterTx::SIGNATURE_SINGLE_TYPE
        ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
