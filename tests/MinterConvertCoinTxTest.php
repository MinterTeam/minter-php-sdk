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
    const PRIVATE_KEY = 'b574d2a7151fcf0df573feae58015f85f6ebf38ea4b38c49196c6aceee27e189';

    /**
     * Predefined minter address
     */
    const MINTER_ADDRESS = 'Mx887c5de2515e788abb422c3e483496e1b1f3dff4';

    /**
     * Predefined data
     */
        const DATA = [
        'coin_from' => 'MNT',
        'coin_to' => 'SPRTEST',
        'value' => '1'
    ];

    /**
     * Predefined valid signature
     */
    const VALID_SIGNATURE = 'Mxf8640601029cdb8a4d4e54000000000000008a535052544553540000008405f5e100801ca09f86537b2c997306d7b4df2a3db0615f1b6377b1cdb7efbcd0b5d53fa70e9bcda048c05469026e213703a29ae8cda53e41d4fff7e9e454ff95017bdf157c3ef384';

    /**
     * Test to decode data for MinterConvertCoinTx
     */
    public function testDecode(): void
    {
        $tx = new MinterTx(self::VALID_SIGNATURE);

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
            'data' => self::DATA,
            'payload' => ''
        ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
