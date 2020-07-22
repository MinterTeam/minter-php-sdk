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
     * Test to decode data for MinterDeclareCandidacyTx
     */
    public function testDecode(): void
    {
        $tx = MinterTx::decode(self::VALID_SIGNATURE);
        $validTx = $this->makeTransaction();

        $this->assertSame($validTx->getNonce(), $tx->getNonce());
        $this->assertSame($validTx->getGasCoin(), $tx->getGasCoin());
        $this->assertSame($validTx->getGasPrice(), $tx->getGasPrice());
        $this->assertSame($validTx->getChainID(), $tx->getChainID());
        $this->assertSame($validTx->getData()->address, $tx->getData()->address);
        $this->assertSame($validTx->getData()->publicKey, $tx->getData()->publicKey);
        $this->assertSame($validTx->getData()->commission, $tx->getData()->commission);
        $this->assertSame($validTx->getData()->coin, $tx->getData()->coin);
        $this->assertSame($validTx->getData()->stake, $tx->getData()->stake);
        $this->assertSame(self::MINTER_ADDRESS, $tx->getSenderAddress());
    }

    /**
     * Test signing MinterDeclareCandidacyTx
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
        $data = new MinterDeclareCandidacyTx('Mx67691076548b20234461ff6fd2bc9c64393eb8fc', 'Mp0208f8a2bd535f65ecbe4b057b3b3c5fbfef6003b0713dc37b697b1d19153fe0', 10, 0, '10000');
        return new MinterTx(12, $data);
    }
}
