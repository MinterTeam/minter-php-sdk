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
        'gasCoin' => 'MNT',
        'type' => 1,
        'data' => [
            'coin' => 'MNT',
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
    const VALID_TX = '0xf901270102018a4d4e540000000000000001aae98a4d4e540000000000000094d82558ea00eb81d35f2654953598f5d51737d31d880de0b6b3a7640000808002b8e8f8e694db4f4b6942cb927e8d7e3a1f602d0f1fb43b5bd2f8cff8431ca0a116e33d2fea86a213577fc9dae16a7e4cadb375499f378b33cddd1d4113b6c1a021ee1e9eb61bbd24233a0967e1c745ab23001cf8816bb217d01ed4595c6cb2cdf8431ca0f7f9c7a6734ab2db210356161f2d012aa9936ee506d88d8d0cba15ad6c84f8a7a04b71b87cbbe7905942de839211daa984325a15bdeca6eea75e5d0f28f9aaeef8f8431ba0d8c640d7605034eefc8870a6a3d1c22e2f589a9319288342632b1c4e6ce35128a055fe3f93f31044033fe7b07963d547ac50bccaac38a057ce61665374c72fb454';

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
