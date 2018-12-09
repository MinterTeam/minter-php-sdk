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
        'coinToBuy' => 'TEST',
        'valueToBuy' => '1',
        'coinToSell' => 'MNT',
        'maximumValueToSell' => '1'
    ];

    /**
     * Predefined valid signature
     */
    const VALID_SIGNATURE = '0xf88401018a4d4e540000000000000004abea8a54455354000000000000880de0b6b3a76400008a4d4e54000000000000008a31000000000000000000808001b845f8431ba0c30019067fe6ede8d5dff8e6977ac19d02a34159ff6f9ac270879b1154ae738ba07038e90b2ba9d5a779a3eb41de5f55679b4b144f8e8ab03ac1d1ea7952531235';

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
