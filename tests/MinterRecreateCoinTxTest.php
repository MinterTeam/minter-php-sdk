<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterCreateCoinTx;
use Minter\SDK\MinterCoins\MinterRecreateCoinTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterRecreateCoinTx
 */
final class MinterRecreateCoinTxTest extends TestCase
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
    const VALID_SIGNATURE = '0xf88b0901018010b83af8388a535550455220544553548a535550455254455354318a021e19e0c9bab24000008a021e19e0c9bab2400000638a021e27c1806e59a40000808001b845f8431ba096aa8fb9e884dd6c30320ed17e5c5ffbd0cc918fa14199004a493bea42b3e1c6a0156596e592a56d292688247be1a2f8c9ff8eec22173ef864fa15e8d13dd72cb4';

    /**
     * Predefined data
     */
    const DATA = [
        'name'           => 'SUPER TEST',
        'symbol'         => 'SUPERTEST1',
        'initialAmount'  => '10000',
        'initialReserve' => '10000',
        'crr'            => 99,
        'maxSupply'      => '10001'
    ];

    /**
     * Test to decode data for MinterRecreateCoinTx
     */
    public function testDecode(): void
    {
        $tx = new MinterTx(self::VALID_SIGNATURE);

        $this->assertSame($tx->data, self::DATA);
        $this->assertSame($tx->from, self::MINTER_ADDRESS);
    }

    /**
     * Test signing MinterRecreateCoinTx
     */
    public function testSign(): void
    {
        $tx = new MinterTx([
           'nonce'   => 9,
           'chainId' => MinterTx::MAINNET_CHAIN_ID,
           'type'    => MinterRecreateCoinTx::TYPE,
           'data'    => self::DATA,
       ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
