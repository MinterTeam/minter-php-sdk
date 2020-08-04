<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterSendCoinTx;
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
    const PRIVATE_KEY = '4daf02f92bf760b53d3c725d6bcc0da8e55d27ba5350c78d3a88f873e502bd6e';

    /**
     * Predefined minter address
     */
    const MINTER_ADDRESS = 'Mx67691076548b20234461ff6fd2bc9c64393eb8fc';

    /**
     * Predefined valid signature
     */
    const VALID_SIGNATURE = '0xf86f01010180019fde809467691076548b20234461ff6fd2bc9c64393eb8fc872bdbb64bc09000808001b845f8431ca08be3f0c3aecc80ec97332e8aa39f20cd9e735092c0de37eb726d8d3d0a255a66a02040a1001d1a9116317eb24aa7ee4730ed980bd08a1fc0adb4e7598425178d3a';


    /**
     * Predefined data
     */
    const DATA = [
        'coin'  => 0,
        'to'    => 'Mx67691076548b20234461ff6fd2bc9c64393eb8fc',
        'value' => '0.012345'
    ];

    /**
     * Test to decode data for MinterSendCoinTx
     */
    public function testDecode(): void
    {
        $tx = new MinterTx(self::VALID_SIGNATURE);

        $this->assertSame($tx->data, self::DATA);
        $this->assertSame($tx->from, self::MINTER_ADDRESS);
    }

    /**
     * Test signing MinterSendCoinTx
     */
    public function testSign(): void
    {
        $tx = new MinterTx([
           'nonce'    => 1,
           'chainId'  => MinterTx::MAINNET_CHAIN_ID,
           'gasPrice' => 1,
           'type'     => MinterSendCoinTx::TYPE,
           'data'     => self::DATA
       ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
