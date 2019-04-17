<?php

namespace Minter;

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
     * Get status of node
     *
     * @return \stdClass
     * @throws \Exception
     */
    public function getStatus(): \stdClass
    {
        return $this->get('/status');
    }

    /**
     * This endpoint shows candidate’s info by provided public_key.
     * It will respond with 404 code if candidate is not found.
     *
     * @param string $publicKey
     * @param null|int $height
     * @return \stdClass
     * @throws \Exception
     */
    public function getCandidate(string $publicKey, ?int $height = null): \stdClass
    {
        $params = ['pub_key' => $publicKey];

        if($height) {
            $params['height'] = $height;
        }

        return $this->get('/candidate', $params);
    }

    /**
     * Returns list of active validators
     *
     * @param null|int $height
     * @return \stdClass
     * @throws \Exception
     */
    public function getValidators(?int $height = null): \stdClass
    {
        return $this->get('/validators', ($height ? ['height' => $height] : null));
    }

    /**
     * Returns the balance of given account and the number of outgoing transaction.
     *
     * @param string $address
     * @param null|int $height
     * @return \stdClass
     * @throws \Exception
     */
    public function getBalance(string $address, ?int $height = null): \stdClass
    {
        $params = ['address' => $address];

        if($height) {
            $params['height'] = $height;
        }

        return $this->get('/address', $params);
    }

    /**
     * Returns nonce.
     *
     * @param string $address
     * @return int
     */
    public function getNonce(string $address): int
    {
        return $this->getBalance($address)->result->transaction_count + 1;
    }

    /**
     * Sends transaction to the Minter Network.
     *
     * @param string $tx
     * @return \stdClass
     * @throws \Exception
     */
    public function send(string $tx): \stdClass
    {
        return $this->get('/send_transaction', ['tx' => $tx]);
    }

    /**
     * Returns transaction info.
     *
     * @param string $hash
     * @return \stdClass
     * @throws \Exception
     */
    public function getTransaction(string $hash): \stdClass
    {
        return $this->get('/transaction',  ['hash' => $hash]);
    }

    /**
     * Returns block data at given height.
     *
     * @param int $height
     * @return \stdClass
     * @throws \Exception
     */
    public function getBlock(int $height): \stdClass
    {
        return $this->get('/block', ['height' => $height]);
    }

    /**
     * Returns events at given height.
     *
     * @param int $height
     * @return \stdClass
     */
    public function getEvents(int $height): \stdClass
    {
        return $this->get('/events', ['height' => $height]);
    }

    /**
     * Returns list of candidates.
     *
     * @param null|int $height
     * @return \stdClass
     * @throws \Exception
     */
    public function getCandidates(?int $height = null): \stdClass
    {
        return $this->get('/candidates', ($height ? ['height' => $height] : null));
    }

    /**
     * Returns information about coin.
     * Note: this method does not return information about base coins (MNT and BIP).
     *
     * @param null|int $height
     * @param string $symbol
     * @return \stdClass
     */
    public function getCoinInfo(string $symbol, ?int $height = null): \stdClass
    {
        $params = ['symbol' => $symbol];

        if($height) {
            $params['height'] = $height;
        }

        return $this->get('/coin_info', $params);
    }

    /**
     * Return estimate of sell coin transaction.
     *
     * @param string $coinToSell
     * @param string $valueToSell
     * @param string $coinToBuy
     * @param null|int $height
     * @return \stdClass
     * @throws \Exception
     */
    public function estimateCoinSell(string $coinToSell, string $valueToSell, string $coinToBuy, ?int $height = null): \stdClass
    {
        $params = [
            'coin_to_sell' => $coinToSell,
            'value_to_sell' => $valueToSell,
            'coin_to_buy' => $coinToBuy
        ];

        if($height) {
            $params['height'] = $height;
        }

        return $this->get('/estimate_coin_sell', $params);
    }

    /**
     * Return estimate of buy coin transaction.
     *
     * @param string $coinToSell
     * @param string $valueToBuy
     * @param string $coinToBuy
     * @param null|int $height
     * @return \stdClass
     * @throws \Exception
     */
    public function estimateCoinBuy(string $coinToSell, string $valueToBuy, string $coinToBuy, ?int $height = null): \stdClass
    {
        $params = [
            'coin_to_sell' => $coinToSell,
            'value_to_buy' => $valueToBuy,
            'coin_to_buy' => $coinToBuy
        ];

        if($height) {
            $params['height'] = $height;
        }

        return $this->get('/estimate_coin_buy', $params);
    }

    /**
     * Return estimate of transaction.
     *
     * @param string $tx
     * @return \stdClass
     */
    public function estimateTxCommission(string $tx): \stdClass
    {
        return $this->get('/estimate_tx_commission', ['tx' => $tx]);
    }

    /**
     * Get transactions by query.
     *
     * @param string $query
     * @return \stdClass
     */
    public function getTransactions(string $query): \stdClass
    {
        return $this->get('/transactions', ['query' => $query]);
    }

    /**
     * Returns unconfirmed transactions.
     *
     * @param int|null $limit
     * @return \stdClass
     */
    public function getUnconfirmedTxs(?int $limit = null): \stdClass
    {
        return $this->get('/unconfirmed_txs', ($limit ? ['limit' => $limit] : null));
    }

    /**
     * Returns current max gas price.
     *
     * @param int|null $height
     * @return \stdClass
     */
    public function getMaxGasPrice(?int $height = null): \stdClass
    {
        return $this->get('/max_gas', ($height ? ['height' => $height] : null));
    }

    /**
     * Returns current min gas price.
     *
     * @return \stdClass
     */
    public function getMinGasPrice(): \stdClass
    {
        return $this->get('/min_gas_price');
    }
}
