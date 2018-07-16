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
    const PRIVATE_KEY = '05ddcd4e6f7d248ed1388f0091fe345bf9bf4fc2390384e26005e7675c98b3c1';

    /**
     * Predefined data
     */
    const DATA = [
        'check' => 'Mcf89b01830f423f8a4d4e5400000000000000843b9aca00b8419b3beac2c6ad88a8bd54d24912754bb820e58345731cb1b9bc0885ee74f9e50a58a80aa990a29c98b05541b266af99d3825bb1e5ed4e540c6e2f7c9b40af9ecc011ca00f7ba6d0aa47d74274b960fba02be03158d0374b978dcaa5f56fc7cf1754f821a019a829a3b7bba2fc290f5c96e469851a3876376d6a6a4df937327b3a5e9e8297',
        'proof' => 'da021d4f84728e0d3d312a18ec84c21768e0caa12a53cb0a1452771f72b0d1a91770ae139fd6c23bcf8cec50f5f2e733eabb8482cf29ee540e56c6639aac469600'
    ];

    /**
     * Predefined valid signature
     */
    const VALID_SIGNATURE = 'f9012e010108b8e4f8e2b89df89b01830f423f8a4d4e5400000000000000843b9aca00b8419b3beac2c6ad88a8bd54d24912754bb820e58345731cb1b9bc0885ee74f9e50a58a80aa990a29c98b05541b266af99d3825bb1e5ed4e540c6e2f7c9b40af9ecc011ca00f7ba6d0aa47d74274b960fba02be03158d0374b978dcaa5f56fc7cf1754f821a019a829a3b7bba2fc290f5c96e469851a3876376d6a6a4df937327b3a5e9e8297b841da021d4f84728e0d3d312a18ec84c21768e0caa12a53cb0a1452771f72b0d1a91770ae139fd6c23bcf8cec50f5f2e733eabb8482cf29ee540e56c6639aac46960080801ba0bd28acb5a0ff49fb0f33955877f287ee70567a5bed875ddb23cfb2684c437889a019d6c2c57d3e0ecf2f3bba250beed53dfd00be75bd8ad7d7b7a52148565b2cfa';

    /**
     * Test to decode data for MinterRedeemCheckTx
     */
    public function testDecode(): void
    {
        $tx = new MinterTx(self::VALID_SIGNATURE);

        $this->assertSame($tx->data, self::DATA);
    }

    /**
     * Test signing MinterRedeemCheckTx
     */
    public function testSign(): void
    {
        $tx = new MinterTx([
            'nonce' => 1,
            'gasPrice' => 1,
            'type' => MinterRedeemCheckTx::TYPE,
            'data' => self::DATA,
            'payload' => '',
            'serviceData' => ''
        ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
