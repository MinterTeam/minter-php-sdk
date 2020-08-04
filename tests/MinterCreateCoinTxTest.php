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
    const PRIVATE_KEY = '4daf02f92bf760b53d3c725d6bcc0da8e55d27ba5350c78d3a88f873e502bd6e';

    /**
     * Predefined minter address
     */
    const MINTER_ADDRESS = 'Mx67691076548b20234461ff6fd2bc9c64393eb8fc';

    /**
     * Predefined valid signature
     */
    const VALID_SIGNATURE = '0xf88b0901018005b83af8388a535550455220544553548a535550455254455354318a021e19e0c9bab24000008a021e19e0c9bab2400000638a021e27c1806e59a40000808001b845f8431ba03c4678e9549256b9413827dc617de9b054b3c02ea72eb5b99d038ad49c600dcca02c54da56153d766ed1c9bc1917d82b6c56029e9f889e4d0d1e945eafeca9991b';

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
           'nonce'   => 9,
           'chainId' => MinterTx::MAINNET_CHAIN_ID,
           'type'    => MinterCreateCoinTx::TYPE,
           'data'    => self::DATA,
       ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
