<?php
declare(strict_types=1);

use Minter\SDK\MinterConvertCoinTx;
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
    const PRIVATE_KEY = '418e4be028dcaed85aa58b643979f644f806a42bb6d1912848720788a53bb8a4';

    /**
     * Predefined data
     */
    const DATA = [
        'coin_from' => 'MNT',
        'coin_to' => 'COINTEST',
        'value' => 1
    ];

    /**
     * Predefined valid RLP encoded data of self::DATA
     */
    const VALID_RLP_ENCODED_DATA = 'db8a4d4e54000000000000008a434f494e5445535400008405f5e100';

    /**
     * Predefined valid signature
     */
    const VALID_SIGNATURE = 'Mxf8630101029cdb8a4d4e54000000000000008a434f494e5445535400008405f5e1001ba022d7d220afd17953ddfde047429a59ee5e646bb4003fabc4f78100920e1034eba008b90f864f500d043e2d59902a34f464a7d2077a95dcb655e51f857b7636cdf5';

    /**
     * Test to valid RLP encode data of MinterConvertCoinTx serialization
     */
    public function testSerialization(): void
    {
        $tx = new MinterConvertCoinTx(self::DATA);

        $this->assertSame($tx->serialize()->toString('hex'), self::VALID_RLP_ENCODED_DATA);
    }

    /**
     * Test to decode data for MinterConvertCoinTx
     */
    public function testDecode(): void
    {
        $tx = new MinterConvertCoinTx([
            implode(unpack("H*", self::DATA['coin_from'])),
            implode(unpack("H*", self::DATA['coin_to'])),
            dechex(self::DATA['value'])
        ], true);

        $this->assertSame($tx->coin_from, self::DATA['coin_from']);

        $this->assertSame($tx->coin_to, self::DATA['coin_to']);

        $this->assertSame($tx->value, self::DATA['value']);
    }

    /**
     * Test signing MinterConvertCoinTx
     */
    public function testSign(): void
    {
        $txData = new MinterConvertCoinTx(self::DATA);
        $tx = new MinterTx([
            'nonce' => 1,
            'gasPrice' => 1,
            'type' => 2,
            'data' => $txData->serialize()
        ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
