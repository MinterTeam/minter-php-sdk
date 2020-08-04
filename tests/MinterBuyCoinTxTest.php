<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterBuyCoinTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterSendCoinTx
 */
final class MinterBuyCoinTxTest extends TestCase
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
     * Predefined data
     */
    const DATA = [
        'coinToBuy'          => '1',
        'valueToBuy'         => '0.001',
        'coinToSell'         => '0',
        'maximumValueToSell' => '100'
    ];

    /**
     * Predefined valid signature
     */
    const VALID_SIGNATURE = '0xf865020101800495d40187038d7ea4c680008089056bc75e2d63100000808001b845f8431ca0f64de1594ea6ea7717a2161771a429a2202e78ae4f1bf628a8c2e12a2df13e4aa04b8eb64ef9e7574983cc66960e98829fd93ab61fd2d7794c3e8810970e9e3693';

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
                               'nonce'   => 2,
                               'chainId' => MinterTx::MAINNET_CHAIN_ID,
                               'gasCoin' => 0,
                               'type'    => MinterBuyCoinTx::TYPE,
                               'data'    => self::DATA
                           ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
