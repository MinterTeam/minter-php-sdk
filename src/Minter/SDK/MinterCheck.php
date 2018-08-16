<?php

namespace Minter\SDK;

use Elliptic\EC;
use Minter\Library\ECDSA;
use Minter\Library\Helper;
use Web3p\RLP\RLP;

/**
 * Class MinterCheck
 * @package Minter\SDK
 */
class MinterCheck
{
    /**
     * @var RLP
     */
    protected $rlp;

    /**
     * @var string
     */
    protected $minterAddress;

    /**
     * Check passphrase
     *
     * @var string
     */
    protected $passphrase;

    /**
     * Check structure
     *
     * @var array
     */
    protected $structure = [
        'nonce',
        'dueBlock',
        'coin',
        'value',
        'lock',
        'v',
        'r',
        's'
    ];

    /**
     * Define RLP, password and encode/decode check
     *
     * MinterCheck constructor.
     * @param $checkOrAddress
     * @param string $passphrase
     */
    public function __construct($checkOrAddress, string $passphrase)
    {
        $this->rlp = new RLP;

        $this->passphrase = $passphrase;

        if(is_array($checkOrAddress)) {
            $this->structure = $this->defineProperties($checkOrAddress);
        }

        if(is_string($checkOrAddress)) {
            $this->minterAddress = $checkOrAddress;
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
        return $this->structure[$name];
    }

    /**
     *
     *
     * @param string $privateKey
     * @return string
     */
    public function sign(string $privateKey): string
    {
        // create message hash and passphrase by first 4 fields
        $msgHash = $this->serialize(
            array_slice($this->structure, 0, 4)
        );

        $passphrase = hash('sha256', $this->passphrase);

        // create elliptic curve and sign
        $signature = ECDSA::sign($msgHash, $passphrase);

        // define lock field
        $this->structure['lock'] = $this->formatLockFromSignature($signature);

        // create message hash with lock field
        $msgHashWithLock = $this->serialize(
            array_slice($this->structure, 0, 5)
        );

        $this->structure = array_merge($this->structure, ECDSA::sign($msgHashWithLock, $privateKey));

        // rlp encode data and add Minter wallet prefix
        return MinterPrefix::CHECK . $this->rlp->encode($this->structure)->toString('hex');

    }

    /**
     * Create proof by address and passphrase
     *
     * @return string
     * @throws \Exception
     */
    public function createProof(): string
    {
        if(!$this->minterAddress) {
            throw new \Exception('Minter address is not defined');
        }

        // create msg hash of address
        $minterAddress = [hex2bin(Helper::removeWalletPrefix($this->minterAddress))];
        $addressHash = $this->serialize($minterAddress);

        // get SHA 256 hash of password and create EC signature
        $passphrase = hash('sha256', $this->passphrase);
        $signature = ECDSA::sign($addressHash, $passphrase);

        // return formatted proof
        return bin2hex(
            $this->formatLockFromSignature($signature)
        );
    }

    /**
     * Merge input fields with structure
     *
     * @param array $check
     * @return array
     * @throws \Exception
     */
    protected function defineProperties(array $check): array
    {
        $structure = array_flip($this->structure);

        if(!$this->validateFields($check)) {
            throw new \Exception('Invalid fields');
        }

        return array_merge($structure, $this->encode($check));
    }

    /**
     * Encode input fields
     *
     * @param array $check
     * @return array
     */
    protected function encode(array $check): array
    {
        return [
            'nonce' => $check['nonce'],

            'dueBlock' => $check['dueBlock'],

            'coin' => MinterConverter::convertCoinName($check['coin']),

            'value' => MinterConverter::convertValue($check['value'], 'pip'),
        ];
    }

    /**
     * Create message Keccak hash from structure fields limited by number of fields
     *
     * @return array
     */
    protected function serialize($data): string
    {
        // create msg hash with lock field
        $msgHash = $this->rlp->encode($data)->toString('hex');

        return Helper::createKeccakHash($msgHash);
    }

    /**
     * Validate that input fields are correct
     *
     * @param array $fields
     * @return bool
     */
    protected function validateFields(array $fields): bool
    {
        $structure = array_flip($this->structure);

        foreach ($fields as $field => $fieldValue) {
            if(!isset($structure[$field])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Prepare lock field
     *
     * @param array $signature
     * @return string
     */
    protected function formatLockFromSignature(array $signature): string
    {
        $recovery = $signature['v'] === 1 ? '01' : '00';

        return $signature['r'] . $signature['s'] . hex2bin($recovery);
    }
}