<?php
declare(strict_types=1);

use Minter\SDK\MinterSendCoinTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterSendCoinTx
 */
final class MinterSendCoinTxTest extends TestCase
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
        'coin' => 'MNT',
        'to' => 'Mxfe60014a6e9ac91618f5d1cab3fd58cded61ee99',
        'value' => '1'
    ];

    /**
     * Predefined valid signature
     */
    const VALID_SIGNATURE = 'Mxf86e010101a6e58a4d4e540000000000000094fe60014a6e9ac91618f5d1cab3fd58cded61ee998405f5e100801ba0d1d3408ae9019e0ebeb0c04fb4c48e4947ceb1da3b9ddab8a66052047643fbb0a007672ed1604cace1a9acd24700fa093ed094290ea696c556bb169bd583e0ac02';

    /**
     * Test to decode data for MinterSendCoinTx
     */
    public function testDecode(): void
    {
        $tx = new MinterTx(self::VALID_SIGNATURE);

        $this->assertSame($tx->data, self::DATA);
        $this->assertSame($tx->from, self::MINTER_ADDRESS);
    }

    /**
     * Test signing MinterSendCoinTx
     */
    public function testSign(): void
    {
        $tx = new MinterTx([
            'nonce' => 1,
            'gasPrice' => 1,
            'type' => MinterSendCoinTx::TYPE,
            'data' => self::DATA,
            'payload' => ''
        ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
