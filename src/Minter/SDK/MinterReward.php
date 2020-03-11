<?php

namespace Minter\SDK;

/**
 * Class MinterReward
 * @package Minter\SDK
 */
class MinterReward
{
    /**
     * Total blocks for reward
     */
    const TOTAL_BLOCKS_COUNT = 43702612;

    /**
     * First reward
     */
    CONST FIRST_REWARD = 333;

    /*
     * Last reward
     */
    CONST LAST_REWARD = 68;

    /**
     * Start height of blockchain.
     */
    const START_HEIGHT = 5000001;

    /**
     * Get reward by the block number in PIP
     *
     * @param int $blockNumber
     * @return string
     */
    public static function get(int $blockNumber): string
    {
        // check that block number is correct
        if($blockNumber <= 0) {
            throw new \InvalidArgumentException('Block number should be greater than 0');
        }

        $blockNumber += self::START_HEIGHT;

        if($blockNumber > self::TOTAL_BLOCKS_COUNT) {
            return 0;
        }

        if($blockNumber === self::TOTAL_BLOCKS_COUNT) {
            return self::LAST_REWARD;
        }

        $reward = self::formula($blockNumber);
        return $reward;
    }

    /**
     * Calculate reward by formula
     *
     * @param int $blockNumber
     * @return string
     */
    protected static function formula(int $blockNumber): string
    {
        $reward = self::FIRST_REWARD - ($blockNumber / 200000);

        return ceil($reward);
    }
}