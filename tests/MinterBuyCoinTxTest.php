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
    const PRIVATE_KEY = '07bc17abdcee8b971bb8723e36fe9d2523306d5ab2d683631693238e0f9df142';

    /**
     * Predefined minter address
     */
    const MINTER_ADDRESS = 'Mx31e61a05adbd13c6b625262704bc305bf7725026';

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
    const VALID_SIGNATURE = 'f87901018a4d4e540000000000000004a0df8a4d4e5400000000000000880de0b6b3a76400008a54455354000000000000808001b845f8431ba0ad46cda1456aad92f6ff21ff37b810bcd928beb1e346b4321a7661eed7ea696aa01ab1e07fb7d98d31ba342c98d93010bedc3cbeebbb6277a892bbdb92d7c00e8d';

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
            'type' => MinterBuyCoinTx::TYPE,
            'data' => self::DATA,
            'payload' => '',
            'serviceData' => '',
            'signatureType' => MinterTx::SIGNATURE_SINGLE_TYPE
        ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
