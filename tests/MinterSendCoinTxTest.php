<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterSendCoinTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterSendCoinTx
 */
final class MinterSendCoinTxTest extends TestCase
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
    const VALID_SIGNATURE = '0xf86f01010180019fde809467691076548b20234461ff6fd2bc9c64393eb8fc872bdbb64bc09000808001b845f8431ca08be3f0c3aecc80ec97332e8aa39f20cd9e735092c0de37eb726d8d3d0a255a66a02040a1001d1a9116317eb24aa7ee4730ed980bd08a1fc0adb4e7598425178d3a';

    /**
     * Test to decode data for MinterSendCoinTx
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
        $this->assertSame($validTx->getData()->to, $tx->getData()->to);
        $this->assertSame($validTx->getData()->value, $tx->getData()->value);
        $this->assertSame(self::MINTER_ADDRESS, $tx->getSenderAddress());
    }

    /**
     * Test signing MinterSendCoinTx
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
        $data = new MinterSendCoinTx(0, 'Mx67691076548b20234461ff6fd2bc9c64393eb8fc', '0.012345');
        return new MinterTx(1, $data);
    }
}
