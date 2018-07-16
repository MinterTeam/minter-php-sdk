<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterBuyCoinTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterSendCoinTx
 */
final class MinterBuyCoinTxTest extends TestCase
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
        'coinToBuy' => 'MNT',
        'valueToBuy' => '1',
        'coinToSell' => 'TEST'
    ];

    /**
     * Predefined valid signature
     */
    const VALID_SIGNATURE = 'f869010103a0df8a4d4e5400000000000000880de0b6b3a76400008a5445535400000000000080801ba0e81b31217cd329ec9b28ae1dfdbe98ef444054f64900cbd5945f4c89d0e378caa05d8ee5f161340dfd7a1055da0541171750e9e9a7938b33582fdf5a84290094e6';

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
            'type' => MinterBuyCoinTx::TYPE,
            'data' => self::DATA,
            'payload' => '',
            'serviceData' => ''
        ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
