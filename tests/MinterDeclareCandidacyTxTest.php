<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterDeclareCandidacyTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterDeclareCandidacyTx
 */
final class MinterDeclareCandidacyTxTest extends TestCase
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
    const VALID_SIGNATURE = '0xf8960c01018006b845f8439467691076548b20234461ff6fd2bc9c64393eb8fca00208f8a2bd535f65ecbe4b057b3b3c5fbfef6003b0713dc37b697b1d19153fe00a808a021e19e0c9bab2400000808001b845f8431ba0997dfdb7b07c38bcb9fba27a6a65e20a087f79642c34d9f7b2ff41a7f83457f1a0476e4605a29757715835d6a1d1e20215e3987f0d96a864d63ff84978246dc476';

    /**
     * Predefined data
     */
    const DATA = [
        'address'    => 'Mx67691076548b20234461ff6fd2bc9c64393eb8fc',
        'pubkey'     => 'Mp0208f8a2bd535f65ecbe4b057b3b3c5fbfef6003b0713dc37b697b1d19153fe0',
        'commission' => '10',
        'coin'       => 0,
        'stake'      => '10000'
    ];

    /**
     * Test to decode data for MinterDeclareCandidacyTx
     */
    public function testDecode(): void
    {
        $tx = new MinterTx(self::VALID_SIGNATURE);

        $this->assertSame($tx->data, self::DATA);
    }

    /**
     * Test signing MinterDeclareCandidacyTx
     */
    public function testSign(): void
    {
        $tx = new MinterTx([
           'nonce'   => 12,
           'chainId' => MinterTx::MAINNET_CHAIN_ID,
           'type'    => MinterDeclareCandidacyTx::TYPE,
           'data'    => self::DATA,
       ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
