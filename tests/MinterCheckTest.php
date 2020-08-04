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
    const PRIVATE_KEY = '4daf02f92bf760b53d3c725d6bcc0da8e55d27ba5350c78d3a88f873e502bd6e';

    /**
     * Predefined Minter address
     */
    const ADDRESS = 'Mx67691076548b20234461ff6fd2bc9c64393eb8fc';

    /**
     * Predefined passphrase
     */
    const PASSPHRASE = 'pass';

    /**
     * Predefined valid check string
     */
    CONST VALID_CHECK = 'Mcf89a8334383001830f423f80888ac7230489e8000080b84191ea56636b6667bb9da14bd412d492b90b9ae29799a90d0d69a637f3894c8ba246aae2b466fe76acab0a65cc6791ab2ae29d155b56efe84a929e089a22e15615001ba0d88c6543ac5d791428d46f8625c0af8e908fde3ec339e3d77ecb585e7c507ea8a021debf60dd96497d430b3cd92dac7bf22a00b4518f39d084c1dc50ba4c8b0d3b';

    /**
     * Predefined valid proof
     */
    const VALID_PROOF = '7afddf8a86013784a056a3fa7ce3b5b07e259870e686c5f3df4eb656f8e6800c732bd8cb13fbdca0100433d98a760a8736249a41ad34b8af445b807ba53974f500';

    /**
     * Test that Minter Check return valid signature
     */
    public function testSignCheck()
    {
        $check = new MinterCheck([
            'nonce' => 480,
            'chainId' => MinterTx::MAINNET_CHAIN_ID,
            'dueBlock' => 999999,
            'coin' => 0,
            'value' => 10,
            'gasCoin' => 0
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
            'chainId' => MinterTx::MAINNET_CHAIN_ID,
            'dueBlock' => 999999,
            'coin' => 0,
            'value' => '10',
            'gasCoin' => 0,
            'lock' => '91ea56636b6667bb9da14bd412d492b90b9ae29799a90d0d69a637f3894c8ba246aae2b466fe76acab0a65cc6791ab2ae29d155b56efe84a929e089a22e1561500',
            'v' => 27,
            'r' => 'd88c6543ac5d791428d46f8625c0af8e908fde3ec339e3d77ecb585e7c507ea8',
            's' => '21debf60dd96497d430b3cd92dac7bf22a00b4518f39d084c1dc50ba4c8b0d3b'
        ], $check->getBody());

        $this->assertSame(self::ADDRESS, $check->getOwnerAddress());
    }
}
