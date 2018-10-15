<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterCreateCoinTx;
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
    const PRIVATE_KEY = '07bc17abdcee8b971bb8723e36fe9d2523306d5ab2d683631693238e0f9df142';

    /**
     * Predefined minter address
     */
    const MINTER_ADDRESS = 'Mx31e61a05adbd13c6b625262704bc305bf7725026';

    /**
     * Predefined data
     */
    const DATA = [
        'name' => 'SUPER TEST',
        'symbol' => 'SPRTEST',
        'initialAmount' => '100',
        'initialReserve' => '10',
        'crr' => 10
    ];

    /**
     * Predefined valid signature
     */

    const VALID_SIGNATURE = 'f88401018a4d4e540000000000000005abea8a535550455220544553548a5350525445535400000089056bc75e2d63100000888ac7230489e800000a808001b845f8431ca0cab62b0670de21a16df3bc11af2553964c9fc5ae18b2adaa43d43f826bc143eea014e564991ab69f41a325fb90022ef3556921a6757c3b69d487051dde11c5d84a';

    /**
     * Test to decode data for MinterCreateCoinTx
     */
    public function testDecode(): void
    {
        $tx = new MinterTx(self::VALID_SIGNATURE);

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
            'gasCoin' => 'MNT',
            'type' => MinterCreateCoinTx::TYPE,
            'data' => self::DATA,
            'payload' => '',
            'serviceData' => '',
            'signatureType' => MinterTx::SIGNATURE_SINGLE_TYPE
        ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
