<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterSendCoinTx;
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
    const VALID_SIGNATURE = 'f873010101aae98a4d4e540000000000000094fe60014a6e9ac91618f5d1cab3fd58cded61ee99880de0b6b3a764000080801ca0ae0ee912484b9bf3bee785f4cbac118793799450e0de754667e2c18faa510301a04f1e4ed5fad4b489a1065dc1f5255b356ab9a2ce4b24dde35bcb9dc43aba019c';

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
            'payload' => '',
            'serviceData' => ''
        ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
