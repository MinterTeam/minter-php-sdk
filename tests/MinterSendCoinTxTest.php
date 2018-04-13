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
    const PRIVATE_KEY = '418e4be028dcaed85aa58b643979f644f806a42bb6d1912848720788a53bb8a4';

    /**
     * Predefined data
     */
    const DATA = [
        'coin' => 'MNT',
        'to' => 'Mx62b54f602a70f5f7e276ed1c72ec741b9be1e736',
        'value' => 10
    ];

    /**
     * Predefined valid RLP encoded data of self::DATA
     */
    const VALID_RLP_ENCODED_DATA = 'e58a4d4e54000000000000009462b54f602a70f5f7e276ed1c72ec741b9be1e736843b9aca00';

    /**
     * Predefined valid signature
     */
    const VALID_SIGNATURE = 'Mxf86d010101a6e58a4d4e54000000000000009462b54f602a70f5f7e276ed1c72ec741b9be1e736843b9aca001ca0c1496b98ab7a7eeaab0e9104e017f22c3678111fac1f5c0699c63e69468d944aa071f79981e5a66c5beeaa1ef9f2dc3d9a17824b71adb7bef9aa622872880b7403';

    /**
     * Test to valid RLP encode data of MinterSendCoinTx serialization
     */
    public function testSerialization(): void
    {
        $tx = new MinterSendCoinTx(self::DATA);

        $this->assertSame($tx->serialize()->toString('hex'), self::VALID_RLP_ENCODED_DATA);
    }

    /**
     * Test to decode data for MinterSendCoinTx
     */
    public function testDecode(): void
    {
        $tx = new MinterSendCoinTx([
            implode(unpack("H*", self::DATA['coin'])),
            substr(self::DATA['to'], -40),
            dechex(self::DATA['value'])
        ], true);

        $this->assertSame($tx->coin, self::DATA['coin']);

        $this->assertSame($tx->to, self::DATA['to']);

        $this->assertSame($tx->value, self::DATA['value']);
    }

    /**
     * Test signing MinterSendCoinTx
     */
    public function testSign(): void
    {
        $txData = new MinterSendCoinTx(self::DATA);
        $tx = new MinterTx([
            'nonce' => 1,
            'gasPrice' => 1,
            'type' => 1,
            'data' => $txData->serialize()
        ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
