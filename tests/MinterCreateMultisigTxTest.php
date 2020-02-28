<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterCreateMultisigTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class MinterCreateMultisigTxTest
 */
final class MinterCreateMultisigTxTest extends TestCase
{
    /**
     * Predefined private key
     */
    const PRIVATE_KEY = 'bc3503cae8c8561df5eadc4a9eda21d32c252a6c94cfae55b5310bf6085c8582';

    /**
     * Predefined minter address
     */
    const MINTER_ADDRESS = 'Mx3e4d56e776ff42c023b1ec99a7486b592a654981';

    /**
     * Predefined data
     */
    const DATA = [
        'threshold' => 7,
        'weights' => [1, 3, 5],
        'addresses' => [
            'Mxee81347211c72524338f9680072af90744333143',
            'Mxee81347211c72524338f9680072af90744333145',
            'Mxee81347211c72524338f9680072af90744333144'
        ]
    ];

    /**
     * Predefined valid signature
     */

    const VALID_SIGNATURE = '0xf8a30102018a4d4e54000000000000000cb848f84607c3010305f83f94ee81347211c72524338f9680072af9074433314394ee81347211c72524338f9680072af9074433314594ee81347211c72524338f9680072af90744333144808001b845f8431ca094eb41d39e6782f5539615cc66da7073d4283893f0b3ee2b2f36aee1eaeb7c57a037f90ffdb45eb9b6f4cf301b48e73a6a81df8182e605b656a52057537d264ab4';

    /**
     * Test to decode data for MinterCreateMultisigTx
     */
    public function testDecode(): void
    {
        $tx = new MinterTx(self::VALID_SIGNATURE);

        $this->assertSame($tx->data, self::DATA);
        $this->assertSame($tx->from, self::MINTER_ADDRESS);
    }

    /**
     * Test signing MinterCreateMultisigTx
     */
    public function testSign(): void
    {
        $tx = new MinterTx([
            'nonce' => 1,
            'chainId' => MinterTx::TESTNET_CHAIN_ID,
            'gasPrice' => 1,
            'gasCoin' => 'MNT',
            'type' => MinterCreateMultisigTx::TYPE,
            'data' => self::DATA,
            'payload' => '',
            'serviceData' => '',
            'signatureType' => MinterTx::SIGNATURE_SINGLE_TYPE
        ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
