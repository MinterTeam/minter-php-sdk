<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterLockStakeTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterLockStakeTx
 */
final class MinterLockStakeTxTest extends TestCase
{
    /**
     * Predefined private key
     */
    const PRIVATE_KEY = '0x00b05ce5f9c4e848e947fc12c362da5d53ec5b11027396aca678d2978e9ad8f9';

    /**
     * Predefined minter address
     */
    const MINTER_ADDRESS = 'Mxdb66a454999e7bb4d173574b24dd1056028a40f9';

    /**
     * Predefined valid signature
     */
    const VALID_SIGNATURE = '0xf851030201802581c0808001b845f8431ca0088f6ec7c34252605294d968b34dcb3f3731eb4cb9cb08155822a2f68a61bb47a06f67c150785b7773f478e36945bcf8ff47833644196532f99080b0db93f5b714';

    /**
     * Test to decode data for MinterDelegateTx
     */
    public function testDecode(): void
    {
        $tx      = MinterTx::decode(self::VALID_SIGNATURE);
        $validTx = $this->makeTransaction();

        $this->assertSame($validTx->getNonce(), $tx->getNonce());
        $this->assertSame($validTx->getGasCoin(), $tx->getGasCoin());
        $this->assertSame($validTx->getGasPrice(), $tx->getGasPrice());
        $this->assertSame($validTx->getChainID(), $tx->getChainID());
        $this->assertSame(self::MINTER_ADDRESS, $tx->getSenderAddress());
    }

    /**
     * Test signing MinterDelegateTx
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
        $data = new MinterLockStakeTx();
        return (new MinterTx(3, $data))->setTestnetChainId();
    }
}
