<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterAddLiquidityTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class MinterAddLiquidityTxTest
 */
final class MinterAddLiquidityTxTest extends TestCase
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
    const VALID_SIGNATURE = '0xf8670d0201801597d68001890d8d726b7177a80000891b1ae4d6e2ef500000808001b845f8431ba0a213c7ac638e399cc4f85047bfab2da6ace86fa77e0497a3592737cb2ddcfff3a00a261dcbfa6ec0f51028756a8eec571464828a53f1c09666b6d45b7a9c617259';

    /**
     * Test to decode data for MinterAddLiquidityTx
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
        $this->assertSame($validTx->getData()->volume0, $tx->getData()->volume0);
        $this->assertSame($validTx->getData()->maximumVolume1, $tx->getData()->maximumVolume1);
        $this->assertSame(self::MINTER_ADDRESS, $tx->getSenderAddress());
    }

    /**
     * Test signing MinterAddLiquidityTx
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
        $data = new MinterAddLiquidityTx(0, 1, '250', '500');
        return (new MinterTx(13, $data))->setChainID(MinterTx::TESTNET_CHAIN_ID);;
    }
}