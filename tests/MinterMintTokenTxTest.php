<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterMintTokenTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterMintTokenTx
 */
final class MinterMintTokenTxTest extends TestCase
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
    const VALID_SIGNATURE = '0xf85c170201801c8ccb0389878678326eac900000808001b845f8431ba0dd78fa0dfb782d99d3f0983917ff8b73e571cbd56eb694bfd570368a31453abca07a6d86ed82e5de41ad27588a7e71b7030abb258c6992d38f4b995c1184ebf745';

    /**
     * Test to decode data for MinterMintTokenTx
     */
    public function testDecode(): void
    {
        $tx = MinterTx::decode(self::VALID_SIGNATURE);
        $validTx = $this->makeTransaction();

        $this->assertSame($validTx->getNonce(), $tx->getNonce());
        $this->assertSame($validTx->getGasCoin(), $tx->getGasCoin());
        $this->assertSame($validTx->getGasPrice(), $tx->getGasPrice());
        $this->assertSame($validTx->getChainID(), $tx->getChainID());
        $this->assertSame($validTx->getData()->coin, $tx->getData()->coin);
        $this->assertSame($validTx->getData()->value, $tx->getData()->value);
        $this->assertSame(self::MINTER_ADDRESS, $tx->getSenderAddress());
    }

    /**
     * Test signing MinterMintTokenTx
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
        $data = new MinterMintTokenTx(3,'2500');
        return (new MinterTx(23, $data))->setChainID(MinterTx::TESTNET_CHAIN_ID);
    }
}
