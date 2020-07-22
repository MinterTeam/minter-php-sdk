<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterSendCoinTx;
use Minter\SDK\MinterDeepLink;
use PHPUnit\Framework\TestCase;

final class MinterDeepLinkTest extends TestCase
{
    const HOST_BASE_URL = 'https://bip.to/tx';

    public function testEncodeWithPayload()
    {
        $txData = new MinterSendCoinTx(0, 'Mx7633980c000139dd3bd24a3f54e06474fa941e16', 10);
        $link   = new MinterDeepLink($txData);

        $link->setPayload('custom message')->setGasCoin('ASD');

        $this->assertSame(
            self::HOST_BASE_URL . '/-D4BoN-AlHYzmAwAATndO9JKP1TgZHT6lB4WiIrHIwSJ6AAAjmN1c3RvbSBtZXNzYWdlgICKQVNEAAAAAAAAAA',
            $link->encode());
    }

    public function testEncodeWithoutPayload()
    {
        $txData = new MinterSendCoinTx(0, 'Mx18467bbb64a8edf890201d526c35957d82be3d95', '1.23456789');
        $link   = new MinterDeepLink($txData);

        $this->assertSame(
            self::HOST_BASE_URL . '/5gGg34CUGEZ7u2So7fiQIB1SbDWVfYK-PZWIESIQ9HaNtACAgICA',
            $link->encode());
    }

    public function testEncodeFullTx()
    {
        $txData = new MinterSendCoinTx(0, 'Mx18467bbb64a8edf890201d526c35957d82be3d95', '1.23456789');
        $link   = new MinterDeepLink($txData);

        $link->setNonce('1');
        $link->setPayload('Check payload');
        $link->setGasCoin('MNT');
        $link->setGasPrice('1');

        $this->assertSame(
            self::HOST_BASE_URL . '/-D0BoN-AlBhGe7tkqO34kCAdUmw1lX2Cvj2ViBEiEPR2jbQAjUNoZWNrIHBheWxvYWQBAYpNTlQAAAAAAAAA',
            $link->encode()
        );
    }
}