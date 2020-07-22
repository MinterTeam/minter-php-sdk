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
     * Test to decode data for MinterCreateMultisigTx
     */
    public function testDecode(): void
    {
        $tx = MinterTx::decode(self::VALID_SIGNATURE);
        $validTx = $this->makeTransaction();

        $this->assertSame($validTx->getNonce(), $tx->getNonce());
        $this->assertSame($validTx->getGasCoin(), $tx->getGasCoin());
        $this->assertSame($validTx->getGasPrice(), $tx->getGasPrice());
        $this->assertSame($validTx->getChainID(), $tx->getChainID());
        $this->assertSame($validTx->getData()->weights, $tx->getData()->weights);
        $this->assertSame($validTx->getData()->addresses, $tx->getData()->addresses);
        $this->assertSame($validTx->getData()->threshold, $tx->getData()->threshold);
        $this->assertSame(self::MINTER_ADDRESS, $tx->getSenderAddress());
    }

    /**
     * Test signing MinterCreateMultisigTx
     */
    public function testSign(): void
    {
        $signature = $this->makeTransaction()->sign(self::PRIVATE_KEY);
        $this->assertSame($signature, self::VALID_SIGNATURE);
    }

    /**
     * @return MinterTx
     */
    private function makeTransaction(): MinterTx
    {
        $data = new MinterCreateMultisigTx(3, [1, 2], ['Mx67691076548b20234461ff6fd2bc9c64393eb8fc', 'Mxc26dbd06984949a0efce1517925ca57a8d7a2c06']);
        return new MinterTx(8, $data);
    }
}
