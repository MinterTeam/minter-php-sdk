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
            self::HOST_BASE_URL . '/9wGg34CUdjOYDAABOd070ko_VOBkdPqUHhaIiscjBInoAACOY3VzdG9tIG1lc3NhZ2WAgINBU0Q',
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
        $link->setGasCoin(1);
        $link->setGasPrice('1');

        $this->assertSame(
            self::HOST_BASE_URL . '/8wGg34CUGEZ7u2So7fiQIB1SbDWVfYK-PZWIESIQ9HaNtACNQ2hlY2sgcGF5bG9hZAEBAQ',
            $link->encode()
        );
    }
}