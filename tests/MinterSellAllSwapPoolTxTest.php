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
    const VALID_SIGNATURE = '0xf85d10020180198dcc018089056bc75e2d63100000808001b845f8431ca0e50537db70ed7263094cfaee22c69545c0c41e9efedef521c0ecce3a4f33ade6a03e71cde08a52561136584bc14707b3c01e618513b01a96b9865b44ff0395fc7a';

    /**
     * Test to decode data for MinterSellAllSwapPoolTx
     */
    public function testDecode(): void
    {
        $tx = MinterTx::decode(self::VALID_SIGNATURE);
        $validTx = $this->makeTransaction();

        $this->assertSame($validTx->getNonce(), $tx->getNonce());
        $this->assertSame($validTx->getGasCoin(), $tx->getGasCoin());
        $this->assertSame($validTx->getGasPrice(), $tx->getGasPrice());
        $this->assertSame($validTx->getChainID(), $tx->getChainID());
        $this->assertSame($validTx->getData()->coinToBuy, $tx->getData()->coinToBuy);
        $this->assertSame($validTx->getData()->coinToSell, $tx->getData()->coinToSell);
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
        $data = new MinterSellAllSwapPoolTx(1, 0, '100');
        return (new MinterTx(16, $data))->setChainID(MinterTx::TESTNET_CHAIN_ID);
    }
}