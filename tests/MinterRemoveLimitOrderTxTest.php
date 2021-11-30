<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterRemoveLimitOrderTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class MinterRemoveLimitOrderTxTest
 */
final class MinterRemoveLimitOrderTxTest extends TestCase
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
    const VALID_SIGNATURE = '0xf852010201802482c106808001b845f8431ca0695d448ab24f360319def7df7c5fd05b7ad43a6edf290c86164d4141ef1a1dc3a04c92b2d56c346560aefd88dfd32734fdfb78f1670c7513d16dd6635405a28f07';

    /**
     * Test to decode data for MinterRemoveLimitOrderTx
     */
    public function testDecode(): void
    {
        $tx      = MinterTx::decode(self::VALID_SIGNATURE);
        $validTx = $this->makeTransaction();

        $this->assertSame($validTx->getNonce(), $tx->getNonce());
        $this->assertSame($validTx->getGasCoin(), $tx->getGasCoin());
        $this->assertSame($validTx->getGasPrice(), $tx->getGasPrice());
        $this->assertSame($validTx->getChainID(), $tx->getChainID());
        $this->assertSame($validTx->getData()->id, $tx->getData()->id);
        $this->assertSame(self::MINTER_ADDRESS, $tx->getSenderAddress());
    }

    /**
     * Test signing MinterRemoveLimitOrderTx
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
        $data = new MinterRemoveLimitOrderTx(6);
        return (new MinterTx(1, $data))->setChainID(MinterTx::TESTNET_CHAIN_ID);
    }
}
