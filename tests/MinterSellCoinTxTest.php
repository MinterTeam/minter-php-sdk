<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterSellCoinTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterSendCoinTx
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
     * Predefined data
     */
    const DATA = [
        'coinToSell'        => 0,
        'valueToSell'       => '1000',
        'coinToBuy'         => 1,
        'minimumValueToBuy' => '0.00001'
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
           'nonce'    => 3,
           'chainId'  => MinterTx::MAINNET_CHAIN_ID,
           'gasPrice' => 1,
           'type'     => MinterSellCoinTx::TYPE,
           'data'     => self::DATA
       ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
