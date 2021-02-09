<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterRecreateTokenTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

class MinterRecreateTokenTxTest extends TestCase
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
    const VALID_SIGNATURE = '0xf8901e0201801fb83ff83d994255524e41424c452026204d494e5441424c4520544f4b454e8a4255524e41424c4500008ad3c20dee1639f99c00008ad3c20dee1639f99c00000101808001b845f8431ca082c41369313d7d2c2fc1c455a2d1a0458da8f647ddbf45601f2edbef2299c5ffa06788aaeec5abff26d91f1d482903cb41fa57c34823e014aa711099f31b94a3be';

    /**
     * Test to decode data for MinterRecreateTokenTx
     */
    public function testDecode(): void
    {
        $tx = MinterTx::decode(self::VALID_SIGNATURE);
        $validTx = $this->makeTransaction();

        $this->assertSame($validTx->getNonce(), $tx->getNonce());
        $this->assertSame($validTx->getGasCoin(), $tx->getGasCoin());
        $this->assertSame($validTx->getGasPrice(), $tx->getGasPrice());
        $this->assertSame($validTx->getChainID(), $tx->getChainID());
        $this->assertSame($validTx->getData()->name, $tx->getData()->name);
        $this->assertSame($validTx->getData()->symbol, $tx->getData()->symbol);
        $this->assertSame($validTx->getData()->initialAmount, $tx->getData()->initialAmount);
        $this->assertSame($validTx->getData()->maxSupply, $tx->getData()->maxSupply);
        $this->assertSame($validTx->getData()->mintable, $tx->getData()->mintable);
        $this->assertSame($validTx->getData()->burnable, $tx->getData()->burnable);
        $this->assertSame(self::MINTER_ADDRESS, $tx->getSenderAddress());
    }

    /**
     * Test signing MinterRecreateTokenTx
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
        $data = new MinterRecreateTokenTx('BURNABLE & MINTABLE TOKEN','BURNABLE','999999','999999',true,true);
        return (new MinterTx(30, $data))->setChainID(MinterTx::TESTNET_CHAIN_ID);
    }
}