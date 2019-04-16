<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterSellAllCoinTx;
use Minter\SDK\MinterTx;
use PHPUnit\Framework\TestCase;

/**
 * Class for testing MinterSellAllCoinTx
 */
final class MinterSellAllCoinTxTest extends TestCase
{
    /**
     * Predefined private key
     */
    const PRIVATE_KEY = '07bc17abdcee8b971bb8723e36fe9d2523306d5ab2d683631693238e0f9df142';

    /**
     * Predefined minter address
     */
    const MINTER_ADDRESS = 'Mx31e61a05adbd13c6b625262704bc305bf7725026';

    /**
     * Predefined data
     */
    const DATA = [
        'coinToSell' => 'MNT',
        'coinToBuy' => 'TEST',
        'minimumValueToBuy' => '1'
    ];

    /**
     * Predefined valid signature
     */
    const VALID_SIGNATURE = '0xf87a0102018a4d4e540000000000000003a0df8a4d4e54000000000000008a54455354000000000000880de0b6b3a7640000808001b845f8431ca0b10794a196b6ad2f94e6162613ca9538429dd49ca493594ba9d99f80d2499765a03c1d78e9e04f57336691e8812a16faccb00bf92ac817ab61cd9bf001e9380d47';

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
            'nonce' => 1,
            'chainId' => MinterTx::TESTNET_CHAIN_ID,
            'gasPrice' => 1,
            'gasCoin' => 'MNT',
            'type' => MinterSellAllCoinTx::TYPE,
            'data' => self::DATA,
            'payload' => '',
            'serviceData' => '',
            'signatureType' => MinterTx::SIGNATURE_SINGLE_TYPE
        ]);

        $signature = $tx->sign(self::PRIVATE_KEY);

        $this->assertSame($signature, self::VALID_SIGNATURE);
    }
}
