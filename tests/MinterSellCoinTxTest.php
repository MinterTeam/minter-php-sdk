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
    const VALID_SIGNATURE = 'f87401018a4d4e540000000000000002a0df8a4d4e5400000000000000880de0b6b3a76400008a5445535400000000000080801ba068bced880aa12eab4a553637f498af0c760e85a175f0766abbd98515d081c9f8a0045c0476c55609529220c6305bd3c011b15d6104bea936402437625659a1d5fc';

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
            'type' => MinterSellCoinTx::TYPE,
            'data' => self::DATA,
            'payload' => '',
            'serviceData' => ''
        ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
