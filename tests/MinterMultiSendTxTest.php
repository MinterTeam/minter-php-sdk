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
    const PRIVATE_KEY = '4daf02f92bf760b53d3c725d6bcc0da8e55d27ba5350c78d3a88f873e502bd6e';

    /**
     * Predefined minter address
     */
    const MINTER_ADDRESS = 'Mx67691076548b20234461ff6fd2bc9c64393eb8fc';

    /**
     * Predefined valid signature
     */
    const VALID_SIGNATURE = '0xf895060101800db844f842f840df809467691076548b20234461ff6fd2bc9c64393eb8fc8801b4fbd92b5f8000df8094d82558ea00eb81d35f2654953598f5d51737d31d8804746bcc9ce68000808001b845f8431ba0a936ac922d8d67f06efc996f50f3d2af55a77453f521bc96d73158de16b530baa0192f5d1f2feb520b38d92513ed89fc1ede26353ce3660502f61721ea6232b261';

    /**
     * Predefined data
     */
    const DATA = [
        'list' => [
            [
                'coin'  => 0,
                'to'    => 'Mx67691076548b20234461ff6fd2bc9c64393eb8fc',
                'value' => '0.123'
            ],
            [
                'coin'  => 0,
                'to'    => 'Mxd82558ea00eb81d35f2654953598f5d51737d31d',
                'value' => '0.321'
            ]
        ]
    ];

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
           'nonce'         => 6,
           'chainId'       => MinterTx::MAINNET_CHAIN_ID,
           'type'          => MinterMultiSendTx::TYPE,
           'data'          => self::DATA,
       ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
