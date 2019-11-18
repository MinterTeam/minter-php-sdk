<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterSendCoinTx;
use Minter\SDK\MinterDeepLink;
use PHPUnit\Framework\TestCase;

final class MinterDeepLinkTest extends TestCase
{
    public function testEncodeWithPayload()
    {
        $txData = new MinterSendCoinTx([
            'coin'  => 'BIP',
            'to'    => 'Mx18467bbb64a8edf890201d526c35957d82be3d95',
            'value' => '1.23456789'
        ]);

        $link = new MinterDeepLink($txData);
        $link->setPayload('Hello World');

        $this->assertSame(
            'f83b01aae98a424950000000000000009418467bbb64a8edf890201d526c35957d82be3d9588112210f4768db4008b48656c6c6f20576f726c64808080',
            $link->encode());
    }

    public function testEncodeWithoutPayload()
    {
        $txData = new MinterSendCoinTx([
            'coin'  => 'BIP',
            'to'    => 'Mx18467bbb64a8edf890201d526c35957d82be3d95',
            'value' => '1.23456789'
        ]);

        $link = new MinterDeepLink($txData);

        $this->assertSame(
            'f001aae98a424950000000000000009418467bbb64a8edf890201d526c35957d82be3d9588112210f4768db40080808080',
            $link->encode());
    }

    public function testEncodeFullTx()
    {
        $txData = new MinterSendCoinTx([
            'coin'  => 'BIP',
            'to'    => 'Mx18467bbb64a8edf890201d526c35957d82be3d95',
            'value' => '1.23456789'
        ]);

        $link = new MinterDeepLink($txData);

        $link->setNonce('1');
        $link->setPayload('Check payload');
        $link->setGasCoin('MNT');
        $link->setGasPrice('1');

        $this->assertSame(
            'f84701aae98a424950000000000000009418467bbb64a8edf890201d526c35957d82be3d9588112210f4768db4008d436865636b207061796c6f616401018a4d4e5400000000000000',
            $link->encode()
            );
    }
}