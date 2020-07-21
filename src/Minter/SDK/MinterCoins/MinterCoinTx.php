<?php

namespace Minter\SDK\MinterCoins;

use Minter\Contracts\MinterTxInterface;
use Web3p\RLP\Buffer;
use Web3p\RLP\RLP;

/**
 * Class MinterCoinTx
 * @package Minter\SDK\MinterCoins
 */
abstract class MinterCoinTx implements MinterTxInterface
{
    public const TYPE_TO_DATA = [
        MinterSendCoinTx::TYPE         => MinterSendCoinTx::class,
        MinterSellCoinTx::TYPE         => MinterSellCoinTx::class,
        MinterSellAllCoinTx::TYPE      => MinterSellAllCoinTx::class,
        MinterBuyCoinTx::TYPE          => MinterBuyCoinTx::class,
        MinterCreateCoinTx::TYPE       => MinterCreateCoinTx::class,
        MinterDeclareCandidacyTx::TYPE => MinterDeclareCandidacyTx::class,
        MinterDelegateTx::TYPE         => MinterDelegateTx::class,
        MinterUnbondTx::TYPE           => MinterUnbondTx::class,
        MinterRedeemCheckTx::TYPE      => MinterRedeemCheckTx::class,
        MinterSetCandidateOnTx::TYPE   => MinterSetCandidateOnTx::class,
        MinterSetCandidateOffTx::TYPE  => MinterSetCandidateOffTx::class,
        MinterCreateMultisigTx::TYPE   => MinterCreateMultisigTx::class,
        MinterMultiSendTx::TYPE        => MinterMultiSendTx::class,
        MinterEditCandidateTx::TYPE    => MinterEditCandidateTx::class,
        MinterRecreateCoinTx::TYPE     => MinterRecreateCoinTx::class,
        MinterChangeOwnerTx::TYPE      => MinterChangeOwnerTx::class,
    ];

    /**
     * Get transaction data fee
     *
     * @return int
     */
    public function getFee()
    {
        return static::COMMISSION;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return static::TYPE;
    }

    /**
     * @return Buffer
     */
    public function encode(): Buffer
    {
        $rlp = new RLP();
        return $rlp->encode($this->encodeData());
    }

    /**
     * Prepare data tx for signing
     *
     * @return array
     */
    abstract function encodeData(): array;

    /**
     * Prepare output tx data
     */
    abstract function decodeData();
}