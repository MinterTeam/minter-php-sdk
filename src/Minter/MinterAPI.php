<?php

namespace Minter;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
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

    /** @var float */
    const HTTP_DEFAULT_CONNECT_TIMEOUT = 15.0;

    /** @var float */
    const HTTP_DEFAULT_TIMEOUT = 30.0;

    /**
     * MinterAPI constructor.
     * @param $node
     */
    public function __construct($node)
    {
        if ($node instanceof Client) {
            $this->setClient($node);
        } else {
            $client = $this->createDefaultHttpClient($node);
            $this->setClient($client);
        }
    }

    /**
     * @param string $baseUri
     * @return Client
     */
    public function createDefaultHttpClient(string $baseUri): Client
    {
        return new Client([
                              'base_uri'        => $baseUri,
                              'connect_timeout' => self::HTTP_DEFAULT_CONNECT_TIMEOUT,
                              'timeout'         => self::HTTP_DEFAULT_TIMEOUT,
                          ]);
    }

    /**
     * Get status of node
     *
     * @return \stdClass
     * @throws Exception
     * @throws GuzzleException
     */
    public function getStatus(): \stdClass
    {
        return $this->get('status');
    }

    /**
     * This endpoint shows candidate’s info by provided public_key.
     * It will respond with 404 code if candidate is not found.
     *
     * @param string   $publicKey
     * @param null|int $height
     * @return \stdClass
     * @throws Exception
     * @throws GuzzleException
     */
    public function getCandidate(string $publicKey, ?int $height = null): \stdClass
    {
        if ($height) {
            $params = ['height' => $height];
        }

        return $this->get('candidate/' . $publicKey, $params ?? null);
    }

    /**
     * Returns list of active validators
     *
     * @param null|int $height
     * @param int|null $page
     * @param int|null $perPage
     * @return \stdClass
     * @throws GuzzleException
     */
    public function getValidators(?int $height = null, ?int $page = 1, ?int $perPage = null): \stdClass
    {
        $params = ['page' => $page];

        if ($height) {
            $params['height'] = $height;
        }

        if ($perPage) {
            $params['per_page'] = $perPage;
        }

        return $this->get('validators', $params);
    }

    /**
     * Returns the balance of given account and the number of outgoing transaction.
     *
     * @param string   $address
     * @param null|int $height
     * @param bool     $delegated
     * @return \stdClass
     * @throws GuzzleException
     */
    public function getBalance(string $address, ?int $height = null, bool $delegated = false): \stdClass
    {
        $params = ['delegated' => $delegated];
        if ($height) {
            $params['height'] = $height;
        }

        return $this->get('address/' . $address, $params);
    }

    /**
     * Returns addresses balances.
     *
     * @param array    $addresses
     * @param int|null $height
     * @param bool     $delegated
     * @return \stdClass
     * @throws GuzzleException
     */
    public function getAddresses(array $addresses, ?int $height = null, bool $delegated = false): \stdClass
    {
        $params = ['addresses' => json_encode($addresses)];

        if ($height) {
            $params['height'] = $height;
        }

        return $this->get('addresses', $params);
    }

    /**
     * Returns nonce.
     *
     * @param string $address
     * @return int
     * @throws Exception
     * @throws GuzzleException
     */
    public function getNonce(string $address): int
    {
        return $this->getBalance($address)->transaction_count + 1;
    }

    /**
     * Sends transaction to the Minter Network.
     *
     * @param string $tx
     * @return \stdClass
     * @throws Exception
     * @throws GuzzleException
     */
    public function send(string $tx): \stdClass
    {
        return $this->post('send_transaction', ['tx' => $tx]);
    }

    /**
     * Returns transaction info.
     *
     * @param string $hash
     * @return \stdClass
     * @throws Exception
     * @throws GuzzleException
     */
    public function getTransaction(string $hash): \stdClass
    {
        return $this->get('transaction/' . $hash);
    }

    /**
     * @param int       $height
     * @param bool|null $failedTxs
     * @return \stdClass
     * @throws GuzzleException
     */
    public function getBlock(int $height, ?bool $failedTxs = false): \stdClass
    {
        return $this->get('block/' . $height, ($failedTxs ? ['failed_txs' => true] : null));
    }

    /**
     * Returns events at given height.
     *
     * @param int $height
     * @return \stdClass
     * @throws Exception
     * @throws GuzzleException
     */
    public function getEvents(int $height): \stdClass
    {
        return $this->get('events/' . $height);
    }

    /**
     * Returns list of candidates.
     *
     * @param null|int  $height
     * @param bool|null $includeStakes
     * @param string    $status
     * @return \stdClass
     * @throws GuzzleException
     */
    public function getCandidates(?int $height = null, ?bool $includeStakes = false, string $status = 'all'): \stdClass
    {
        $params = ['status' => $status];

        if ($includeStakes) {
            $params['include_stakes'] = 'true';
        }

        if ($height) {
            $params['height'] = $height;
        }

        return $this->get('candidates', $params);
    }

    /**
     * Returns information about coin.
     * Note: this method does not return information about base coins (MNT and BIP).
     *
     * @param null|int $height
     * @param string   $symbol
     * @return \stdClass
     * @throws Exception
     * @throws GuzzleException
     */
    public function getCoinInfo(string $symbol, ?int $height = null): \stdClass
    {
        if ($height) {
            $params['height'] = $height;
        }

        return $this->get('coin_info/' . $symbol, $params ?? null);
    }

    /**
     * Return estimate of sell coin transaction.
     *
     * @param string   $coinToSell
     * @param string   $valueToSell
     * @param string   $coinToBuy
     * @param null|int $height
     * @param string   $swapFrom
     * @return \stdClass
     * @throws Exception
     * @throws GuzzleException
     */
    public function estimateCoinSell(
        string $coinToSell,
        string $valueToSell,
        string $coinToBuy,
        ?int   $height = null,
        string $swapFrom = 'optimal'
    ): \stdClass {
        $params = [
            'coin_to_sell'  => $coinToSell,
            'value_to_sell' => $valueToSell,
            'coin_to_buy'   => $coinToBuy,
            'swap_from'     => $swapFrom
        ];

        if ($height) {
            $params['height'] = $height;
        }

        return $this->get('estimate_coin_sell', $params);
    }

    /**
     * Return estimate of sell all coin transaction.
     *
     * @param string   $coinToSell
     * @param string   $valueToSell
     * @param string   $coinToBuy
     * @param int|null $height
     * @param string   $swapFrom
     * @return \stdClass
     * @throws GuzzleException
     */
    public function estimateCoinSellAll(
        string $coinToSell,
        string $valueToSell,
        string $coinToBuy,
        ?int   $height = null,
        string $swapFrom = 'optimal'
    ): \stdClass {
        $params = [
            'coin_to_sell'  => $coinToSell,
            'value_to_sell' => $valueToSell,
            'coin_to_buy'   => $coinToBuy,
            'swap_from'     => $swapFrom
        ];

        if ($height) {
            $params['height'] = $height;
        }

        return $this->get('estimate_coin_sell_all', $params);
    }

    /**
     * Return estimate of buy coin transaction.
     *
     * @param string   $coinToSell
     * @param string   $valueToBuy
     * @param string   $coinToBuy
     * @param null|int $height
     * @param string   $swapFrom
     * @return \stdClass
     * @throws Exception
     * @throws GuzzleException
     */
    public function estimateCoinBuy(
        string $coinToSell,
        string $valueToBuy,
        string $coinToBuy,
        ?int   $height = null,
        string $swapFrom = 'optimal'
    ): \stdClass {
        $params = [
            'coin_to_sell' => $coinToSell,
            'value_to_buy' => $valueToBuy,
            'coin_to_buy'  => $coinToBuy,
            'swap_from'    => $swapFrom

        ];

        if ($height) {
            $params['height'] = $height;
        }

        return $this->get('estimate_coin_buy', $params);
    }

    /**
     * Return estimate of transaction.
     *
     * @param string   $tx
     * @param int|null $height
     * @return \stdClass
     * @throws GuzzleException
     */
    public function estimateTxCommission(string $tx, ?int $height = null): \stdClass
    {
        return $this->get('estimate_tx_commission/' . $tx, ($height ? ['height' => $height] : null));
    }

    /**
     * Get transactions by query.
     *
     * @param string   $query
     * @param int|null $page
     * @param int|null $perPage
     * @return \stdClass
     * @throws Exception
     * @throws GuzzleException
     */
    public function getTransactions(string $query, ?int $page = null, ?int $perPage = null): \stdClass
    {
        $params = ['query' => $query];

        if ($page) {
            $params['page'] = $page;
        }

        if ($perPage) {
            $params['per_page'] = $perPage;
        }


        return $this->get('transactions', $params);
    }

    /**
     * Returns unconfirmed transactions.
     *
     * @param int|null $limit
     * @return \stdClass
     * @throws Exception
     * @throws GuzzleException
     */
    public function getUnconfirmedTxs(?int $limit = null): \stdClass
    {
        return $this->get('unconfirmed_txs', ($limit ? ['limit' => $limit] : null));
    }

    /**
     * Returns current max gas price.
     *
     * @param int|null $height
     * @return \stdClass
     * @throws Exception
     * @throws GuzzleException
     */
    public function getMaxGasPrice(?int $height = null): \stdClass
    {
        return $this->get('max_gas', ($height ? ['height' => $height] : null));
    }

    /**
     * Returns current min gas price.
     *
     * @return \stdClass
     * @throws Exception
     * @throws GuzzleException
     */
    public function getMinGasPrice(): \stdClass
    {
        return $this->get('min_gas_price');
    }

    /**
     * Returns missed blocks by validator public key.
     *
     * @param string   $pubKey
     * @param int|null $height
     * @return \stdClass
     * @throws Exception
     * @throws GuzzleException
     */
    public function getMissedBlocks(string $pubKey, ?int $height = null): \stdClass
    {
        return $this->get('missed_blocks/' . $pubKey, ($height ? ['height' => $height] : null));
    }

    /**
     * Return network genesis.
     *
     * @return \stdClass
     * @throws GuzzleException
     */
    public function getGenesis(): \stdClass
    {
        return $this->get('genesis');
    }

    /**
     * Return node network information.
     *
     * @return \stdClass
     * @throws GuzzleException
     */
    public function getNetworkInfo(): \stdClass
    {
        return $this->get('net_info');
    }

    /**
     * @param int      $id
     * @param int|null $height
     * @return \stdClass
     * @throws GuzzleException
     */
    public function getCoinInfoByID(int $id, ?int $height = null): \stdClass
    {
        return $this->get('coin_info_by_id/' . $id, ($height ? ['height' => $height] : null));
    }

    /**
     * @param int|null $height
     * @return \stdClass
     * @throws GuzzleException
     */
    public function getHalts(?int $height = null): \stdClass
    {
        return $this->get('halts', ($height ? ['height' => $height] : null));
    }

    /**
     * @param string|null $address
     * @param string|null $coin
     * @return \stdClass
     */
    public function getFrozen(?string $address = null, ?string $coin = null): \stdClass
    {
        $params = [];

        if ($address) {
            $params['address'] = $address;
        }

        if ($coin) {
            $params['coin'] = $coin;
        }

        return $this->get('frozen', $params);
    }

    /**
     * @param string      $address
     * @param string|null $publicKey
     * @param int|null    $height
     * @return \stdClass
     * @throws GuzzleException
     */
    public function getWaitlist(string $address, ?string $publicKey = null, ?int $height = null): \stdClass
    {
        $params = [];

        if ($height) {
            $params['height'] = $height;
        }

        if ($publicKey) {
            $params['publicKey'] = $publicKey;
        }

        return $this->get('waitlist/' . $address, $params);
    }

    /**
     * @param int|null $height
     * @return \stdClass
     * @throws GuzzleException
     */
    public function getPriceCommissions(?int $height = null): \stdClass
    {
        return $this->get('price_commissions', ($height ? ['height' => $height] : null));
    }

    /**
     * @param int $height
     * @return \stdClass
     * @throws GuzzleException
     */
    public function getPriceVotes(int $height): \stdClass
    {
        return $this->get('price_votes/' . $height);
    }

    /**
     * @param string   $coin0
     * @param string   $coin1
     * @param int|null $height
     * @return \stdClass
     * @throws GuzzleException
     */
    public function getSwapPool(string $coin0, string $coin1, ?int $height = null): \stdClass
    {
        return $this->get('swap_pool/' . $coin0 . '/' . $coin1, ($height ? ['height' => $height] : null));
    }

    /**
     * @param string   $coin0
     * @param string   $coin1
     * @param string   $provider
     * @param int|null $height
     * @return \stdClass
     * @throws GuzzleException
     */
    public function getSwapPoolProvider(string $coin0, string $coin1, string $provider, ?int $height = null): \stdClass
    {
        return $this->get('swap_pool/' . $coin0 . '/' . $coin1 . '/' . $provider, ($height ? ['height' => $height] : null));
    }

    /**
     * @param array    $ids
     * @param int|null $height
     * @return \stdClass
     * @throws GuzzleException
     */
    public function getLimitOrders(array $ids, ?int $height = null): \stdClass
    {
        $params = ['ids' => $ids];
        if ($height) {
            $params['height'] = $height;
        }

        return $this->get('limit_orders', $params);
    }

    /**
     * @param int      $limitOrderId
     * @param int|null $height
     * @return \stdClass
     * @throws GuzzleException
     */
    public function getLimitOrder(int $limitOrderId, ?int $height = null): \stdClass
    {
        return $this->get('limit_order/' . $limitOrderId, ($height ? ['height' => $height] : null));
    }

    /**
     * @param string   $sellCoin
     * @param string   $buyCoin
     * @param int|null $limit
     * @param int|null $height
     * @return \stdClass
     * @throws GuzzleException
     */
    public function getLimitOrdersByCoins(
        string $sellCoin,
        string $buyCoin,
        int    $limit = null,
        ?int   $height = null
    ): \stdClass {
        $params = [];
        if ($limit) {
            $params['limit'] = $limit;
        }

        if ($height) {
            $params['height'] = $height;
        }

        return $this->get('limit_orders/' . $sellCoin . '/' . $buyCoin, $params);
    }
}
