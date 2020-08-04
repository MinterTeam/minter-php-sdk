<?php
declare(strict_types=1);

use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterSetCandidateOffTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterSetCandidateOffTx
 */
final class MinterSetCandidateOffTxTest extends TestCase
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
    const VALID_SIGNATURE = '0xf8720e0101800ba2e1a00208f8a2bd535f65ecbe4b057b3b3c5fbfef6003b0713dc37b697b1d19153fe0808001b845f8431ba024c5fc033d9d6ad2bfecb37680db84a897839813fd0ad5583254b69180954fb9a022728b74156536acc44c3ef572cc20947a1a015c94b13e36068e1089c95d025b';


    /**
     * Predefined data
     */
    const DATA = [
        'pubkey' => 'Mp0208f8a2bd535f65ecbe4b057b3b3c5fbfef6003b0713dc37b697b1d19153fe0'
    ];

    /**
     * Test to decode data for MinterSetCandidateOffTx
     */
    public function testDecode(): void
    {
        $tx = new MinterTx(self::VALID_SIGNATURE);

        $this->assertSame($tx->data, self::DATA);
    }

    /**
     * Test signing MinterSetCandidateOnTx
     */
    public function testSign(): void
    {
        $tx = new MinterTx([
           'nonce'   => 14,
           'chainId' => MinterTx::MAINNET_CHAIN_ID,
           'type'    => MinterSetCandidateOffTx::TYPE,
           'data'    => self::DATA
       ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
