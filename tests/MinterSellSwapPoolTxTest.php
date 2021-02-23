<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterSellSwapPoolTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

final class MinterSellSwapPoolTxTest extends TestCase
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
    const VALID_SIGNATURE = '0xf867050201801797d6c201028901158e460913d00000881bc16d674ec80000808001b845f8431ba01ba1d7c9c05993e6d92d4db8cb24b32c9a28f61286464e41ffcaf86551f97a79a042dcd34363bd981676541b8e8f88b8bbbfcbf0746db2017be73f378afbf045c2';

    /**
     * Test to decode data for MinterSellSwapPoolTx
     */
    public function testDecode(): void
    {
        $tx = MinterTx::decode(self::VALID_SIGNATURE);
        $validTx = $this->makeTransaction();

        $this->assertSame($validTx->getNonce(), $tx->getNonce());
        $this->assertSame($validTx->getGasCoin(), $tx->getGasCoin());
        $this->assertSame($validTx->getGasPrice(), $tx->getGasPrice());
        $this->assertSame($validTx->getChainID(), $tx->getChainID());
        $this->assertSame($validTx->getData()->coins, $tx->getData()->coins);
        $this->assertSame($validTx->getData()->valueToSell, $tx->getData()->valueToSell);
        $this->assertSame($validTx->getData()->minimumValueToBuy, $tx->getData()->minimumValueToBuy);
        $this->assertSame(self::MINTER_ADDRESS, $tx->getSenderAddress());
    }

    /**
     * Test signing MinterSellSwapPoolTx
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
        $data = new MinterSellSwapPoolTx([1, 2], '20',  '2');
        return (new MinterTx(5, $data))->setChainID(MinterTx::TESTNET_CHAIN_ID);
    }
}