<?php

namespace Minter;

use Minter\Library\Helper;
use Minter\Library\Http;

/**
 * Class MinterAPI
 * @package Minter
 */
class MinterAPI
{
    /**
     * http requests
     */
    use Http;

    /**
     * MinterAPI constructor.
     * @param bool $nodeUrl
     */
    public function __construct($nodeUrl)
    {
        $this->setApiUrl($nodeUrl);
    }

    /**
     * Get balance by address
     *
     * @param string $address
     * @return \stdClass
     * @throws \Exception
     */
    public function getBalance(string $address): \stdClass
    {
        return $this->get('/api/balance/' . $address);
    }

    /**
     * Get nonce
     *
     * @param string $address
     * @return int
     * @throws \Exception
     */
    public function getNonce(string $address): int
    {
        $response = $this->get('/api/transactionCount/' . $address);

        return $response->result->count + 1;
    }

    /**
     * Send tx
     *
     * @param string $tx
     * @return \stdClass
     * @throws \Exception
     */
    public function send(string $tx): \stdClass
    {
        return $this->post('/api/sendTransaction', ['transaction' => $tx]);
    }

    /**
     * Get outcoming transactions by address
     *
     * @param string $address
     * @return \stdClass
     * @throws \Exception
     */
    public function getTransactionsFrom(string $address): \stdClass
    {
        // prepare address for api request
        $address = strtolower(Helper::removeWalletPrefix($address));

        return $this->get('/api/transactions', ['query' => "tx.from='" . $address . "'"]);
    }

    /**
     * Get incoming transactions by address
     *
     * @param string $address
     * @return \stdClass
     * @throws \Exception
     */
    public function getTransactionsTo(string $address): \stdClass
    {
        // prepare address for api request
        $address = strtolower(Helper::removeWalletPrefix($address));

        return $this->get('/api/transactions', ['query' => "tx.to='" . $address . "'"]);
    }

    /**
     * Get status of node
     *
     * @return \stdClass
     * @throws \Exception
     */
    public function getStatus(): \stdClass
    {
        return $this->get('/api/status');
    }

    /**
     * Returns list of active validators
     *
     * @return \stdClass
     * @throws \Exception
     */
    public function getValidators(): \stdClass
    {
        return $this->get('/api/validators');
    }

    /**
     * Return estimate of buy coin transaction
     *
     * @param string $coinToSell
     * @param string $valueToBuy
     * @param string $coinToBuy
     * @return \stdClass
     * @throws \Exception
     */
    public function estimateCoinBuy(string $coinToSell, string $valueToBuy, string $coinToBuy): \stdClass
    {
        return $this->get('/api/estimateCoinBuy', [
            'coin_to_sell' => $coinToSell,
            'value_to_buy' => $valueToBuy,
            'coin_to_buy' => $coinToBuy
        ]);
    }

    /**
     * Return estimate of sell coin transaction
     *
     * @param string $coinToSell
     * @param string $valueToSell
     * @param string $coinToBuy
     * @return \stdClass
     * @throws \Exception
     */
    public function estimateCoinSell(string $coinToSell, string $valueToSell, string $coinToBuy): \stdClass
    {
        return $this->get('/api/estimateCoinSell', [
            'coin_to_sell' => $coinToSell,
            'value_to_sell' => $valueToSell,
            'coin_to_buy' => $coinToBuy
        ]);
    }

    /**
     * Returns information about coin.
     *
     * @param string $coin
     * @return \stdClass
     * @throws \Exception
     */
    public function getCoinInfo(string $coin): \stdClass
    {
        return $this->get('/api/coinInfo/' . $coin);
    }

    /**
     * Returns block data at given height.
     *
     * @param int $height
     * @param bool $withEvents
     * @return \stdClass
     * @throws \Exception
     */
    public function getBlock(int $height, $withEvents = false): \stdClass
    {
        return $this->get('/api/block/' . $height . ($withEvents ? '?withEvents=true' : ''));
    }

    /**
     * Returns transaction info
     *
     * @param string $hash
     * @return \stdClass
     * @throws \Exception
     */
    public function getTransaction(string $hash): \stdClass
    {
        return $this->get('/api/transaction/' . $hash);
    }

    /**
     * Returns amount of base coin (BIP or MNT) existing in the network. It counts block rewards, premine and relayed rewards.
     *
     * @param int $height
     * @return \stdClass
     * @throws \Exception
     */
    public function getBaseCoinVolume(int $height): \stdClass
    {
        return $this->get('/api/bipVolume', ['height' => $height]);
    }

    /**
     * Returns candidate’s info by provided public_key. It will respond with 404 code if candidate is not found.
     *
     * @param string $publicKey
     * @return \stdClass
     * @throws \Exception
     */
    public function getCandidate(string $publicKey): \stdClass
    {
        return $this->get('/api/candidate/' . $publicKey);
    }

    /**
     * Returns list of candidates
     *
     * @return \stdClass
     * @throws \Exception
     */
    public function getCandidates(): \stdClass
    {
        return $this->get('/api/candidates');
    }
}
