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
    const PRIVATE_KEY = '07bc17abdcee8b971bb8723e36fe9d2523306d5ab2d683631693238e0f9df142';

    /**
     * Predefined minter address
     */
    const MINTER_ADDRESS = 'Mx31e61a05adbd13c6b625262704bc305bf7725026';

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
    const VALID_SIGNATURE = 'f87901018a4d4e540000000000000002a0df8a4d4e5400000000000000880de0b6b3a76400008a54455354000000000000808001b845f8431ba0c107aba36fed3695a13a0c847008c2e2845897f99f1600f9143821a4189e5099a04a757023d0bb11d6bbdaaff4bec2fe92265f550c520d44af560466126095ba68';

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
            'serviceData' => '',
            'signatureType' => MinterTx::SIGNATURE_SINGLE_TYPE
        ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
