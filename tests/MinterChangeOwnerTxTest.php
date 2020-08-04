<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterChangeOwnerTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterChangeOwnerTx
 */
final class MinterChangeOwnerTxTest extends TestCase
{
    /**
     * Predefined private key
     */
    const PRIVATE_KEY = '4daf02f92bf760b53d3c725d6bcc0da8e55d27ba5350c78d3a88f873e502bd6e';

    /**
     * Predefined minter address
     */
    const MINTER_ADDRESS = 'Mx67691076548b20234461ff6fd2bc9c64393eb8fc';

    /**
     * Predefined valid signature
     */
    const VALID_SIGNATURE = '0xf8710b01018011a1e08a5355504552544553543194d82558ea00eb81d35f2654953598f5d51737d31c808001b845f8431ca07ec736f2bebcafb9628603c3837dd75a18e76f29bdeae6ecdce635ca8519ae00a04715b58493660840957d5cce0311a2f2caf7a3c14f7f3afaad3ec6c47f91d932';

    /**
     * Test to decode data for MinterChangeOwnerTx
     */
    public function testDecode(): void
    {
        $tx      = new MinterTx(self::VALID_SIGNATURE);
        $validTx = $this->makeTransaction();

//        $this->assertSame($validTx->getNonce(), $tx->getNonce());
//        $this->assertSame($validTx->getGasCoin(), $tx->getGasCoin());
//        $this->assertSame($validTx->getGasPrice(), $tx->getGasPrice());
//        $this->assertSame($validTx->getChainID(), $tx->getChainID());
//        $this->assertSame($validTx->getData()->newOwner, $tx->getData()->newOwner);
//        $this->assertSame($validTx->getData()->symbol, $tx->getData()->symbol);
        $this->assertSame(self::MINTER_ADDRESS, $tx->from);
    }

    /**
     * Test signing MinterChangeOwnerTx
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
        return new MinterTx([
            'nonce'   => 11,
            'chainId' => 1,
            'type'    => MinterChangeOwnerTx::TYPE,
            'data'    => [
                'symbol'   => 'SUPERTEST1',
                'newOwner' => 'Mxd82558ea00eb81d35f2654953598f5d51737d31c'
            ]
        ]);
    }
}