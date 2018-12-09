<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterMultiSendTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterMultiSendTxTest
 */
final class MinterMultiSendTxTest extends TestCase
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
        'list' => [
            [
                'coin' => 'MNT',
                'to' => 'Mxfe60014a6e9ac91618f5d1cab3fd58cded61ee99',
                'value' => '0.1'
            ], [
                'coin' => 'MNT',
                'to' => 'Mxddab6281766ad86497741ff91b6b48fe85012e3c',
                'value' => '0.2'
            ]
        ]
    ];

    /**
     * Predefined valid signature
     */
    const VALID_SIGNATURE = '0xf8b201018a4d4e54000000000000000db858f856f854e98a4d4e540000000000000094fe60014a6e9ac91618f5d1cab3fd58cded61ee9988016345785d8a0000e98a4d4e540000000000000094ddab6281766ad86497741ff91b6b48fe85012e3c8802c68af0bb140000808001b845f8431ca04d3814bd63cd7032786c18e4ca5b5235451d863034921315f061357779254d26a027b701feb7d00b689e06a364b159d46eb2143bed78aadbfafed798b5d1055dc0';

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
            'type' => MinterMultiSendTx::TYPE,
            'data' => self::DATA,
            'payload' => '',
            'serviceData' => '',
            'signatureType' => MinterTx::SIGNATURE_SINGLE_TYPE
        ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
