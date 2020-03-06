<?php
declare(strict_types=1);

use Minter\SDK\MinterCheck;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterCheck
 */
final class MinterCheckTest extends TestCase
{
    /**
     * Predefined private key
     */
    const PRIVATE_KEY = '64e27afaab363f21eec05291084367f6f1297a7b280d69d672febecda94a09ea';

    /**
     * Predefined Minter address
     */
    const ADDRESS = 'Mxa7bc33954f1ce855ed1a8c768fdd32ed927def47';

    /**
     * Predefined passphrase
     */
    const PASSPHRASE = 'pass';

    /**
     * Predefined valid check string
     */
    CONST VALID_CHECK = 'Mcf8ae8334383002830f423f8a4d4e5400000000000000888ac7230489e800008a4d4e5400000000000000b841497c5f3e6fc182fd1a791522a9ef7576710bdfbc86fdbf165476ef220e89f9ff1380f93f2d9a2f92fdab0edc1e2605cc2c69b707cd404b2cb1522b7aba4defd5001ba083c9945169f0a7bbe596973b32dc887608780580b1d3bc7b188bedb3bd385594a047b2d5345946ed5498f5bee713f86276aac046a5fef820beaee77a9b6f9bc1df';

    /**
     * Predefined valid proof
     */
    const VALID_PROOF = 'da021d4f84728e0d3d312a18ec84c21768e0caa12a53cb0a1452771f72b0d1a91770ae139fd6c23bcf8cec50f5f2e733eabb8482cf29ee540e56c6639aac469600';

    /**
     * Test that Minter Check return valid signature
     */
    public function testSignCheck()
    {
        $check = new MinterCheck([
            'nonce' => 480,
            'chainId' => MinterTx::TESTNET_CHAIN_ID,
            'dueBlock' => 999999,
            'coin' => 'MNT',
            'value' => 10,
            'gasCoin' => 'MNT'
        ], self::PASSPHRASE);

        $signature = $check->sign(self::PRIVATE_KEY);

        $this->assertSame(self::VALID_CHECK, $signature);
    }

    /**
     * Test that Minter Check create valid proof
     */
    public function testCreateProof()
    {
        $check = new MinterCheck(self::ADDRESS, self::PASSPHRASE);
        $proof = $check->createProof();
        $this->assertSame(self::VALID_PROOF, $proof);

        $proof = (new MinterCheck('Mx41f3e5c369c8c874181b119637f1330acd08fa9d', 'Hello   moto'))->createProof();
        $this->assertSame('ebe0562d0896e7ef4d0afd6d6fe80919a449dddf7f2c3fdd2a714bb39071ba68004f52ae2b4f995d24fd8be9808783330ed94bf02871f80eccb88ab3f3095b5d00', $proof);
    }

    /**
     * Test that Minter Check return valid array after decoding.
     */
    public function testDecodeCheck()
    {
        $check = new MinterCheck(self::VALID_CHECK);

        $this->assertSame([
            'nonce' => '480',
            'chainId' => MinterTx::TESTNET_CHAIN_ID,
            'dueBlock' => 999999,
            'coin' => 'MNT',
            'value' => '10',
            'gasCoin' => 'MNT',
            'lock' => '497c5f3e6fc182fd1a791522a9ef7576710bdfbc86fdbf165476ef220e89f9ff1380f93f2d9a2f92fdab0edc1e2605cc2c69b707cd404b2cb1522b7aba4defd500',
            'v' => 27,
            'r' => '83c9945169f0a7bbe596973b32dc887608780580b1d3bc7b188bedb3bd385594',
            's' => '47b2d5345946ed5498f5bee713f86276aac046a5fef820beaee77a9b6f9bc1df'
        ], $check->getBody());

        $this->assertSame('Mxce931863b9c94a526d94acd8090c1c5955a6eb4b', $check->getOwnerAddress());
    }
}
