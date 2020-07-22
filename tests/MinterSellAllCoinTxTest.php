<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterSellAllCoinTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterSellAllCoinTx
 */
final class MinterSellAllCoinTxTest extends TestCase
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
    const VALID_SIGNATURE = '0xf85c04010180038ccb01808801b4fbd92b5f8000808001b845f8431ba0c3a668f479a9a9ee25bc98915877e50b5b91fd38ae53a17142b85919dc9f0baba040617eccdc0b28bc8b182ae9d6cb1d1935358973cf48ebf012c0284ed2898ff9';

    /**
     * Test to decode data for MinterSellAllCoinTx
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
     * Test signing MinterSellAllCoinTx
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
        return new MinterTx(4, new MinterSellAllCoinTx(1, 0, '0.123'));
    }
}
