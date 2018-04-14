<?php
declare(strict_types=1);

use Minter\SDK\MinterSendCoinTx;
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
    const PRIVATE_KEY = '418e4be028dcaed85aa58b643979f644f806a42bb6d1912848720788a53bb8a4';

    /**
     * Predefined minter address
     */
    const MINTER_ADDRESS = 'Mxfe60014a6e9ac91618f5d1cab3fd58cded61ee99';

    /**
     * Predefined data
     */
    const DATA = [
        'coin' => 'MNT',
        'to' => 'Mxc3a55cdb5bcb97fd5657794247de4ed5e4a49f0d',
        'value' => 1
    ];

    /**
     * Predefined valid tx for decoding
     */
    const VALID_TX = '+G0LAQGm5YpNTlQAAAAAAAAAlMOlXNtby5f9Vld5QkfeTtXkpJ8NhAX14QAcoBTXzGIXMl5Yo4hNer6emdLQP4b40AGTKOacva2N2oJyoH9lXgz1hFz+FC9jfK0zeJAOtmzOttBK3a7Bqxw/FtRO';

    /**
     * Predefined valid signature
     */
    const VALID_SIGNATURE = 'Mxf86d0b0101a6e58a4d4e540000000000000094c3a55cdb5bcb97fd5657794247de4ed5e4a49f0d8405f5e1001ca014d7cc6217325e58a3884d7abe9e99d2d03f86f8d0019328e69cbdad8dda8272a07f655e0cf5845cfe142f637cad3378900eb66cceb6d04addaec1ab1c3f16d44e';

    /**
     * Test to decode data for MinterSendCoinTx
     */
    public function testDecode(): void
    {
        $tx = new MinterTx(self::VALID_TX);

        $this->assertSame($tx->data, self::DATA);
        $this->assertSame($tx->from, self::MINTER_ADDRESS);
    }

    /**
     * Test signing MinterSendCoinTx
     */
    public function testSign(): void
    {
        $tx = new MinterTx([
            'nonce' => 11,
            'gasPrice' => 1,
            'type' => MinterSendCoinTx::TYPE,
            'data' => self::DATA
        ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
