<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterEditMultisigOwnersTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class MinterEditMultisigOwnersTx
 */
final class MinterEditMultisigOwnersTxTest extends TestCase
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
    const VALID_SIGNATURE = '0xf8af0101018012b845f84394d3adb0453388a731055ed2e98bc4bce870a7b1fdc20102ea9467691076548b20234461ff6fd2bc9c64393eb8fc94cc34fb43daae6b98af2c3bcf2ec82cdf281ea8b5808002b85ef85c9401bf2a485ab11d4ef1339cc68abf9dc46582675ef845f8431ba00352639007ee4fcee1283122e408abb76c7ca2da238ae2b3d8663276f9578057a02823ac0086fe114a752af12362978ab2ab0fa03d3e14a05067f2fe4c0108198e';

    /**
     * Test to decode data for MinterEditMultisigOwnersTx
     */
    public function testDecode(): void
    {
        $tx      = MinterTx::decode(self::VALID_SIGNATURE);
        $validTx = $this->makeTransaction();

        $this->assertSame($validTx->getNonce(), $tx->getNonce());
        $this->assertSame($validTx->getGasCoin(), $tx->getGasCoin());
        $this->assertSame($validTx->getGasPrice(), $tx->getGasPrice());
        $this->assertSame($validTx->getChainID(), $tx->getChainID());
        $this->assertSame($validTx->getData()->weights, $tx->getData()->weights);
        $this->assertSame($validTx->getData()->addresses, $tx->getData()->addresses);
        $this->assertSame($validTx->getData()->multisigAddress, $tx->getData()->multisigAddress);
        $this->assertSame('Mx01bf2a485ab11d4ef1339cc68abf9dc46582675e', $tx->getSenderAddress());
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
        $data = new MinterEditMultisigOwnersTx(
            'Mxd3adb0453388a731055ed2e98bc4bce870a7b1fd', [1, 2],
            ['Mx67691076548b20234461ff6fd2bc9c64393eb8fc', 'Mxcc34fb43daae6b98af2c3bcf2ec82cdf281ea8b5']
        );

        return new MinterTx(1, $data);
    }
}
