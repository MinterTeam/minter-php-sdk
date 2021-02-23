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
    const VALID_SIGNATURE = '0xf8680e0201801898d7c202038829a2241af62c00008a010f0cf064dd59200000808001b845f8431ca0d77c1c580754c5143abb5a3ad3f5e892ebd36e7f9f44b7da2734ffff6c2e9611a022034256303ee750a9b32b148e60c6fff34665b5854f1a9770f7ab4a8a26cc00';

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
        $this->assertSame($validTx->getData()->coins, $tx->getData()->coins);
        $this->assertSame($validTx->getData()->valueToBuy, $tx->getData()->valueToBuy);
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
        $data = new MinterBuySwapPoolTx([2, 3], '3', '5000');
        return (new MinterTx(14, $data))->setChainID(MinterTx::TESTNET_CHAIN_ID);
    }
}