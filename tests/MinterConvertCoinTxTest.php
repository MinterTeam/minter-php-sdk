<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterConvertCoinTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterConvertCoinTx
 */
final class MinterConvertCoinTxTest extends TestCase
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
        'coin_from' => 'MNT',
        'coin_to' => 'SPRTEST',
        'value' => '1'
    ];

    /**
     * Predefined valid signature
     */
    const VALID_SIGNATURE = 'f869010102a0df8a4d4e54000000000000008a53505254455354000000880de0b6b3a764000080801ba09ea1259e0b94b0e136c54ddf9fe97aefb47c6208a307a692169f4f7c8606d24aa030fee0c7978dde0aa9ab814593ab3c99c1be3d0ce0ceeb607f25a29acf3a958c';

    /**
     * Test to decode data for MinterConvertCoinTx
     */
    public function testDecode(): void
    {
        $tx = new MinterTx(self::VALID_SIGNATURE);

        $this->assertSame($tx->data, self::DATA);
        $this->assertSame($tx->from, self::MINTER_ADDRESS);
    }

    /**
     * Test signing MinterConvertCoinTx
     */
    public function testSign(): void
    {
        $tx = new MinterTx([
            'nonce' => 1,
            'gasPrice' => 1,
            'type' => MinterConvertCoinTx::TYPE,
            'data' => self::DATA,
            'payload' => '',
            'serviceData' => ''
        ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
