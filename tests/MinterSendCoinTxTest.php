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
    const PRIVATE_KEY = '4c9a495b52aeaa839e53c3eb2f2d6650d892277bde58a24bb6a396f2bb31aa37';

    /**
     * Predefined minter address
     */
    const MINTER_ADDRESS = 'Mxfd250353d712dc19abf4c5453050b92ca7193285';

    /**
     * Predefined data
     */
    const DATA = [
        'coin' => 'MNT',
        'to' => 'Mxccc3fc91a3d47dc1ee26d62611a09831f0214d62',
        'value' => '10'
    ];

    /**
     * Predefined valid signature
     */
    const VALID_SIGNATURE = 'f87e01018a4d4e540000000000000001aae98a4d4e540000000000000094ccc3fc91a3d47dc1ee26d62611a09831f0214d62888ac7230489e8000080801ba024219a3729a7a7750df77027567b3b89ca2adbcaa3391182fe1ce4cdc4e9431ba05fec62e4fd71a25fe3a628bfd3a4d86519345a47f721034de04b3259d73b1945';

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
            'gasCoin' => 'MNT',
            'type' => MinterSendCoinTx::TYPE,
            'data' => self::DATA,
            'payload' => '',
            'serviceData' => ''
        ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
