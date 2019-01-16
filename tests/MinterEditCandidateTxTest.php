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
    const PRIVATE_KEY = 'a3fb55450f53dbbf4f2494280188f7f0cd51a7b51ec27ed49ed364d920e326ba';

    /**
     * Predefined minter address
     */
    const MINTER_ADDRESS = 'Mxa879439b0a29ecc7c5a0afe54b9eb3c22dbde8d9';

    /**
     * Predefined data
     */
    const DATA = [
        'pubkey' => 'Mp4ae1ee73e6136c85b0ca933a9a1347758a334885f10b3238398a67ac2eb153b8',
        'reward_address' => 'Mx89e5dc185e6bab772ac8e00cf3fb3f4cb0931c47',
        'owner_address' => 'Mxe731fcddd37bb6e72286597d22516c8ba3ddffa0'
    ];

    /**
     * Predefined valid signature
     */
    const VALID_SIGNATURE = '0xf8a701018a4d4e54000000000000000eb84df84ba04ae1ee73e6136c85b0ca933a9a1347758a334885f10b3238398a67ac2eb153b89489e5dc185e6bab772ac8e00cf3fb3f4cb0931c4794e731fcddd37bb6e72286597d22516c8ba3ddffa0808001b845f8431ba091e2c43e13e14a869f5f941e9fa2e676d79c0125aad281646f912d4919f9895fa07182db16d2279c158a3506b4867c46e42c7c3f9d82ca1f957fdfb636dfe78217';

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
            'nonce' => 1,
            'gasPrice' => 1,
            'gasCoin' => 'MNT',
            'type' => MinterEditCandidateTx::TYPE,
            'data' => self::DATA,
            'payload' => '',
            'serviceData' => '',
            'signatureType' => MinterTx::SIGNATURE_SINGLE_TYPE
        ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
