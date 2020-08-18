<?php
declare(strict_types=1);

use Minter\SDK\MinterCoins\MinterSendCoinTx;
use PHPUnit\Framework\TestCase;
use Minter\SDK\MinterTx;

/**
 * Class for testing MinterMultisigTx
 */
final class MinterMultisigTxTest extends TestCase
{
    const SENDER_ADDRESS = 'Mx67691076548b20234461ff6fd2bc9c64393eb8fd';

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
    const VALID_TX = '0xf901130101018001a0df809467691076548b20234461ff6fd2bc9c64393eb8fc8801b4fbd92b5f8000808002b8e8f8e69467691076548b20234461ff6fd2bc9c64393eb8fdf8cff8431ca0e710b173287ec60a03bc8bdd2821045b5a5be1a08cfa1f7abc4fd22ed093b99ca03c18e46bbfa4b221d8b951ba3a677bb0ed1fdbd0c8e635f324bef49ba5ab19f9f8431ba05bda1e622619a67ace87e23ed16d4103a89558bca975c1212dee5c6211b2ab39a079e69519f43278c36cbf80a138f2deb695110ffefb88373af4046902e600b234f8431ba0b6fe63fa00cea4584bb24102ca79aac547358749f338e55b361af0fd116f5fcda05ad8e871ee0fef610a832d1d2eaf352bc1ee16c6e4f0b013bb2b25947dac77b7';

    /**
     * Test signing.
     */
    public function testSign()
    {
        $tx = new MinterTx(1, new MinterSendCoinTx(0, 'Mx67691076548b20234461ff6fd2bc9c64393eb8fc', '0.123'));
        $signedTx = $tx->signMultisig(self::SENDER_ADDRESS, self::PRIVATE_KEYS);
        $this->assertEquals(self::VALID_TX, $signedTx);
    }

    /**
     * Test get decode.
     */
    public function testDecode()
    {
        $tx = MinterTx::decode(self::VALID_TX);
        $this->assertEquals(self::SENDER_ADDRESS, $tx->getSenderAddress());
    }

    /**
     * Test sign multisig with ready signatures.
     */
    public function testSignBySignatures()
    {
        $tx = new MinterTx(1, new MinterSendCoinTx(0, 'Mx67691076548b20234461ff6fd2bc9c64393eb8fc', '0.123'));

        $signatures = [];
        foreach (self::PRIVATE_KEYS as $privateKey) {
            $signatures[] = $tx->createSignature($privateKey);
        }

        $signedTx = $tx->signMultisigBySigns(self::SENDER_ADDRESS, $signatures);
        $this->assertEquals(
            '0xf901130101018001a0df809467691076548b20234461ff6fd2bc9c64393eb8fc8801b4fbd92b5f8000808002b8e8f8e69467691076548b20234461ff6fd2bc9c64393eb8fdf8cff8431ca097862be1ae46720c7f2821f5a7773586ed81b3c79da9a8d7fd764c4eab8ee670a02a59fa798139b6cb5a198f20410cbb7ddde32381023e4c70627e5d0b0b6cb8bff8431ba0cd2b8400aed5dc66fafb53936eb55da690bc7862ce5251e063fb0520569898afa03e03b15d26931433a093d49cc423b7688b63413b634ff033a6e25d44d9b8aa2bf8431ba073bbb4bcf06eb19781c5283c7e787d705e6e3c933d3b4a116dc2a43a2f3d24cda01bfbbb35adb9dde9ae8e2929336cd9fb79d6a6e8a904d9e8ecc0fe0835ef8233',
            $signedTx
        );
    }
}
