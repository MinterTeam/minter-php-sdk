<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterPriceCommissionTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterPriceCommissionTx
 */
final class MinterPriceCommissionTxTest extends TestCase
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
    const VALID_SIGNATURE = '0xf902031f02018020b901b1f901aea0325580a8baed04899252ae5b5f6167ee34ec0144f3401d88586b350999999999830186a080880de0b6b3a7640000881bc16d674ec800008829a2241af62c0000883782dace9d900000884563918244f400008853444835ec580000886124fee993bc0000886f05b59d3b200000887ce66c50e2840000888ac7230489e800008898a7d9b8314c000088a688906bd8b0000088b469471f8014000088c249fdd32778000088d02ab486cedc000088de0b6b3a7640000088ebec21ee1da4000088f9ccd8a1c5080000890107ad8f556c6c00008901158e460913d000008901236efcbcbb3400008901314fb3706298000089013f306a2409fc000089014d1120d7b160000089015af1d78b58c40000890168d28e3f00280000890176b344f2a78c000089018493fba64ef0000089019274b259f65400008901a055690d9db800008901ae361fc1451c00008901bc16d674ec8000008901c9f78d2893e400008901d7d843dc3b4800008901e5b8fa8fe2ac00008901f399b1438a1000008902017a67f73174000089020f5b1eaad8d8000089021d3bd55e803c000089022b1c8c1227a00000890238fd42c5cf040000808001b845f8431ca013b5fc16f4bd45cc6c58b634954977e4a73c9f3d3c864835dfb6f4eaea2e7db7a014afd6498dea11cc4ac0471b738bf608bb72d10c27500730e1258f345c8373d9';

    /**
     * Test to decode data for MinterPriceCommissionTx
     */
    public function testDecode(): void
    {
        $tx = MinterTx::decode(self::VALID_SIGNATURE);
        $validTx = $this->makeTransaction();

        $this->assertSame($validTx->getNonce(), $tx->getNonce());
        $this->assertSame($validTx->getGasCoin(), $tx->getGasCoin());
        $this->assertSame($validTx->getGasPrice(), $tx->getGasPrice());
        $this->assertSame($validTx->getChainID(), $tx->getChainID());
        $this->assertSame($validTx->getData()->pubKey, $tx->getData()->pubKey);
        $this->assertSame($validTx->getData()->height, $tx->getData()->height);
        $this->assertSame($validTx->getData()->coin, $tx->getData()->coin);
        $this->assertSame($validTx->getData()->payloadByte, $tx->getData()->payloadByte);
        $this->assertSame($validTx->getData()->send, $tx->getData()->send);
        $this->assertSame($validTx->getData()->buyBancor, $tx->getData()->buyBancor);
        $this->assertSame($validTx->getData()->sellBancor, $tx->getData()->sellBancor);
        $this->assertSame($validTx->getData()->sellAllBancor, $tx->getData()->sellAllBancor);
        $this->assertSame($validTx->getData()->buyPool, $tx->getData()->buyPool);
        $this->assertSame($validTx->getData()->sellPool, $tx->getData()->sellPool);
        $this->assertSame($validTx->getData()->sellAllPool, $tx->getData()->sellAllPool);
        $this->assertSame($validTx->getData()->createTicker3, $tx->getData()->createTicker3);
        $this->assertSame($validTx->getData()->createTicker4, $tx->getData()->createTicker4);
        $this->assertSame($validTx->getData()->createTicker5, $tx->getData()->createTicker5);
        $this->assertSame($validTx->getData()->createTicker6, $tx->getData()->createTicker6);
        $this->assertSame($validTx->getData()->createTicker7to10, $tx->getData()->createTicker7to10);
        $this->assertSame($validTx->getData()->createCoin, $tx->getData()->createCoin);
        $this->assertSame($validTx->getData()->createToken, $tx->getData()->createToken);
        $this->assertSame($validTx->getData()->recreateCoin, $tx->getData()->recreateCoin);
        $this->assertSame($validTx->getData()->recreateToken, $tx->getData()->recreateToken);
        $this->assertSame($validTx->getData()->declareCandidacy, $tx->getData()->declareCandidacy);
        $this->assertSame($validTx->getData()->delegate, $tx->getData()->delegate);
        $this->assertSame($validTx->getData()->unbond, $tx->getData()->unbond);
        $this->assertSame($validTx->getData()->redeemCheck, $tx->getData()->redeemCheck);
        $this->assertSame($validTx->getData()->setCandidateOn, $tx->getData()->setCandidateOn);
        $this->assertSame($validTx->getData()->setCandidateOff, $tx->getData()->setCandidateOff);
        $this->assertSame($validTx->getData()->createMultisig, $tx->getData()->createMultisig);
        $this->assertSame($validTx->getData()->multisendBase, $tx->getData()->multisendBase);
        $this->assertSame($validTx->getData()->multisendDelta, $tx->getData()->multisendDelta);
        $this->assertSame($validTx->getData()->editCandidate, $tx->getData()->editCandidate);
        $this->assertSame($validTx->getData()->setHaltBlock, $tx->getData()->setHaltBlock);
        $this->assertSame($validTx->getData()->editTickerOwner, $tx->getData()->editTickerOwner);
        $this->assertSame($validTx->getData()->editMultisig, $tx->getData()->editMultisig);
        $this->assertSame($validTx->getData()->priceVote, $tx->getData()->priceVote);
        $this->assertSame($validTx->getData()->editCandidatePublicKey, $tx->getData()->editCandidatePublicKey);
        $this->assertSame($validTx->getData()->createSwapPool, $tx->getData()->createSwapPool);
        $this->assertSame($validTx->getData()->addLiquidity, $tx->getData()->addLiquidity);
        $this->assertSame($validTx->getData()->removeLiquidity, $tx->getData()->removeLiquidity);
        $this->assertSame($validTx->getData()->editCandidateCommission, $tx->getData()->editCandidateCommission);
        $this->assertSame($validTx->getData()->moveStake, $tx->getData()->moveStake);
        $this->assertSame($validTx->getData()->burnToken, $tx->getData()->burnToken);
        $this->assertSame($validTx->getData()->mintToken, $tx->getData()->mintToken);
        $this->assertSame($validTx->getData()->voteCommission, $tx->getData()->voteCommission);
        $this->assertSame($validTx->getData()->voteUpdate, $tx->getData()->voteUpdate);
        $this->assertSame(self::MINTER_ADDRESS, $tx->getSenderAddress());
    }

    /**
     * Test signing MinterPriceCommissionTx
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
        $data = new MinterPriceCommissionTx('Mp325580a8baed04899252ae5b5f6167ee34ec0144f3401d88586b350999999999', 100000,0,'1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35','36','37','38','39','40','41');
        return (new MinterTx(31, $data))->setChainID(MinterTx::TESTNET_CHAIN_ID);
    }
}
