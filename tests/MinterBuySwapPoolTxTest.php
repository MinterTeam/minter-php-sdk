<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterBuySwapPoolTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

final class MinterBuySwapPoolTxTest extends TestCase
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
    const VALID_SIGNATURE = '0xf8670e0201801897d6018829a2241af62c0000808a010f0cf064dd59200000808001b845f8431ba06f309b5ebb47042147c0e6481e186bdd06d6e883b680c7f282495efb75e83423a076aa9b5da356f9075604c0f6e20ac64832b1df885fdbe28010cc006b38c48d8d';

    /**
     * Test to decode data for MinterBuySwapPoolTx
     */
    public function testDecode(): void
    {
        $tx = MinterTx::decode(self::VALID_SIGNATURE);
        $validTx = $this->makeTransaction();

        $this->assertSame($validTx->getNonce(), $tx->getNonce());
        $this->assertSame($validTx->getGasCoin(), $tx->getGasCoin());
        $this->assertSame($validTx->getGasPrice(), $tx->getGasPrice());
        $this->assertSame($validTx->getChainID(), $tx->getChainID());
        $this->assertSame($validTx->getData()->coinToBuy, $tx->getData()->coinToBuy);
        $this->assertSame($validTx->getData()->valueToBuy, $tx->getData()->valueToBuy);
        $this->assertSame($validTx->getData()->coinToSell, $tx->getData()->coinToSell);
        $this->assertSame($validTx->getData()->maximumValueToSell, $tx->getData()->maximumValueToSell);
        $this->assertSame(self::MINTER_ADDRESS, $tx->getSenderAddress());
    }

    /**
     * Test signing MinterBuySwapPoolTx
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
        $data = new MinterBuySwapPoolTx(1, '3', 0, '5000');
        return (new MinterTx(14, $data))->setChainID(MinterTx::TESTNET_CHAIN_ID);
    }
}