<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterEditMultisigTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class MinterEditMultisigTx
 */
final class MinterEditMultisigTxTest extends TestCase
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
    const VALID_SIGNATURE = '0xf8990101018012b0ef01c20102ea9467691076548b20234461ff6fd2bc9c64393eb8fc94cc34fb43daae6b98af2c3bcf2ec82cdf281ea8b5808002b85ef85c9401bf2a485ab11d4ef1339cc68abf9dc46582675ef845f8431ba012d564085b3ad46539ae1b83eefec68eee2aa21283c5edb8def59696d085dc34a0371782d3d72e98f85e0a46d7d0b68c640daefc0888b1fc5664fb329163ea59b3';

    const DATA = [
        'threshold' => 1,
        'weights'   => [1, 2],
        'addresses' => ['Mx67691076548b20234461ff6fd2bc9c64393eb8fc', 'Mxcc34fb43daae6b98af2c3bcf2ec82cdf281ea8b5']
    ];

    /**
     * Test to decode data for MinterEditMultisigTx
     */
    public function testDecode(): void
    {
        $tx = new MinterTx(self::VALID_SIGNATURE);
        $this->assertSame($tx->data, self::DATA);
        $this->assertSame('Mx01bf2a485ab11d4ef1339cc68abf9dc46582675e', $tx->from);
    }

    /**
     * Test signing MinterCreateMultisigTx
     */
    public function testSign(): void
    {
        $signature = $this->makeTransaction()
                          ->signMultisig('Mx01bf2a485ab11d4ef1339cc68abf9dc46582675e', [self::PRIVATE_KEY]);
        $this->assertSame($signature, self::VALID_SIGNATURE);
    }

    /**
     * @return MinterTx
     */
    private function makeTransaction(): MinterTx
    {
        return new MinterTx([
            'nonce'   => 1,
            'chainId' => MinterTx::MAINNET_CHAIN_ID,
            'type'    => MinterEditMultisigTx::TYPE,
            'data'    => self::DATA,
        ]);
    }
}