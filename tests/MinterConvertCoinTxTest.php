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
     * Predefined minter address
     */
    const MINTER_ADDRESS = 'Mxfe60014a6e9ac91618f5d1cab3fd58cded61ee99';

    /**
     * Predefined data
     */
    const DATA = [
        'coin_from' => 'MNT',
        'coin_to' => 'SPRTEST',
        'value' => 1
    ];

    /**
     * Predefined valid tx for decoding
     */
    const VALID_TX = '+GMGAQKc24pNTlQAAAAAAAAAilNQUlRFU1QAAACEBfXhABygvrRyY+ab1rsP8/TFTuzJz6EU3WD7g1lIKVhN5NA7AO6gUhGf5IQKWYN4TkVe3zwMMT5Qyx1O2qqD9LblVU/dI78=';

    /**
     * Predefined valid signature
     */
    const VALID_SIGNATURE = 'Mxf8630601029cdb8a4d4e54000000000000008a535052544553540000008405f5e1001ca0beb47263e69bd6bb0ff3f4c54eecc9cfa114dd60fb83594829584de4d03b00eea052119fe4840a5983784e455edf3c0c313e50cb1d4edaaa83f4b6e5554fdd23bf';

    /**
     * Test to decode data for MinterConvertCoinTx
     */
    public function testDecode(): void
    {
        $tx = new MinterTx(self::VALID_TX);

        $this->assertSame($tx->data, self::DATA);
        $this->assertSame($tx->from, self::MINTER_ADDRESS);
    }

    /**
     * Test signing MinterConvertCoinTx
     */
    public function testSign(): void
    {
        $tx = new MinterTx([
            'nonce' => 6,
            'gasPrice' => 1,
            'type' => MinterConvertCoinTx::TYPE,
            'data' => self::DATA
        ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
