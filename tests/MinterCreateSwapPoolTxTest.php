<?php
declare(strict_types=1);


use Minter\SDK\MinterCoins\MinterCreateSwapPoolTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterCreateSwapPoolTx
 */
final class MinterCreateSwapPoolTxTest extends TestCase
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
    const VALID_SIGNATURE = '0xf8691a0201802299d803048a010f0cf064dd592000008a021e19e0c9bab2400000808001b845f8431ca0ba14d8ae3bb24ea063470a63185e4f590e624babd5767b294f49b8a645279d4da048737a80238a4b5e3e1218f7e70b964929c2ca0337ca4b8a8710550c83acf6d3';

    /**
     * Test to decode data for MinterCreateSwapPoolTx
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
        $this->assertSame($validTx->getData()->volume1, $tx->getData()->volume1);
        $this->assertSame(self::MINTER_ADDRESS, $tx->getSenderAddress());
    }

    /**
     * Test signing MinterCreateSwapPoolTx
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
        $data = new MinterCreateSwapPoolTx(3,4,'5000','10000');
        return (new MinterTx(26, $data))->setChainID(MinterTx::TESTNET_CHAIN_ID);
    }
}