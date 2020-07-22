<?php
declare(strict_types=1);

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
    const VALID_SIGNATURE = '0xf87e0a01018010aeed8a535550455254455354318a021e19e0c9bab24000008a021e19e0c9bab2400000638a021e27c1806e59a40000808001b845f8431ba084a67a9c402533e296c656ea82e29d3cc2a2cd4c978944978328cb3afae9cae4a018e444ff0eb3b343c940aa74125f9513454d0b9fc50b8f21dc285fb52767a0f6';

    /**
     * Test to decode data for MinterRecreateCoinTx
     */
    public function testDecode(): void
    {
        $tx = MinterTx::decode(self::VALID_SIGNATURE);
        $validTx = $this->makeTransaction();

        $this->assertSame($validTx->getNonce(), $tx->getNonce());
        $this->assertSame($validTx->getGasCoin(), $tx->getGasCoin());
        $this->assertSame($validTx->getGasPrice(), $tx->getGasPrice());
        $this->assertSame($validTx->getChainID(), $tx->getChainID());
        $this->assertSame($validTx->getData()->symbol, $tx->getData()->symbol);
        $this->assertSame($validTx->getData()->amount, $tx->getData()->amount);
        $this->assertSame($validTx->getData()->reserve, $tx->getData()->reserve);
        $this->assertSame($validTx->getData()->crr, $tx->getData()->crr);
        $this->assertSame($validTx->getData()->maxSupply, $tx->getData()->maxSupply);
        $this->assertSame(self::MINTER_ADDRESS, $tx->getSenderAddress());
    }

    /**
     * Test signing MinterRecreateCoinTx
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
        $data = new MinterRecreateCoinTx('SUPERTEST1', '10000', '10000', 99, '10001');
        return new MinterTx(10, $data);
    }
}
