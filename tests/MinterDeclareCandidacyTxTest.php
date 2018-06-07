<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterDeclareCandidacyTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterDeclareCandidacyTx
 */
final class MinterDeclareCandidacyTxTest extends TestCase
{
    /**
     * Predefined private key
     */
    const PRIVATE_KEY = '05ddcd4e6f7d248ed1388f0091fe345bf9bf4fc2390384e26005e7675c98b3c1';

    /**
     * Predefined data
     */
    const DATA = [
        'address' => 'Mxa7bc33954f1ce855ed1a8c768fdd32ed927def47',
        'pubkey' => 'Mx023853f15fc1b1073ad7a1a0d4490a3b1fadfac00f36039b6651bc4c7f52ba9c02',
        'commission' => '10',
        'stake' => '5'
    ];

    /**
     * Predefined valid signature
     */
    const VALID_SIGNATURE = 'Mxf88d010104b843f84194a7bc33954f1ce855ed1a8c768fdd32ed927def47a1023853f15fc1b1073ad7a1a0d4490a3b1fadfac00f36039b6651bc4c7f52ba9c020a884563918244f4000080801ca05ec1cba9ebd9fad0c46a64b1302e3064e2e2581c894c0d3a2691d068cd28399ea06665ed86a76020934bd9a4f85d21f2e9c01ab1900dc6cfd378b35b38d743f35a';

    /**
     * Test to decode data for MinterDeclareCandidacyTx
     */
    public function testDecode(): void
    {
        $tx = new MinterTx(self::VALID_SIGNATURE);

        $this->assertSame($tx->data, self::DATA);
    }

    /**
     * Test signing MinterDeclareCandidacyTx
     */
    public function testSign(): void
    {
        $tx = new MinterTx([
            'nonce' => 1,
            'gasPrice' => 1,
            'type' => MinterDeclareCandidacyTx::TYPE,
            'data' => self::DATA,
            'payload' => '',
            'serviceData' => ''
        ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
