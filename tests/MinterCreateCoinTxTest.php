<?php
declare(strict_types=1);

use Minter\SDK\MinterCreateCoinTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterCreateCoinTx
 */
final class MinterCreateCoinTxTest extends TestCase
{
    /**
     * Predefined private key
     */
    const PRIVATE_KEY = '418e4be028dcaed85aa58b643979f644f806a42bb6d1912848720788a53bb8a4';

    /**
     * Predefined data
     */
    const DATA = [
        'name' => 'TEST COIN',
        'symbol' => 'TEST',
        'initialAmount' => 100,
        'initialReserve' => 10,
        'crr' => 10
    ];

    /**
     * Predefined valid RLP encoded data of self::DATA
     */
    const VALID_RLP_ENCODED_DATA = 'd8895445535420434f494e8a54455354000000000000640a0a';

    /**
     * Predefined valid signature
     */
    const VALID_SIGNATURE = 'Mxf86001010399d8895445535420434f494e8a54455354000000000000640a0a1ba025120bc80add1e7456ec6e09d62d1fd0f2bdf6694f1dc219d72d973407bf6cc4a0602bb53187957e804467387d33c282f052e7d0141c4271bd57bc5ad6c68290b1';

    /**
     * Test to valid RLP encode data of MinterCreateCoinTx serialization
     */
    public function testSerialization(): void
    {
        $tx = new MinterCreateCoinTx(self::DATA);

        $this->assertSame($tx->serialize()->toString('hex'), self::VALID_RLP_ENCODED_DATA);
    }

    /**
     * Test to decode data for MinterCreateCoinTx
     */
    public function testDecode(): void
    {
        $tx = new MinterCreateCoinTx([
            implode(unpack("H*", self::DATA['name'])),
            implode(unpack("H*", self::DATA['symbol'])),
            dechex(self::DATA['initialAmount']),
            dechex(self::DATA['initialReserve']),
            dechex(self::DATA['crr'])
        ], true);

        $this->assertSame($tx->name, self::DATA['name']);

        $this->assertSame($tx->symbol, self::DATA['symbol']);

        $this->assertSame($tx->initialAmount, self::DATA['initialAmount']);

        $this->assertSame($tx->initialReserve, self::DATA['initialReserve']);

        $this->assertSame($tx->crr, self::DATA['crr']);
    }

    /**
     * Test signing MinterCreateCoinTx
     */
    public function testSign(): void
    {
        $txData = new MinterCreateCoinTx(self::DATA);
        $tx = new MinterTx([
            'nonce' => 1,
            'gasPrice' => 1,
            'type' => 3,
            'data' => $txData->serialize()
        ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
