<?php

namespace Minter\SDK;

use Minter\Library\ECDSA;
use Minter\Library\Helper;
use Web3p\RLP\Buffer;
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
        'chainId',
        'dueBlock',
        'coin',
        'value',
        'gasCoin',
        'lock',
        'v',
        'r',
        's'
    ];

    /**
     * Define RLP, password and encode/decode check.
     *
     * MinterCheck constructor.
     * @param $checkOrAddress
     * @param string|null $passphrase
     */
    public function __construct($checkOrAddress, ?string $passphrase = null)
    {
        $this->rlp = new RLP;

        if(is_array($checkOrAddress)) {
            $this->structure = $this->defineProperties($checkOrAddress);
        }

        if(is_string($checkOrAddress) && !$passphrase) {
            $this->structure = $this->decode($checkOrAddress);
        }
        else if(is_string($checkOrAddress)) {
            $this->minterAddress = $checkOrAddress;
        }

        $this->passphrase = $passphrase;
    }

    /**
     * Get check structure.
     *
     * @return array
     */
    public function getBody(): array
    {
        return $this->structure;
    }

    /**
     * Get owner address from decoded check.
     *
     * @return string
     */
    public function getOwnerAddress(): string
    {
        return $this->minterAddress;
    }

    /**
     * Sign check.
     *
     * @param string $privateKey
     * @return string
     */
    public function sign(string $privateKey): string
    {
        // create message hash and passphrase by first 4 fields
        $msgHash = $this->serialize(array_slice($this->structure, 0, 6));

        $passphrase = hash('sha256', $this->passphrase);

        // create elliptic curve and sign
        $signature = ECDSA::sign($msgHash, $passphrase);

        // define lock field
        $this->structure['lock'] = hex2bin($this->formatLockFromSignature($signature));

        // create message hash with lock field
        $msgHashWithLock = $this->serialize(array_slice($this->structure, 0, 7));

        // create signature
        $signature = ECDSA::sign($msgHashWithLock, $privateKey);
        $this->structure = array_merge($this->structure, Helper::hex2buffer($signature));

        // rlp encode data and add Minter wallet prefix
        return MinterPrefix::CHECK . $this->rlp->encode($this->structure)->toString('hex');
    }

    /**
     * Create proof by address and passphrase.
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
        return $this->formatLockFromSignature($signature);
    }

    /**
     * Decode check.
     *
     * @param string $check
     * @return array
     */
    protected function decode(string $check): array
    {
        // prepare check string and convert to hex array
        $check = Helper::removePrefix($check, MinterPrefix::CHECK);
        $check = $this->rlp->decode('0x' . $check);
        $check = Helper::rlpArrayToHexArray($check);

        // prepare decoded data
        foreach ($check as $key => $value) {
            $field = $this->structure[$key];

            switch ($field) {
                case 'nonce':
                case 'coin':
                case 'gasCoin':
                    $data[$field] = Helper::hex2str($value);
                    break;

                case 'value':
                    $data[$field] = MinterConverter::convertToBase(Helper::hexDecode($value));
                    break;

                default:
                    $data[$field] = $value;
                    if(in_array($field, ['dueBlock', 'v', 'chainId'])) {
                        $data[$field] = hexdec($value);
                    }
                    break;
            }
        }

        // set owner address
        list($body, $signature) = array_chunk($data, 7, true);
        $this->setOwnerAddress($body, $signature);

        return $data;
    }

    /**
     * Set check owner address.
     *
     * @param array $body
     * @param array $signature
     */
    protected function setOwnerAddress(array $body, array $signature): void
    {
        // encode check to rlp
        $lock = array_pop($body);
        $check = $this->encode($body);
        $check['lock'] = hex2bin($lock);

        // create keccak hash from check
        $msg = $this->serialize($check);

        // recover public key
        $publicKey = ECDSA::recover($msg, $signature['r'], $signature['s'], $signature['v']);
        $publicKey = MinterPrefix::PUBLIC_KEY . $publicKey;

        $this->minterAddress = MinterWallet::getAddressFromPublicKey($publicKey);
    }

    /**
     * Merge input fields with structure.
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
     * Encode input fields.
     *
     * @param array $check
     * @return array
     */
    protected function encode(array $check): array
    {
        return [
            'nonce' => Helper::hexDecode(
                Helper::str2hex($check['nonce'])
            ),

            'chainId' => dechex($check['chainId']),

            'dueBlock' => $check['dueBlock'],

            'coin' => MinterConverter::convertCoinName($check['coin']),

            'value' => MinterConverter::convertToPip($check['value']),

            'gasCoin' => MinterConverter::convertCoinName($check['gasCoin'])
        ];
    }

    /**
     * Create message Keccak hash from structure fields limited by number of fields.
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
     * Validate that input fields are correct.
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

            if($field === 'nonce' && strlen($fieldValue) > 32) {
                return false;
            }
        }

        return true;
    }

    /**
     * Prepare lock field.
     *
     * @param array $signature
     * @return string
     */
    protected function formatLockFromSignature(array $signature): string
    {
        $r = str_pad($signature['r'], 64, '0', STR_PAD_LEFT);
        $s = str_pad($signature['s'], 64, '0', STR_PAD_LEFT);
        $recovery = hexdec($signature['v']) === ECDSA::V_BITS ? '00' : '01';

        return $r . $s. $recovery;
    }
}