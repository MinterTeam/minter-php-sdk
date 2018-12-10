<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterDeclareCandidacyTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterDeclareCandidacyTx
 */
final class MinterDeclareCandidacyTxTest extends TestCase
{
    /**
     * Predefined private key
     */
    const PRIVATE_KEY = '6e1df6ec69638d152f563c5eca6c13cdb5db4055861efc11ec1cdd578afd96bf';

    /**
     * Predefined data
     */
    const DATA = [
        'address' => 'Mx9f7fd953c2c69044b901426831ed03ee0bd0597a',
        'pubkey' => 'Mp0eb98ea04ae466d8d38f490db3c99b3996a90e24243952ce9822c6dc1e2c1a43',
        'commission' => '10',
        'coin' => 'MNT',
        'stake' => '5'
    ];

    /**
     * Predefined valid signature
     */

    const VALID_SIGNATURE = '0xf8a701018a4d4e540000000000000006b84df84b949f7fd953c2c69044b901426831ed03ee0bd0597aa00eb98ea04ae466d8d38f490db3c99b3996a90e24243952ce9822c6dc1e2c1a430a8a4d4e5400000000000000884563918244f40000808001b845f8431ba0ee1affda349de52bc1b0b72d2eee26531e8d234c8bf6b5fab15c2b398c3465fba00da829566a23518ca09e19642293a8559b87722f7ef03873ac08181c54fb2f6b';

    /**
     * Test to decode data for MinterDeclareCandidacyTx
     */
    public function testDecode(): void
    {
        $tx = new MinterTx(self::VALID_SIGNATURE);

        $this->assertSame($tx->data, self::DATA);
    }

    /**
     * Test signing MinterDeclareCandidacyTx
     */
    public function testSign(): void
    {
        $tx = new MinterTx([
            'nonce' => 1,
            'gasPrice' => 1,
            'gasCoin' => 'MNT',
            'type' => MinterDeclareCandidacyTx::TYPE,
            'data' => self::DATA,
            'payload' => '',
            'serviceData' => '',
            'signatureType' => MinterTx::SIGNATURE_SINGLE_TYPE
        ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
