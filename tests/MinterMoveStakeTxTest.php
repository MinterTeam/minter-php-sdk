<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterDelegateTx;
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
    const PRIVATE_KEY = '0x00b05ce5f9c4e848e947fc12c362da5d53ec5b11027396aca678d2978e9ad8f9';

    /**
     * Predefined minter address
     */
    const MINTER_ADDRESS = 'Mxdb66a454999e7bb4d173574b24dd1056028a40f9';

    /**
     * Predefined valid signature
     */
    const VALID_SIGNATURE = '0xf8a0040201801bb84ff84da0325580a8baed04899252ae5b5f6167ee34ec0144f3401d88586b350a380bc7d4a0eee9614b63a7ed6370ccd1fa227222fa30d6106770145c55bd4b482b888888888089056bc75e2d63100000808001b845f8431ba052ddb2a8b5a5a4f76a56b670e8e0ad61a5dc89474bb5e1abe6d01ef5864ae165a03170a55c16a2f44187f9858c5199687a7c966bd370604024d0d97770aaa239e9';

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
        $this->assertSame($validTx->getData()->fromPubKey, $tx->getData()->fromPubKey);
        $this->assertSame($validTx->getData()->toPubKey, $tx->getData()->toPubKey);
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
        $data = new MinterMoveStakeTx(
            'Mp325580a8baed04899252ae5b5f6167ee34ec0144f3401d88586b350a380bc7d4',
            'Mpeee9614b63a7ed6370ccd1fa227222fa30d6106770145c55bd4b482b88888888',
            0,
            '100');

        return (new MinterTx(4, $data))->setTestnetChainId();
    }
}
