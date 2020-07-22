<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterSellCoinTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterSellCoinTx
 */
final class MinterSellCoinTxTest extends TestCase
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
    const VALID_SIGNATURE = '0xf864030101800294d380893635c9adc5dea00000018609184e72a000808001b845f8431ba036361e8cdfe662af2285c98fbeb9aa6af1037711fbe47f580777e14ed13575eaa062ff5ce42bec17732db635c85ccf101b4faad5abd9eb9730a78247d12fc1aa34';

    /**
     * Test to decode data for MinterSellCoinTx
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
        $this->assertSame($validTx->getData()->valueToSell, $tx->getData()->valueToSell);
        $this->assertSame(self::MINTER_ADDRESS, $tx->getSenderAddress());
    }

    /**
     * Test signing MinterSellCoinTx
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
        return new MinterTx(3, new MinterSellCoinTx(0, '1000', 1, '0.00001'));
    }
}
