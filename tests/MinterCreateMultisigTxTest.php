<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterCreateMultisigTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class MinterCreateMultisigTx
 */
final class MinterCreateMultisigTxTest extends TestCase
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
    const VALID_SIGNATURE = '0xf880080101800cb0ef03c20102ea9467691076548b20234461ff6fd2bc9c64393eb8fc94c26dbd06984949a0efce1517925ca57a8d7a2c06808001b845f8431ba077b3ac0b0605279239bdcec12a698f7beb2c5d9d213c2cdc90638b3da020bbeaa021f4a509eaa7e93bc77901de3061d98e092c9ce1c414ad779a92804aedf4eb97';

    /**
     * Predefined data
     */
    const DATA = [
        'threshold' => 3,
        'weights'   => [1, 2],
        'addresses' => [
            'Mx67691076548b20234461ff6fd2bc9c64393eb8fc',
            'Mxc26dbd06984949a0efce1517925ca57a8d7a2c06'
        ]
    ];

    /**
     * Test to decode data for MinterCreateMultisigTx
     */
    public function testDecode(): void
    {
        $tx = new MinterTx(self::VALID_SIGNATURE);

        $this->assertSame($tx->data, self::DATA);
        $this->assertSame($tx->from, self::MINTER_ADDRESS);
    }

    /**
     * Test signing MinterCreateMultisigTx
     */
    public function testSign(): void
    {
        $tx = new MinterTx([
           'nonce'   => 8,
           'chainId' => MinterTx::MAINNET_CHAIN_ID,
           'type'    => MinterCreateMultisigTx::TYPE,
           'data'    => self::DATA
       ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
