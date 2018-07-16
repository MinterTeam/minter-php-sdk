<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterSellCoinTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterSendCoinTx
 */
final class MinterSellCoinTxTest extends TestCase
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
        'coinToSell' => 'MNT',
        'valueToSell' => '1',
        'coinToBuy' => 'TEST'
    ];

    /**
     * Predefined valid signature
     */
    const VALID_SIGNATURE = 'f869010102a0df8a4d4e5400000000000000880de0b6b3a76400008a5445535400000000000080801ca0ce2568edba9a16aec2d37b25c40ef6874eddea2cf5bf2e2df13991c9e2f117c5a079fb4185eb29cd45b441eb8c5a09995fd43e2b4fa3ee85fad937e5254c9411f6';

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
            'type' => MinterSellCoinTx::TYPE,
            'data' => self::DATA,
            'payload' => '',
            'serviceData' => ''
        ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
