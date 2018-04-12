<?php

namespace Minter\SDK;

use Minter\Interfaces\MinterTxInterface;
use Web3p\RLP\RLP;
use Web3p\RLP\Buffer;

abstract class MinterCoinTx implements MinterTxInterface
{
    /**
     * rlp
     *
     * @var RLP
     */
    protected $rlp;

    /**
     * Send coin tx data
     *
     * @var array
     */
    protected $data;

    /**
     * MinterSendCoinTx constructor.
     * @param $data
     * @throws \Exception
     */
    public function __construct($data, $convert = false)
    {
        $this->rlp = new RLP();

        if(!$convert) {
            foreach ($this->data as $key => $value) {
                if (!isset($data[$key])) {
                    throw new \Exception('Undefined element "' . $key . '" in tx data');
                }

                $this->data[$key] = $data[$key];
            }
        }
        else {
            $this->data = $this->convertFromHex($data);
        }
    }

    /**
     * Get
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        $method = 'get' . ucfirst($name);

        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], []);
        }

        return $this->data[$name];
    }

    /**
     * RLP encoded tx data
     *
     * @return \Web3p\RLP\Buffer
     */
    abstract function serialize(): Buffer;

    /**
     * Prepare output tx data
     *
     * @param array $txData
     * @return array
     */
    abstract function convertFromHex(array $txData): array;
}