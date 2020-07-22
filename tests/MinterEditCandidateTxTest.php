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
     * Test to decode data for MinterEditCandidateTx
     */
    public function testDecode(): void
    {
        $tx      = MinterTx::decode(self::VALID_SIGNATURE);
        $validTx = $this->makeTransaction();

        $this->assertSame($validTx->getNonce(), $tx->getNonce());
        $this->assertSame($validTx->getGasCoin(), $tx->getGasCoin());
        $this->assertSame($validTx->getGasPrice(), $tx->getGasPrice());
        $this->assertSame($validTx->getChainID(), $tx->getChainID());
        $this->assertSame($validTx->getData()->publicKey, $tx->getData()->publicKey);
        $this->assertSame($validTx->getData()->newPublicKey, $tx->getData()->newPublicKey);
        $this->assertSame($validTx->getData()->rewardAddress, $tx->getData()->rewardAddress);
        $this->assertSame($validTx->getData()->ownerAddress, $tx->getData()->ownerAddress);
        $this->assertSame($validTx->getData()->controlAddress, $tx->getData()->controlAddress);
        $this->assertSame(self::MINTER_ADDRESS, $tx->getSenderAddress());
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
        $data = new MinterEditCandidateTx(
            'Mp0208f8a2bd535f65ecbe4b057b3b3c5fbfef6003b0713dc37b697b1d19153fe0',
            'Mp0208f8a2bd535f65ecbe4b057b3b3c5fbfef6003b0713dc37b697b1d19153fe1',
            'Mxd82558ea00eb81d35f2654953598f5d51737d31d',
            'Mxd82558ea00eb81d35f2654953598f5d51737d31d',
            'Mxd82558ea00eb81d35f2654953598f5d51737d31d'
        );

        return new MinterTx(16, $data);
    }
}
