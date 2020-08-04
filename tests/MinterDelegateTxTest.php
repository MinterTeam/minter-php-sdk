<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterDelegateTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterDelegateTx
 */
final class MinterDelegateTxTest extends TestCase
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
    const VALID_SIGNATURE = '0xf87e0501018007aeeda00208f8a2bd535f65ecbe4b057b3b3c5fbfef6003b0713dc37b697b1d19153fe8808a021e19e0c9bab2400000808001b845f8431ba0b23e03cb8d8f87dc0716ce4a42f6fbad50c173562e29fc2ee4610691c6d131eda022593f96278c49319b28b4651201ae6ae8777a34739841ac55c40c3bcae96a07';

    /**
     * Predefined data
     */
    const DATA = [
        'pubkey' => 'Mp0208f8a2bd535f65ecbe4b057b3b3c5fbfef6003b0713dc37b697b1d19153fe8',
        'coin'   => 0,
        'stake'  => '10000'
    ];

    /**
     * Test to decode data for MinterDelegateTx
     */
    public function testDecode(): void
    {
        $tx = new MinterTx(self::VALID_SIGNATURE);

        $this->assertSame($tx->data, self::DATA);
    }

    /**
     * Test signing MinterDelegateTx
     */
    public function testSign(): void
    {
        $tx = new MinterTx([
           'nonce'   => 5,
           'chainId' => MinterTx::MAINNET_CHAIN_ID,
           'type'    => MinterDelegateTx::TYPE,
           'data'    => self::DATA,
       ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
