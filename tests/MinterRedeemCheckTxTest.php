<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterRedeemCheckTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterRedeemCheckTx
 */
final class MinterRedeemCheckTxTest extends TestCase
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
    const VALID_SIGNATURE = '0xf901fb1101018009b901a9f901a6b9016066386165383333343338333030323833306634323366386134643465353430303030303030303030303030303838386163373233303438396538303030303861346434653534303030303030303030303030303062383431343937633566336536666331383266643161373931353232613965663735373637313062646662633836666462663136353437366566323230653839663966663133383066393366326439613266393266646162306564633165323630356363326336396237303763643430346232636231353232623761626134646566643530303162613038336339393435313639663061376262653539363937336233326463383837363038373830353830623164336263376231383862656462336264333835353934613034376232643533343539343665643534393866356265653731336638363237366161633034366135666566383230626561656537376139623666396263316466b841da021d4f84728e0d3d312a18ec84c21768e0caa12a53cb0a1452771f72b0d1a91770ae139fd6c23bcf8cec50f5f2e733eabb8482cf29ee540e56c6639aac469600808001b845f8431ca0b1f13562e744908fa8624e3ac404a2db86a56eac0a528903d8b8c435679d8dcaa0681dd5ef6d29eddeea794d811ded481e785f4fc070149f64d98fbe4b0a64a4d3';

    /**
     * Test to decode data for MinterRedeemCheckTx
     */
    public function testDecode(): void
    {
        $tx = MinterTx::decode(self::VALID_SIGNATURE);
        $validTx = $this->makeTransaction();

        $this->assertSame($validTx->getNonce(), $tx->getNonce());
        $this->assertSame($validTx->getGasCoin(), $tx->getGasCoin());
        $this->assertSame($validTx->getGasPrice(), $tx->getGasPrice());
        $this->assertSame($validTx->getChainID(), $tx->getChainID());
        $this->assertSame($validTx->getData()->proof, $tx->getData()->proof);
        $this->assertSame($validTx->getData()->check, $tx->getData()->check);
        $this->assertSame(self::MINTER_ADDRESS, $tx->getSenderAddress());
    }

    /**
     * Test signing MinterRedeemCheckTx
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
        return new MinterTx(17, new MinterRedeemCheckTx(
            'Mcf8ae8334383002830f423f8a4d4e5400000000000000888ac7230489e800008a4d4e5400000000000000b841497c5f3e6fc182fd1a791522a9ef7576710bdfbc86fdbf165476ef220e89f9ff1380f93f2d9a2f92fdab0edc1e2605cc2c69b707cd404b2cb1522b7aba4defd5001ba083c9945169f0a7bbe596973b32dc887608780580b1d3bc7b188bedb3bd385594a047b2d5345946ed5498f5bee713f86276aac046a5fef820beaee77a9b6f9bc1df',
            'da021d4f84728e0d3d312a18ec84c21768e0caa12a53cb0a1452771f72b0d1a91770ae139fd6c23bcf8cec50f5f2e733eabb8482cf29ee540e56c6639aac469600'
        ));
    }
}
