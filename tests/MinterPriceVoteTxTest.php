<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterPriceVoteTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterPriceVoteTx
 */
final class MinterPriceVoteTxTest extends TestCase
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
    const VALID_SIGNATURE = '0xf8540a0101801384c3822710808001b845f8431ba04243bc0e8af53fc9b0486baf58977659e775447bbdef680aec2088df30881aa8a06abedc7bbddaa70bbd9174302e26d96388a593cb7ec2bf3053bd610a406449bd';

    const DATA = [
        'price' => 10000
    ];

    /**
     * Test to decode data for MinterPriceVoteTx
     */
    public function testDecode(): void
    {
        $tx = new MinterTx(self::VALID_SIGNATURE);
        $this->assertSame($tx->data, self::DATA);
        $this->assertSame(self::MINTER_ADDRESS, $tx->from);
    }

    /**
     * Test signing MinterEditCoinOwnerTx
     */
    public function testSign(): void
    {
        $signature = $this->makeTransaction()->sign(self::PRIVATE_KEY);
        $this->assertSame($signature, self::VALID_SIGNATURE);
    }

    /**
     * @return MinterTx
     */
    private function makeTransaction(): MinterTx
    {
        return new MinterTx([
            'nonce'   => 10,
            'chainId' => MinterTx::MAINNET_CHAIN_ID,
            'type'    => MinterPriceVoteTx::TYPE,
            'data'    => self::DATA
        ]);
    }
}