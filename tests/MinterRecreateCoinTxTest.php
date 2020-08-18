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
    const VALID_SIGNATURE = '0xf8830201018010b3f284746573748a535550455254455354338a021e19e0c9bab24000008a021e19e0c9bab2400000638a021e27c1806e59a40000808001b845f8431ba0de755c731ccf5c0c131fc26c0eb69298f5cfa53829176ae6725abbba186a7129a00bb33f079db6571918ed7502b1a153c7722f5ce5f011c412ee17d0ec024d5308';

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
        $data = new MinterRecreateCoinTx('test', 'SUPERTEST3', '10000', '10000', 99, '10001');
        return new MinterTx(2, $data);
    }
}
