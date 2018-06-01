<?php

namespace Minter\SDK;

use Minter\Interfaces\MinterTxInterface;

abstract class MinterCoinTx implements MinterTxInterface
{
    /**
     * Send coin tx data
     *
     * @var array
     */
    public $data;

    /**
     * MinterSendCoinTx constructor.
     * @param $data
     * @throws \Exception
     */
    public function __construct(array $data, $convert = false)
    {
        if(!$convert) {
            foreach ($this->data as $key => $value) {
                if (!isset($data[$key])) {
                    throw new \Exception('Undefined element "' . $key . '" in tx data');
                }

                $this->data[$key] = $data[$key];
            }

            $this->data = $this->encode();
        }
        else {
            $this->data = $this->decode($data);
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
     * Decode from hex
     *
     * @param string $hex
     * @return string
     */
    protected function hex_decode(string $hex)
    {
        return gmp_strval(gmp_init($hex, 16), 10);
    }

    /**
     * Prepare data tx for signing
     *
     * @return array
     */
    abstract function encode(): array;

    /**
     * Prepare output tx data
     *
     * @param array $txData
     * @return array
     */
    abstract function decode(array $txData): array;
}