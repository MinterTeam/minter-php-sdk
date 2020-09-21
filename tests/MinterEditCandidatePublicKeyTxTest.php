<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterEditCandidatePublicKeyTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterEditCandidatePublicKeyTx
 */
final class MinterEditCandidatePublicKeyTxTest extends TestCase
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
    const VALID_SIGNATURE = '0xf8720101018014a2e1a00208f8a2bd535f65ecbe4b057b3b3c5fbfef6003b0713dc37b697b1d19153fe0808001b845f8431ba082816a6e2d8bfd9e6c0c9a583b78828916802c2e8fbc7e21369a5360c3226059a049d0421a599e2672c766034f511b67d9cd73ffd9b3cf81ab763d463e3695a650';

    const DATA = [
        'publicKey' => 'Mp0208f8a2bd535f65ecbe4b057b3b3c5fbfef6003b0713dc37b697b1d19153fe0'
    ];

    /**
     * Test to decode data for MinterEditCandidatePublicKeyTx
     */
    public function testDecode(): void
    {
        $tx      = new MinterTx(self::VALID_SIGNATURE);
        $this->assertSame(self::MINTER_ADDRESS, $tx->from);
    }

    /**
     * Test signing MinterEditCandidateTx
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
        return new MinterTx([
            'nonce' => 1,
            'chainId' => MinterTx::MAINNET_CHAIN_ID,
            'type'  => MinterEditCandidatePublicKeyTx::TYPE,
            'data' => self::DATA
        ]);
    }
}