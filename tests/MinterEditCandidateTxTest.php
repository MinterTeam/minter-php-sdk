<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterEditCandidateTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterEditCandidateTx
 */
final class MinterEditCandidateTxTest extends TestCase
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
    const VALID_SIGNATURE = '0xf8d4100101800eb883f881a00208f8a2bd535f65ecbe4b057b3b3c5fbfef6003b0713dc37b697b1d19153fe0a00208f8a2bd535f65ecbe4b057b3b3c5fbfef6003b0713dc37b697b1d19153fe194d82558ea00eb81d35f2654953598f5d51737d31d94d82558ea00eb81d35f2654953598f5d51737d31d94d82558ea00eb81d35f2654953598f5d51737d31d808001b845f8431ca06a9ce263674f403e2e612ac7055933c662db6c2db199635de985a69b9c0032baa03f4e2cd2bb33f89a10d4fdd4024f1767bfa94e87da47e075b4d5cbcaf519f66b';

    /**
     * Predefined data
     */
    const DATA = [
        'pubkey'          => 'Mp0208f8a2bd535f65ecbe4b057b3b3c5fbfef6003b0713dc37b697b1d19153fe0',
        'new_pubkey'      => 'Mp0208f8a2bd535f65ecbe4b057b3b3c5fbfef6003b0713dc37b697b1d19153fe1',
        'reward_address'  => 'Mxd82558ea00eb81d35f2654953598f5d51737d31d',
        'owner_address'   => 'Mxd82558ea00eb81d35f2654953598f5d51737d31d',
        'control_address' => 'Mxd82558ea00eb81d35f2654953598f5d51737d31d'
    ];

    /**
     * Test to decode data for MinterEditCandidateTx
     */
    public function testDecode(): void
    {
        $tx = new MinterTx(self::VALID_SIGNATURE);

        $this->assertSame($tx->data, self::DATA);
        $this->assertSame($tx->from, self::MINTER_ADDRESS);
    }

    /**
     * Test signing MinterEditCandidateTx
     */
    public function testSign(): void
    {
        $tx = new MinterTx([
           'nonce'   => 16,
           'chainId' => MinterTx::MAINNET_CHAIN_ID,
           'type'    => MinterEditCandidateTx::TYPE,
           'data'    => self::DATA
       ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
