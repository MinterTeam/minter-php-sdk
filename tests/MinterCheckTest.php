<?php
declare(strict_types=1);

use Minter\SDK\MinterCheck;
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
    CONST VALID_CHECK = 'Mxf89f01830f423f8a4d4e5400000000000000888ac7230489e80000b841ada7ad273bef8a1d22f3e314fdfad1e19b90b1fe8dc7eeb30bd1d391e89af8642af029c138c2e379b95d6bc71b26c531ea155d9435e156a3d113a14c912dfebf001ba0eb3d47f227c3da3b29e09234ad24c49296f177234f3c9700d780712a656c338ba05726e0ed31ab98c07869a99f22e84165fe4a777b0bac7bcf287532210cae1bba';

    /**
     * Predefined valid proof
     */
    const VALID_PROOF = 'da021d4f84728e0d3d312a18ec84c21768e0caa12a53cb0a1452771f72b0d1a91770ae139fd6c23bcf8cec50f5f2e733eabb8482cf29ee540e56c6639aac469600';

    /**
     * Test that Minter Check return valid signature
     */
    public function test_sign_check()
    {
        $check = new MinterCheck([
            'nonce' => 1,
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
    public function test_create_proof()
    {
        $check = new MinterCheck(self::ADDRESS, self::PASSPHRASE);

        $proof = $check->createProof();

        $this->assertSame(self::VALID_PROOF, $proof);
    }
}
