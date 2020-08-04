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
     * Predefined data
     */
    const DATA = [
        'coinToSell'        => 1,
        'coinToBuy'         => 0,
        'minimumValueToBuy' => '0.123'
    ];

    /**
     * Test to decode data for MinterSendCoinTx
     */
    public function testDecode(): void
    {
        $tx = new MinterTx(self::VALID_SIGNATURE);

        $this->assertSame($tx->data, self::DATA);
        $this->assertSame($tx->from, self::MINTER_ADDRESS);
    }

    /**
     * Test signing MinterSendCoinTx
     */
    public function testSign(): void
    {
        $tx = new MinterTx([
           'nonce'    => 4,
           'chainId'  => MinterTx::MAINNET_CHAIN_ID,
           'gasPrice' => 1,
           'type'     => MinterSellAllCoinTx::TYPE,
           'data'     => self::DATA
       ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
