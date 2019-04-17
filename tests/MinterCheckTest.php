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
    CONST VALID_CHECK = 'Mcf8a00102830f423f8a4d4e5400000000000000888ac7230489e80000b8419200e3c947484ced3268eebd1810d640ac0d6c6a099e4d87e074bab6a5751a324540e1e53907a10c9fb73f944490a737034de4a8bae96e707b5acbf8015dd8cb001ba0cbbc87bc7018f2c3bcaea67968713389addc3bf72f698b8b44ffddc384fca230a07ff35524aaca365fdac2eb25d29e9ba8431484fcb2b890d6d940d2527daeca22';

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
            'nonce' => 1,
            'chainId' => MinterTx::TESTNET_CHAIN_ID,
            'dueBlock' => 999999,
            'coin' => 'MNT',
            'value' => 10
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
    }

    /**
     * Test that Minter Check return valid array after decoding.
     */
    public function testDecodeCheck()
    {
        $check = new MinterCheck(self::VALID_CHECK);

        $this->assertSame([
            'nonce' => 1,
            'chainId' => MinterTx::TESTNET_CHAIN_ID,
            'dueBlock' => 999999,
            'coin' => 'MNT',
            'value' => '10',
            'lock' => '9200e3c947484ced3268eebd1810d640ac0d6c6a099e4d87e074bab6a5751a324540e1e53907a10c9fb73f944490a737034de4a8bae96e707b5acbf8015dd8cb00',
            'v' => 27,
            'r' => 'cbbc87bc7018f2c3bcaea67968713389addc3bf72f698b8b44ffddc384fca230',
            's' => '7ff35524aaca365fdac2eb25d29e9ba8431484fcb2b890d6d940d2527daeca22'
        ], $check->getBody());

        $this->assertSame('Mxce931863b9c94a526d94acd8090c1c5955a6eb4b', $check->getOwnerAddress());
    }
}
