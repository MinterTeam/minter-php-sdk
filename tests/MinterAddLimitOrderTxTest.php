<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterAddLimitOrderTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class MinterAddLimitOrderTxTest
 */
final class MinterAddLimitOrderTxTest extends TestCase
{
    /**
     * Predefined private key
     */
    const PRIVATE_KEY = '474ad4c6517502f3f939e276ae619f494d586a9b6cae81d63f8287dda0aabd4f';

    /**
     * Predefined minter address
     */
    const MINTER_ADDRESS = 'Mx4598fecf900b11ccffe991c51818be8479a46eee';

    /**
     * Predefined valid signature
     */
    const VALID_SIGNATURE = '0xf867010201802397d680888ac7230489e80000820731886124fee993bc0000808001b845f8431ba00f2af8b7fbe8fdb95d380f428a9fb96f8ba8e4a5f2d44ce04d91f1b8b03defbda0628c401818e3834ced421b42bd0ae956df4671f37f57a38529acf4b8541a2ea7';

    /**
     * Test to decode data for MinterAddLimitOrderTx
     */
    public function testDecode(): void
    {
        $tx      = MinterTx::decode(self::VALID_SIGNATURE);
        $validTx = $this->makeTransaction();

        $this->assertSame($validTx->getNonce(), $tx->getNonce());
        $this->assertSame($validTx->getGasCoin(), $tx->getGasCoin());
        $this->assertSame($validTx->getGasPrice(), $tx->getGasPrice());
        $this->assertSame($validTx->getChainID(), $tx->getChainID());
        $this->assertSame($validTx->getData()->coinToBuy, $tx->getData()->coinToBuy);
        $this->assertSame($validTx->getData()->coinToSell, $tx->getData()->coinToSell);
        $this->assertSame($validTx->getData()->valueToBuy, $tx->getData()->valueToBuy);
        $this->assertSame($validTx->getData()->valueToSell, $tx->getData()->valueToSell);
        $this->assertSame(self::MINTER_ADDRESS, $tx->getSenderAddress());
    }

    /**
     * Test signing MinterAddLimitOrderTx
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
        $data = new MinterAddLimitOrderTx(0, '10', 1841, '7');
        return (new MinterTx(1, $data))->setChainID(MinterTx::TESTNET_CHAIN_ID);
    }
}
