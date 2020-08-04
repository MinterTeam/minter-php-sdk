<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterRedeemCheckTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterRedeemCheckTx
 */
final class MinterRedeemCheckTxTest extends TestCase
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
    const VALID_SIGNATURE = '0xf901350101018009b8e4f8e2b89df89b843130303201830f423f80880de0b6b3a764000080b8412b326337a6f1fc5617a3f9b32b0949cdf6761db0129d6507de155c21513b6a0334deb6d0bb4662426d4472716cde0b8258f47c99a12f93a05b2e732c4caaa9fa011ba0bdbd9d7d63b157fc232d5d859d13916e85e076632614013902b838c02e294428a06c031b2115e2c7c68c8808f84bba0cd8be5d882104b5a5c8355aa36008354e39b8413d02668333291917face5bbdc6c5bb6c2020479b720b3ee345b095a79a913409136a09c192b9483f0ae973cf6c86a71a9b440e7bdcb9437489463b93e15382a300808001b845f8431ba07020bc3b709ca547d0eeffb4baf0bd897dcfb4adabfed6113f1f1e9048335271a02af056405d1fe8feff5004cf693de523645c6001bd9ba4a5d41a838ed3fd040e';

    /**
     * Test to decode data for MinterRedeemCheckTx
     */
    public function testDecode(): void
    {
        $tx = MinterTx::decode(self::VALID_SIGNATURE);
        $validTx = $this->makeTransaction();

        $this->assertSame($validTx->getNonce(), $tx->getNonce());
        $this->assertSame($validTx->getGasCoin(), $tx->getGasCoin());
        $this->assertSame($validTx->getGasPrice(), $tx->getGasPrice());
        $this->assertSame($validTx->getChainID(), $tx->getChainID());
        $this->assertSame($validTx->getData()->proof, $tx->getData()->proof);
        $this->assertSame($validTx->getData()->check, $tx->getData()->check);
        $this->assertSame(self::MINTER_ADDRESS, $tx->getSenderAddress());
    }

    /**
     * Test signing MinterRedeemCheckTx
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
        return new MinterTx(1, new MinterRedeemCheckTx(
            'Mcf89b843130303201830f423f80880de0b6b3a764000080b8412b326337a6f1fc5617a3f9b32b0949cdf6761db0129d6507de155c21513b6a0334deb6d0bb4662426d4472716cde0b8258f47c99a12f93a05b2e732c4caaa9fa011ba0bdbd9d7d63b157fc232d5d859d13916e85e076632614013902b838c02e294428a06c031b2115e2c7c68c8808f84bba0cd8be5d882104b5a5c8355aa36008354e39',
            '3d02668333291917face5bbdc6c5bb6c2020479b720b3ee345b095a79a913409136a09c192b9483f0ae973cf6c86a71a9b440e7bdcb9437489463b93e15382a300'
        ));
    }
}
