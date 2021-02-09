<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterRemoveLiquidityTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;


/**
 * Class MinterRemoveLiquidityTxTest
 */
final class MinterRemoveLiquidityTxTest extends TestCase
{
    /**
     * Predefined private key
     */
    const PRIVATE_KEY = '474ad4c6517502f3f939e276ae619f494d586a9b6cae81d63f8287dda0aabd4f';

    /**
     * Predefined minter address
     */
    const MINTER_ADDRESS = 'Mx4598fecf900b11ccffe991c51818be8479a46eee';

    /**
     * Predefined valid signature
     */
    const VALID_SIGNATURE = '0xf8700602018016a0df800189056bc75e2d6310000089015af1d78b58c4000088d02ab486cedc0000808001b845f8431ba0566d458644b591b770f9dd65be6edba084a16564c0a73f182d3412585bff1688a01887bdd283e4aaae59a3dc10b5c9d12d239bde89d989040b29454a000fa1a001';

    /**
     * Test to decode data for MinterRemoveLiquidityTx
     */
    public function testDecode(): void
    {
        $tx = MinterTx::decode(self::VALID_SIGNATURE);
        $validTx = $this->makeTransaction();

        $this->assertSame($validTx->getNonce(), $tx->getNonce());
        $this->assertSame($validTx->getGasCoin(), $tx->getGasCoin());
        $this->assertSame($validTx->getGasPrice(), $tx->getGasPrice());
        $this->assertSame($validTx->getChainID(), $tx->getChainID());
        $this->assertSame($validTx->getData()->coin0, $tx->getData()->coin0);
        $this->assertSame($validTx->getData()->coin1, $tx->getData()->coin1);
        $this->assertSame($validTx->getData()->liquidity, $tx->getData()->liquidity);
        $this->assertSame($validTx->getData()->minimumVolume0, $tx->getData()->minimumVolume0);
        $this->assertSame($validTx->getData()->minimumVolume1, $tx->getData()->minimumVolume1);
        $this->assertSame(self::MINTER_ADDRESS, $tx->getSenderAddress());
    }

    /**
     * Test signing MinterRemoveLiquidityTx
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
        $data = new MinterRemoveLiquidityTx(0, 1, '100', '25', '15');
        return (new MinterTx(6, $data))->setChainID(MinterTx::TESTNET_CHAIN_ID);
    }
}