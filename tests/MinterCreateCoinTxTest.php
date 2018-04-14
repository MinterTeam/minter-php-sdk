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
     * Predefined minter address
     */
    const MINTER_ADDRESS = 'Mxfe60014a6e9ac91618f5d1cab3fd58cded61ee99';

    /**
     * Predefined data
     */
    const DATA = [
        'name' => 'SUPER TEST',
        'symbol' => 'SPRTEST',
        'initialAmount' => 100,
        'initialReserve' => 10,
        'crr' => 10
    ];

    /**
     * Predefined valid tx for decoding
     */
    const VALID_TX = '+GoFAQOj4opTVVBFUiBURVNUilNQUlRFU1QAAACFAlQL5ACEO5rKAAoboCTrfCCQh2alEat8p3G+ymfRu8c6Yvy5Tf0Cklftds+goHuPSqp0aOnCu7obIqvyWtS0isOmN3NTDmQmY9HcTf8k';

    /**
     * Predefined valid signature
     */
    const VALID_SIGNATURE = 'Mxf86a010103a3e28a535550455220544553548a535052544553540000008502540be400843b9aca000a1ca026844b7ace1552e362382ffcde669de1e2e0375f90a77bb488e8d5377e84ce5aa0183cc3f57d73aac3adf01cc74cb52cd5eb77bedeefcddfb18381feec7292b5c4';

    /**
     * Test to decode data for MinterCreateCoinTx
     */
    public function testDecode(): void
    {
        $tx = new MinterTx(self::VALID_TX);

        $this->assertSame($tx->data, self::DATA);
        $this->assertSame($tx->from, self::MINTER_ADDRESS);
    }

    /**
     * Test signing MinterCreateCoinTx
     */
    public function testSign(): void
    {
        $tx = new MinterTx([
            'nonce' => 1,
            'gasPrice' => 1,
            'type' => MinterCreateCoinTx::TYPE,
            'data' => self::DATA
        ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
