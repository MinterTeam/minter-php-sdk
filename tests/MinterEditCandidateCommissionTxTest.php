<?php
declare(strict_types=1);


use Minter\SDK\MinterCoins\MinterEditCandidateCommissionTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterEditCandidateCommissionTx
 */
final class MinterEditCandidateCommissionTxTest extends TestCase
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
    const VALID_SIGNATURE = '0xf873190201801aa3e2a0325580a8baed04899252ae5b5f6167ee34ec0144f3401d88586b3509999999990f808001b845f8431ca0f1d383c895fc5b0fdefdfbfd291d5cb03b85688db4719e9f86bda1cc5668ac72a04d2947dd830bff7bccb6b6b41fcfa042c2a3f18f36d54eb278c85724e8e4bd7c';

    /**
     * Test to decode data for MinterEditCandidateCommissionTx
     */
    public function testDecode(): void
    {
        $tx = MinterTx::decode(self::VALID_SIGNATURE);
        $validTx = $this->makeTransaction();

        $this->assertSame($validTx->getNonce(), $tx->getNonce());
        $this->assertSame($validTx->getGasCoin(), $tx->getGasCoin());
        $this->assertSame($validTx->getGasPrice(), $tx->getGasPrice());
        $this->assertSame($validTx->getChainID(), $tx->getChainID());
        $this->assertSame($validTx->getData()->publicKey, $tx->getData()->publicKey);
        $this->assertSame($validTx->getData()->commission, $tx->getData()->commission);
        $this->assertSame(self::MINTER_ADDRESS, $tx->getSenderAddress());
    }

    /**
     * Test signing MinterEditCandidateCommissionTx
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
        $data = new MinterEditCandidateCommissionTx('Mp325580a8baed04899252ae5b5f6167ee34ec0144f3401d88586b350999999999',15);
        return (new MinterTx(25, $data))->setChainID(MinterTx::TESTNET_CHAIN_ID);
    }
}
