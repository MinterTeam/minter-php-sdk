<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterBuyCoinTx;
use Minter\SDK\MinterCoins\MinterSetHaltBlockTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterSetHaltBlockTx
 */
final class MinterSetHaltBlockTxTest extends TestCase
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
        'pubkey' => 'Mp0208f8a2bd535f65ecbe4b057b3b3c5fbfef6003b0713dc37b697b1d19153fe8',
        'height' => 123,
    ];

    /**
     * Predefined valid signature
     */
    const VALID_SIGNATURE = '0xf873020101800fa3e2a00208f8a2bd535f65ecbe4b057b3b3c5fbfef6003b0713dc37b697b1d19153fe87b808001b845f8431ba0d48744fee3dbcabca03d495c4dffe57a67e8e44b547812d6d72e26f0322d3928a0322d7276f56b4cda3ab6c586a27edb5af01b011e313c0c8e2996b6a8e0f3397c';

    /**
     * Test to decode data for MinterSetHaltBlockTx
     */
    public function testDecode(): void
    {
        $tx = new MinterTx(self::VALID_SIGNATURE);

        $this->assertSame($tx->data, self::DATA);
        $this->assertSame($tx->from, self::MINTER_ADDRESS);
    }

    /**
     * Test signing MinterSetHaltBlockTx
     */
    public function testSign(): void
    {
        $tx = new MinterTx([
           'nonce'   => 2,
           'chainId' => MinterTx::MAINNET_CHAIN_ID,
           'type'    => MinterSetHaltBlockTx::TYPE,
           'data'    => self::DATA
       ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
