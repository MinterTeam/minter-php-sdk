<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterLockTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterLockTx
 */
final class MinterLockTxTest extends TestCase
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
    const VALID_SIGNATURE = '0xf861010201802691d0843b9ac9ff8089056bc75e2d63100000808001b845f8431ba0986d3d7db8e75175ac5cdea2ea4bf382e5cd21fbe4e522b4240de9cfb2c9a2cda03ae8cc5c6d7e9aedd5299129025a33e43908d7ecffe69e1f7551fc71bc0b6597';

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
        $this->assertSame($validTx->getData()->dueBlock, $tx->getData()->dueBlock);
        $this->assertSame($validTx->getData()->coin, $tx->getData()->coin);
        $this->assertSame($validTx->getData()->value, $tx->getData()->value);
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
        $data = new MinterLockTx(999999999, 0, '100');
        return (new MinterTx(1, $data))->setTestnetChainId();
    }
}
