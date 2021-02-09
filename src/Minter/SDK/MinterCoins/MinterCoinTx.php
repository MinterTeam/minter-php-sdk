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
        MinterSendCoinTx::TYPE                => MinterSendCoinTx::class,
        MinterSellCoinTx::TYPE                => MinterSellCoinTx::class,
        MinterSellAllCoinTx::TYPE             => MinterSellAllCoinTx::class,
        MinterBuyCoinTx::TYPE                 => MinterBuyCoinTx::class,
        MinterCreateCoinTx::TYPE              => MinterCreateCoinTx::class,
        MinterDeclareCandidacyTx::TYPE        => MinterDeclareCandidacyTx::class,
        MinterDelegateTx::TYPE                => MinterDelegateTx::class,
        MinterUnbondTx::TYPE                  => MinterUnbondTx::class,
        MinterRedeemCheckTx::TYPE             => MinterRedeemCheckTx::class,
        MinterSetCandidateOnTx::TYPE          => MinterSetCandidateOnTx::class,
        MinterSetCandidateOffTx::TYPE         => MinterSetCandidateOffTx::class,
        MinterCreateMultisigTx::TYPE          => MinterCreateMultisigTx::class,
        MinterMultiSendTx::TYPE               => MinterMultiSendTx::class,
        MinterEditCandidateTx::TYPE           => MinterEditCandidateTx::class,
        MinterRecreateCoinTx::TYPE            => MinterRecreateCoinTx::class,
        MinterEditCoinOwnerTx::TYPE           => MinterEditCoinOwnerTx::class,
        MinterSetHaltBlockTx::TYPE            => MinterSetHaltBlockTx::class,
        MinterEditMultisigTx::TYPE            => MinterEditMultisigTx::class,
        MinterPriceVoteTx::TYPE               => MinterPriceVoteTx::class,
        MinterEditCandidatePublicKeyTx::TYPE  => MinterEditCandidatePublicKeyTx::class,
        MinterAddLiquidityTx::TYPE            => MinterAddLiquidityTx::class,
        MinterRemoveLiquidityTx::TYPE         => MinterRemoveLiquidityTx::class,
        MinterSellSwapPoolTx::TYPE            => MinterSellSwapPoolTx::class,
        MinterBuySwapPoolTx::TYPE             => MinterBuySwapPoolTx::class,
        MinterSellAllSwapPoolTx::TYPE         => MinterSellAllSwapPoolTx::class,
        MinterEditCandidateCommissionTx::TYPE => MinterEditCandidateCommissionTx::class,
        MinterMoveStakeTx::TYPE               => MinterMoveStakeTx::class,
        MinterMintTokenTx::TYPE               => MinterMintTokenTx::class,
        MinterBurnTokenTx::TYPE               => MinterBurnTokenTx::class,
        MinterCreateTokenTx::TYPE             => MinterCreateTokenTx::class,
        MinterRecreateTokenTx::TYPE           => MinterRecreateTokenTx::class,
        MinterPriceCommissionTx::TYPE         => MinterPriceCommissionTx::class,
        MinterCreateSwapPoolTx::TYPE          => MinterCreateSwapPoolTx::class
    ];

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