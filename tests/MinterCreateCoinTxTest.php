<?php
declare(strict_types=1);

use Minter\SDK\MinterCreateCoinTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterCreateCoinTx
 */
final class MinterCreateCoinTxTest extends TestCase
{
    /**
     * Predefined private key
     */
    const PRIVATE_KEY = 'b574d2a7151fcf0df573feae58015f85f6ebf38ea4b38c49196c6aceee27e189';

    /**
     * Predefined minter address
     */
    const MINTER_ADDRESS = 'Mx887c5de2515e788abb422c3e483496e1b1f3dff4';

    /**
     * Predefined data
     */
    const DATA = [
        'name' => 'SUPER TEST',
        'symbol' => 'SPRTEST',
        'initialAmount' => '100',
        'initialReserve' => '10',
        'crr' => 10
    ];

    /**
     * Predefined valid signature
     */
    const VALID_SIGNATURE = 'Mxf86b010103a3e28a535550455220544553548a535052544553540000008502540be400843b9aca000a801ba035da98a1af9903c8500686f7992e4de15bf0b9dc4bfe4a773564094e93256cbfa044047e7ee32dc63bc1cce7f0f34db556fac245c69346f85a3fb527df859206b5';

    /**
     * Test to decode data for MinterCreateCoinTx
     */
    public function testDecode(): void
    {
        $tx = new MinterTx(self::VALID_SIGNATURE);

        $this->assertSame($tx->data, self::DATA);
        $this->assertSame($tx->from, self::MINTER_ADDRESS);
    }

    /**
     * Test signing MinterCreateCoinTx
     */
    public function testSign(): void
    {
        $tx = new MinterTx([
            'nonce' => 1,
            'gasPrice' => 1,
            'type' => MinterCreateCoinTx::TYPE,
            'data' => self::DATA,
            'payload' => ''
        ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
