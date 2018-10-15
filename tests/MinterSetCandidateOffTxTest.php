<?php
declare(strict_types=1);

use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterSetCandidateOffTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterSetCandidateOffTx
 */
final class MinterSetCandidateOffTxTest extends TestCase
{
    /**
     * Predefined private key
     */
    const PRIVATE_KEY = '05ddcd4e6f7d248ed1388f0091fe345bf9bf4fc2390384e26005e7675c98b3c1';

    /**
     * Predefined data
     */
    const DATA = [
        'pubkey' => 'Mp0eb98ea04ae466d8d38f490db3c99b3996a90e24243952ce9822c6dc1e2c1a43'
    ];

    /**
     * Predefined valid signature
     */
    const VALID_SIGNATURE = 'f87b01018a4d4e54000000000000000ba2e1a00eb98ea04ae466d8d38f490db3c99b3996a90e24243952ce9822c6dc1e2c1a43808001b845f8431ba00ec72ffccb429fff30108216641d413f4e9b07de839146912f9ec1b28c98d0b8a022b5e68a830aad449be5fbcf295f99577b7b2423ee49c956570c3d0b48a2c1c4';

    /**
     * Test to decode data for MinterSetCandidateOffTx
     */
    public function testDecode(): void
    {
        $tx = new MinterTx(self::VALID_SIGNATURE);

        $this->assertSame($tx->data, self::DATA);
    }

    /**
     * Test signing MinterSetCandidateOnTx
     */
    public function testSign(): void
    {
        $tx = new MinterTx([
            'nonce' => 1,
            'gasPrice' => 1,
            'gasCoin' => 'MNT',
            'type' => MinterSetCandidateOffTx::TYPE,
            'data' => self::DATA,
            'payload' => '',
            'serviceData' => '',
            'signatureType' => MinterTx::SIGNATURE_SINGLE_TYPE
        ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
