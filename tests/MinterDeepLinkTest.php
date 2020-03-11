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
        $txData = new MinterSendCoinTx([
           'coin' => 'MNT',
           'to' => 'Mx7633980c000139dd3bd24a3f54e06474fa941e16',
           'value' => 10
       ]);

        $link = new MinterDeepLink($txData);
        $link->setPayload('custom message')->setGasCoin('ASD');

        $this->assertSame(
            self::HOST_BASE_URL . '/-EgBqumKTU5UAAAAAAAAAJR2M5gMAAE53TvSSj9U4GR0-pQeFoiKxyMEiegAAI5jdXN0b20gbWVzc2FnZYCAikFTRAAAAAAAAAA',
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
            self::HOST_BASE_URL . '/8AGq6YpCSVAAAAAAAAAAlBhGe7tkqO34kCAdUmw1lX2Cvj2ViBEiEPR2jbQAgICAgA',
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
            self::HOST_BASE_URL . '/-EcBqumKQklQAAAAAAAAAJQYRnu7ZKjt-JAgHVJsNZV9gr49lYgRIhD0do20AI1DaGVjayBwYXlsb2FkAQGKTU5UAAAAAAAAAA',
            $link->encode()
            );
    }
}