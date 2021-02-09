<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterMoveStakeTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterMoveStakeTx
 */
final class MinterMoveStakeTxTest extends TestCase
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
    const VALID_SIGNATURE = '0xf8a1140201801bb850f84ea0325580a8baed04899252ae5b5f6167ee34ec0144f3401d88586b350999999999a0325580a8baed04899252ae5b5f6167ee34ec0144f3401d88586b350a380bc7d4808a010f0cf064dd59200000808001b845f8431ba096e59a5ba7cdccce28d6861a6a266c6b1e2e8bb87b86d1e97359f92c0349524da001cabc9a567adac325caa5ce64a32be2f789582cd71c1d4316dd9cfc3eea9d27';

    /**
     * Test to decode data for MinterMoveStakeTx
     */
    public function testDecode(): void
    {
        $tx = MinterTx::decode(self::VALID_SIGNATURE);
        $validTx = $this->makeTransaction();

        $this->assertSame($validTx->getNonce(), $tx->getNonce());
        $this->assertSame($validTx->getGasCoin(), $tx->getGasCoin());
        $this->assertSame($validTx->getGasPrice(), $tx->getGasPrice());
        $this->assertSame($validTx->getChainID(), $tx->getChainID());
        $this->assertSame($validTx->getData()->from, $tx->getData()->from);
        $this->assertSame($validTx->getData()->to, $tx->getData()->to);
        $this->assertSame($validTx->getData()->coin, $tx->getData()->coin);
        $this->assertSame($validTx->getData()->stake, $tx->getData()->stake);
        $this->assertSame(self::MINTER_ADDRESS, $tx->getSenderAddress());
    }

    /**
     * Test signing MinterMoveStakeTx
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
        $data = new MinterMoveStakeTx('Mp325580a8baed04899252ae5b5f6167ee34ec0144f3401d88586b350999999999','Mp325580a8baed04899252ae5b5f6167ee34ec0144f3401d88586b350a380bc7d4',0,'5000');
        return (new MinterTx(20, $data))->setChainID(MinterTx::TESTNET_CHAIN_ID);
    }
}
