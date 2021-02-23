<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterSellAllSwapPoolTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

class MinterSellAllSwapPoolTxTest extends TestCase
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
    const VALID_SIGNATURE = '0xf85f10020180198fcec301040589056bc75e2d63100000808001b845f8431ca095651bdd2afa8964213f7bc064898b0edeb67fa39f3a0c71b52934f6463412afa0604ced9ff973bf1de6b252c9582982780cf1de4fd2b2e3814bfa8185d10bd6f5';

    /**
     * Test to decode data for MinterSellAllSwapPoolTx
     */
    public function testDecode(): void
    {
        $tx      = MinterTx::decode(self::VALID_SIGNATURE);
        $validTx = $this->makeTransaction();

        $this->assertSame($validTx->getNonce(), $tx->getNonce());
        $this->assertSame($validTx->getGasCoin(), $tx->getGasCoin());
        $this->assertSame($validTx->getGasPrice(), $tx->getGasPrice());
        $this->assertSame($validTx->getChainID(), $tx->getChainID());
        $this->assertSame($validTx->getData()->coins, $tx->getData()->coins);
        $this->assertSame($validTx->getData()->minimumValueToBuy, $tx->getData()->minimumValueToBuy);
        $this->assertSame(self::MINTER_ADDRESS, $tx->getSenderAddress());
    }

    /**
     * Test signing MinterSellAllSwapPoolTx
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
        $data = new MinterSellAllSwapPoolTx([1, 4, 5], '100');
        return (new MinterTx(16, $data))->setChainID(MinterTx::TESTNET_CHAIN_ID);
    }
}