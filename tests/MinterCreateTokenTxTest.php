<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterCreateTokenTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

class MinterCreateTokenTxTest extends TestCase
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
    const VALID_SIGNATURE = '0xf883130201801eb3f28e4255524e41424c4520544f4b454e8a4255524e41424c4500008a34f086f3b33b684000008a3f870857a3e0e38000008001808001b845f8431ba03803675da057cde3bf43a085d1c774a93f3f038e843626d42d26bcdc92310e1fa002c77de52d499f65d03d159672c88e1cd9262048c60904b5a80a5065aa37fce1';

    /**
     * Test to decode data for MinterCreateTokenTx
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
     * Test signing MinterCreateTokenTx
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
        $data = new MinterCreateTokenTx('BURNABLE TOKEN','BURNABLE','250000','300000',false,true);
        return (new MinterTx(19, $data))->setChainID(MinterTx::TESTNET_CHAIN_ID);
    }
}