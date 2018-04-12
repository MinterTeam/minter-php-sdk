<?php

namespace Minter;

use Minter\Traits\Http;

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
    public function __construct($nodeUrl = false)
    {
        if(!$nodeUrl) {
            $nodeUrl = env('MINTER_BLOCKCHAIN_NODE_URL');
        }

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
        return $this->post('/api/getBalance', ['address' => $address]);
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
        $response = $this->post('/api/getTransactionCount', ['address' => $address]);

        return $response->result + 1;
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
        return $this->post('/api/getTransactions', ['query' => "tx.from='" . $address . "'"]);
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
        return $this->post('/api/getTransactions', ['query' => "tx.to='" . $address . "'"]);
    }
}