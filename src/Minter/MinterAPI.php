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
     * @return \stdClass
     * @throws \Exception
     */
    public function getCandidate(string $publicKey): \stdClass
    {
        return $this->get('/candidate', ['pubkey' => $publicKey]);
    }

    /**
     * Returns list of active validators
     *
     * @return \stdClass
     * @throws \Exception
     */
    public function getValidators(): \stdClass
    {
        return $this->get('/validators');
    }

    /**
     * Returns the balance of given account and the number of outgoing transaction.
     *
     * @param string $address
     * @return \stdClass
     * @throws \Exception
     */
    public function getBalance(string $address): \stdClass
    {
        return $this->get('/address', ['address' => $address]);
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
     * @return \stdClass
     * @throws \Exception
     */
    public function getCandidates(): \stdClass
    {
        return $this->get('/candidates');
    }

    /**
     * Returns information about coin.
     * Note: this method does not return information about base coins (MNT and BIP).
     *
     * @param string $symbol
     * @return \stdClass
     */
    public function getCoinInfo(string $symbol): \stdClass
    {
        return $this->get('/coin_info', ['symbol' => $symbol]);
    }

    /**
     * Return estimate of sell coin transaction.
     *
     * @param string $coinToSell
     * @param string $valueToSell
     * @param string $coinToBuy
     * @return \stdClass
     * @throws \Exception
     */
    public function estimateCoinSell(string $coinToSell, string $valueToSell, string $coinToBuy): \stdClass
    {
        return $this->get('/estimate_coin_sell', [
            'coin_to_sell' => $coinToSell,
            'value_to_sell' => $valueToSell,
            'coin_to_buy' => $coinToBuy
        ]);
    }

    /**
     * Return estimate of buy coin transaction.
     *
     * @param string $coinToSell
     * @param string $valueToBuy
     * @param string $coinToBuy
     * @return \stdClass
     * @throws \Exception
     */
    public function estimateCoinBuy(string $coinToSell, string $valueToBuy, string $coinToBuy): \stdClass
    {
        return $this->get('/estimate_coin_buy', [
            'coin_to_sell' => $coinToSell,
            'value_to_buy' => $valueToBuy,
            'coin_to_buy' => $coinToBuy
        ]);
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
}
