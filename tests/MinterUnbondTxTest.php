<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterUnbondTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterUnbondTx
 */
final class MinterUnbondTxTest extends TestCase
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
    const VALID_SIGNATURE = '0xf87c0701018008aceba00208f8a2bd535f65ecbe4b057b3b3c5fbfef6003b0713dc37b697b1d19153fe880880e92596fd6290000808001b845f8431ba00d60995f30fccc40de871a7264c748a21220ee3cd8f88e8bc893163f4f735d04a0103498704eeb2368a9b95b7baf60a2c92f949aa98be9acd78b0fb8999b75a8fd';

    /**
     * Test to decode data for MinterUnbondTx
     */
    public function testDecode(): void
    {
        $tx = MinterTx::decode(self::VALID_SIGNATURE);
        $validTx = $this->makeTransaction();

        $this->assertSame($validTx->getNonce(), $tx->getNonce());
        $this->assertSame($validTx->getGasCoin(), $tx->getGasCoin());
        $this->assertSame($validTx->getGasPrice(), $tx->getGasPrice());
        $this->assertSame($validTx->getChainID(), $tx->getChainID());
        $this->assertSame($validTx->getData()->publicKey, $tx->getData()->publicKey);
        $this->assertSame($validTx->getData()->coin, $tx->getData()->coin);
        $this->assertSame($validTx->getData()->value, $tx->getData()->value);
        $this->assertSame(self::MINTER_ADDRESS, $tx->getSenderAddress());
    }

    /**
     * Test signing MinterUnbondTx
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
        $data = new MinterUnbondTx('Mp0208f8a2bd535f65ecbe4b057b3b3c5fbfef6003b0713dc37b697b1d19153fe8', 0, '1.05');
        return new MinterTx(7, $data);
    }
}
