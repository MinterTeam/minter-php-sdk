<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterVoteUpdateTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterVoteUpdateTxTest
 */
final class MinterVoteUpdateTxTest extends TestCase
{
    /**
     * Predefined private key
     */
    const PRIVATE_KEY = '0x9f444c46ea729be713ac1c5bc3390e2dca61ca288c59356fce44165aaae9184a';

    /**
     * Predefined minter address
     */
    const MINTER_ADDRESS = 'Mx522ce8622d7cb5cac0a03fd7e4b76a8586813cd9';

    /**
     * Predefined valid signature
     */
    const VALID_SIGNATURE = '0xf87c0102018021aceb8474657374a0d83e627510eea6aefa46d9914b0715dabf4a561ced78d34267b31d41d5f700b58405f5e0ff808001b845f8431ca0031ea17f1af0cc678f978db4dc5b0419b09d40ef99a480cb8aba7688fcf1083da003632fd34b944ad0b630124df95d26ea5a6c2a4d1890942a670390d3f1811da0';

    /**
     * Test to decode data for MinterVoteUpdateTx
     */
    public function testDecode(): void
    {
        $tx      = MinterTx::decode(self::VALID_SIGNATURE);
        $validTx = $this->makeTransaction();

        $this->assertSame($validTx->getNonce(), $tx->getNonce());
        $this->assertSame($validTx->getGasCoin(), $tx->getGasCoin());
        $this->assertSame($validTx->getGasPrice(), $tx->getGasPrice());
        $this->assertSame($validTx->getChainID(), $tx->getChainID());
        $this->assertSame($validTx->getData()->publicKey, $tx->getData()->publicKey);
        $this->assertSame($validTx->getData()->version, $tx->getData()->version);
        $this->assertSame($validTx->getData()->height, $tx->getData()->height);
        $this->assertSame(self::MINTER_ADDRESS, $tx->getSenderAddress());
    }

    /**
     * Test signing MinterUnbondTx
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
        $data = new MinterVoteUpdateTx(
            'test',
            'Mpd83e627510eea6aefa46d9914b0715dabf4a561ced78d34267b31d41d5f700b5',
            99999999, '100'
        );

        return (new MinterTx(1, $data))->setTestnetChainId();
    }
}
