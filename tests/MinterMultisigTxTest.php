<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Minter\SDK\MinterTx;

/**
 * Class for testing MinterMultisigTx
 */
final class MinterMultisigTxTest extends TestCase
{
    /**
     * Transaction structure
     */
    const TX = [
        'nonce' => 1,
        'chainId' => 2,
        'gasPrice' => 1,
        'gasCoin' => 0,
        'type' => 1,
        'data' => [
            'coin' => 0,
            'to' => 'Mxd82558ea00eb81d35f2654953598f5d51737d31d',
            'value' => 1
        ],
        'payload' => '',
        'serviceData' => '',
        'signatureType' => 2
    ];

    /**
     * Sender Minter address
     */
    const SENDER_ADDRESS = 'Mxdb4f4b6942cb927e8d7e3a1f602d0f1fb43b5bd2';

    /**
     * Private key for transaction
     */
    const PRIVATE_KEYS = [
        'b354c3d1d456d5a1ddd65ca05fd710117701ec69d82dac1858986049a0385af9',
        '38b7dfb77426247aed6081f769ed8f62aaec2ee2b38336110ac4f7484478dccb',
        '94c0915734f92dd66acfdc48f82b1d0b208efd544fe763386160ec30c968b4af'
    ];

    /**
     * Predefined valid transaction
     */
    const VALID_TX = '0xf901130102018001a0df8094d82558ea00eb81d35f2654953598f5d51737d31d880de0b6b3a7640000808002b8e8f8e694db4f4b6942cb927e8d7e3a1f602d0f1fb43b5bd2f8cff8431ba0d6e0e254e778d7561a8b04e08aafce2e7386df43f0f8ae018ee0364ba1690dfda037ce1cea1d2a41c1d6825fa15c71669a43142bb5eb7ba52ac6d2322dd1de2971f8431ba012b389e3dd031e3c7627c9ab8b808a0a657b03f14e7f18a65f49ba8f9a81c001a077d24311c974caf7a1fdf2c0c8c3a397734169dfd791074ffda220fbbd2b93aff8431ca0b6c8aedf7dfb6dfbd2808624a4c2f92e5895a60a93efc9806c2396c786de0daaa00a69ef06f735eb7e29c4bfc788be3ecb4f4f94d749756f692faa2c24fd303544';

    /**
     * Test signing.
     */
    public function testSign()
    {
        $tx = new MinterTx(self::TX);
        $signedTx = $tx->signMultisig(self::SENDER_ADDRESS, self::PRIVATE_KEYS);
        $this->assertEquals(self::VALID_TX, $signedTx);
    }

    /**
     * Test get decode.
     */
    public function testDecode()
    {
        $tx = new MinterTx(self::VALID_TX);
        $this->assertEquals(self::TX['data'], $tx->data);
        $this->assertEquals(self::SENDER_ADDRESS, $tx->from);
    }

    /**
     * Test sign multisig with ready signatures.
     */
    public function testSignBySignatures()
    {
        $tx = new MinterTx(self::TX);

        $signatures = [];
        foreach (self::PRIVATE_KEYS as $privateKey) {
            $signatures[] = $tx->createSignature($privateKey);
        }

        $signedTx = $tx->signMultisigBySigns(self::SENDER_ADDRESS, $signatures);
        $this->assertEquals(self::VALID_TX, $signedTx);
    }
}
