<p align="center" background="black"><img src="minter-logo.svg" width="400"></p>

## About

This is a pure PHP SDK for working with <b>Minter</b> blockchain

* [Installation](#installing)
* [Minter Api](#using-minterapi)
    - Methods:
	    - [getBalance](#getbalance)
	    - [getNonce](#getnonce)
	    - [send](#send)
	    - [getStatus](#getstatus)
	    - [getValidators](#getvalidators)
	    - [estimateCoinBuy](#estimatecoinbuy)
	    - [estimateCoinSell](#estimatecoinsell)
	    - [getCoinInfo](#getcoininfo)
	    - [getBlock](#getblock)
	    - [getEvents](#getevents)
	    - [getTransaction](#gettransaction)
	    - [getCandidate](#getcandidate)
	    - [getCandidates](#getcandidates)
	    - [estimateTxCommission](#estimatetxcommission)
	    - [getTransactions](#gettransactions)
	    - [getUnconfirmedTxs](#getunconfirmedtxs)
	    - [getMaxGasPrice](#getmaxgasprice)
	    - [getMinGasPrice](#getmingasprice)
	    - [getMissedBlocks](#getmissedblocks)
	- [Error handling](#error-handling)
	
* [Minter SDK](#using-mintersdk)
	- [Sign transaction](#sign-transaction)
		- [SendCoin](#example-3)
		- [SellCoin](#example-4)
		- [SellAllCoin](#example-5)
		- [BuyCoin](#example-6)
		- [CreateCoin](#example-7)
		- [DeclareCandidacy](#example-8)
		- [Delegate](#example-9)
		- [SetCandidateOn](#example-10)
		- [SetCandidateOff](#example-11)
		- [RedeemCheck](#example-12)
		- [Unbond](#example-13)
		- [MultiSend](#example-14)
		- [EditCandidate](#example-15)
	- [Get fee of transaction](#get-fee-of-transaction)
	- [Get hash of transaction](#get-hash-of-transaction)
	- [Decode Transaction](#decode-transaction)
	- [Minter Check](#create-minter-check)
	- [Minter Wallet](#minter-wallet)
	- [Minter Link](#minter-link)
* [Tests](#tests)

## Installing

```bash
composer require minter/minter-php-sdk
```

## Using MinterAPI

You can get all valid responses and full documentation at [Minter Node Api](https://minter-go-node.readthedocs.io/en/latest/api.html)

Create MinterAPI instance

```php
use Minter\MinterAPI;

$nodeUrl = 'https://minter-node-1.testnet.minter.network:8841'; // example of a node url

$api = new MinterAPI($nodeUrl);
```

### getBalance

Returns coins list, balance and transaction count (for nonce) of an address.

``
getBalance(string $minterAddress, ?int $height = null): \stdClass
``

###### Example

```php
$api->getBalance('Mxfe60014a6e9ac91618f5d1cab3fd58cded61ee99')

// {"jsonrpc": "2.0", "id": "", "result": { "balance": { ... }, "transaction_count": "0"}}

```

### getNonce

Returns next transaction number (nonce) of an address.

``
getNonce(string $minterAddress): int
``

###### Example

```php
$api->getNonce('Mxfe60014a6e9ac91618f5d1cab3fd58cded61ee99')
```

### send

Returns the result of sending <b>signed</b> tx.

``
send(string $tx): \stdClass
``

###### Example

```php
$api->send('f873010101aae98a4d4e540000000000000094fe60014a6e9ac91618f5d1cab3fd58cded61ee99880de0b6b3a764000080801ca0ae0ee912484b9bf3bee785f4cbac118793799450e0de754667e2c18faa510301a04f1e4ed5fad4b489a1065dc1f5255b356ab9a2ce4b24dde35bcb9dc43aba019c')
```

### getStatus

Returns node status info.

``
getStatus(): \stdClass
``

### getValidators

Returns list of active validators.

``
getValidators(?int $height = null): \stdClass
``

### estimateCoinBuy

Return estimate of buy coin transaction.

``
estimateCoinBuy(string $coinToSell, string $valueToBuy, string $coinToBuy, ?int $height = null): \stdClass
``

### estimateCoinSell

Return estimate of sell coin transaction.

``
estimateCoinSell(string $coinToSell, string $valueToSell, string $coinToBuy, ?int $height = null): \stdClass
``

### getCoinInfo

Returns information about coin.
Note: this method does not return information about base coins (MNT and BIP).

``
getCoinInfo(string $coin, ?int $height = null): \stdClass
``

### getBlock

Returns block data at given height.

``
getBlock(int $height): \stdClass
``

### getEvents

Returns events at given height.

``
getEvents(int $height): \stdClass
``

### getTransaction

Returns transaction info.

``
getTransaction(string $hash): \stdClass
``

### getCandidate

Returns candidate’s info by provided public_key. It will respond with 404 code if candidate is not found.

``
getCandidate(string $publicKey, ?int $height = null): \stdClass
``

### getCandidates

Returns list of candidates.

$height is optional parameter.

``
getCandidates(?int $height = null, ?bool $includeStakes = false): \stdClass
``

### estimateTxCommission

Return estimate of transaction.

``
estimateTxCommission(string $tx): \stdClass
``

### getTransactions

Return transactions by query.

``
getTransactions(string $query, ?int $page = null, ?int $perPage = null): \stdClass
``

### getUnconfirmedTxs

Returns unconfirmed transactions.

``
getUnconfirmedTxs(?int $limit = null): \stdClass
``

### getMaxGasPrice

Returns current max gas price.

``
getMaxGasPrice(?int $height = null): \stdClass
``

### getMinGasPrice

Returns current min gas price.

``
getMinGasPrice(): \stdClass
``

### getMissedBlocks

Returns missed blocks by validator public key.

``
getMissedBlocks(string $pubKey, ?int $height = null): \stdClass
``

### Error handling

Example of how you can handle errors and get the response body.

```php
use Minter\MinterAPI;
use GuzzleHttp\Exception\RequestException;

// create instance
$api = new MinterAPI('node url here');

try {
    // success response
    $response = $api->send('signed tx here');
} catch(RequestException $exception) {
    // short exception message
    $message = $exception->getMessage();
    
    // error response in json
    $content = $exception->getResponse()
                    ->getBody()
                    ->getContents();
    
    // error response as array
    $error = json_decode($content, true);                
}
```


## Using MinterSDK

### Sign transaction

Returns a signed tx.

###### Example

* Sign the <b>SendCoin</b> transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterSendCoinTx;

$tx = new MinterTx([
    'nonce' => $nonce,
    'chainId' => MinterTx::MAINNET_CHAIN_ID, // or MinterTx::TESTNET_CHAIN_ID
    'gasPrice' => 1,
    'gasCoin' => 'MNT',
    'type' => MinterSendCoinTx::TYPE,
    'data' => [
        'coin' => 'MNT',
        'to' => 'Mxfe60014a6e9ac91618f5d1cab3fd58cded61ee99',
        'value' => '10'
    ],
    'payload' => '',
    'serviceData' => '',
    'signatureType' => MinterTx::SIGNATURE_SINGLE_TYPE // or SIGNATURE_MULTI_TYPE
]);

$tx->sign('your private key')
```

###### Example
* Sign the <b>SellCoin</b> transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterSellCoinTx;

$tx = new MinterTx([
    'nonce' => $nonce,
    'chainId' => MinterTx::MAINNET_CHAIN_ID, // or MinterTx::TESTNET_CHAIN_ID
    'gasPrice' => 1,
    'gasCoin' => 'MNT',
    'type' => MinterSellCoinTx::TYPE,
    'data' => [
         'coinToSell' => 'MNT',
         'valueToSell' => '1',
         'coinToBuy' => 'TEST',
         'minimumValueToBuy' => 1
    ],
    'payload' => '',
    'serviceData' => '',
    'signatureType' => MinterTx::SIGNATURE_SINGLE_TYPE // or SIGNATURE_MULTI_TYPE
]);

$tx->sign('your private key')
```

###### Example
* Sign the <b>SellAllCoin</b> transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterSellAllCoinTx;

$tx = new MinterTx([
    'nonce' => $nonce,
    'chainId' => MinterTx::MAINNET_CHAIN_ID, // or MinterTx::TESTNET_CHAIN_ID
    'gasPrice' => 1,
    'gasCoin' => 'MNT',
    'type' => MinterSellAllCoinTx::TYPE,
    'data' => [
         'coinToSell' => 'TEST',
         'coinToBuy' => 'MNT',
         'minimumValueToBuy' => 1
    ],
    'payload' => '',
    'serviceData' => '',
    'signatureType' => MinterTx::SIGNATURE_SINGLE_TYPE // or SIGNATURE_MULTI_TYPE
]);

$tx->sign('your private key')
```

###### Example
* Sign the <b>BuyCoin</b> transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterBuyCoinTx;

$tx = new MinterTx([
    'nonce' => $nonce,
    'chainId' => MinterTx::MAINNET_CHAIN_ID, // or MinterTx::TESTNET_CHAIN_ID
    'gasPrice' => 1,
    'gasCoin' => 'MNT',
    'type' => MinterBuyCoinTx::TYPE,
    'data' => [
         'coinToBuy' => 'MNT',
         'valueToBuy' => '1',
         'coinToSell' => 'TEST',
         'maximumValueToSell' => 1
    ],
    'payload' => '',
    'serviceData' => '',
    'signatureType' => MinterTx::SIGNATURE_SINGLE_TYPE // or SIGNATURE_MULTI_TYPE
]);

$tx->sign('your private key')
```

###### Example
* Sign the <b>CreateCoin</b> transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterCreateCoinTx;

$tx = new MinterTx([
    'nonce' => $nonce,
    'chainId' => MinterTx::MAINNET_CHAIN_ID, // or MinterTx::TESTNET_CHAIN_ID
    'gasPrice' => 1,
    'gasCoin' => 'MNT',
    'type' => MinterCreateCoinTx::TYPE,
    'data' => [
        'name' => 'TEST COIN',
        'symbol' => 'TEST',
        'initialAmount' => '100',
        'initialReserve' => '10',
        'crr' => 10
    ],
    'payload' => '',
    'serviceData' => '',
    'signatureType' => MinterTx::SIGNATURE_SINGLE_TYPE // or SIGNATURE_MULTI_TYPE
]);

$tx->sign('your private key')
```

###### Example
* Sign the <b>DeclareCandidacy</b> transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterDeclareCandidacyTx;

$tx = new MinterTx([
    'nonce' => $nonce,
    'chainId' => MinterTx::MAINNET_CHAIN_ID, // or MinterTx::TESTNET_CHAIN_ID
    'gasPrice' => 1,
    'gasCoin' => 'MNT',
    'type' => MinterDeclareCandidacyTx::TYPE,
    'data' => [
        'address' => 'Mxa7bc33954f1ce855ed1a8c768fdd32ed927def47',
        'pubkey' => 'Mp023853f15fc1b1073ad7a1a0d4490a3b1fadfac00f36039b6651bc4c7f52ba9c02',
        'commission' => 10,
        'coin' => 'MNT',
        'stake' => '5'
    ],
    'payload' => '',
    'serviceData' => '',
    'signatureType' => MinterTx::SIGNATURE_SINGLE_TYPE // or SIGNATURE_MULTI_TYPE
]);

$tx->sign('your private key')
```

###### Example
* Sign the <b>Delegate</b> transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterDelegateTx;

$tx = new MinterTx([
    'nonce' => $nonce,
    'chainId' => MinterTx::MAINNET_CHAIN_ID, // or MinterTx::TESTNET_CHAIN_ID
    'gasPrice' => 1,
    'gasCoin' => 'MNT',
    'type' => MinterDelegateTx::TYPE,
    'data' => [
        'pubkey' => 'Mp0eb98ea04ae466d8d38f490db3c99b3996a90e24243952ce9822c6dc1e2c1a43',
        'coin' => 'MNT',
        'stake' => '5'
    ],
    'payload' => '',
    'serviceData' => '',
    'signatureType' => MinterTx::SIGNATURE_SINGLE_TYPE // or SIGNATURE_MULTI_TYPE
]);

$tx->sign('your private key')
```

###### Example
* Sign the <b>SetCandidateOn</b> transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterSetCandidateOnTx;

$tx = new MinterTx([
    'nonce' => $nonce,
    'chainId' => MinterTx::MAINNET_CHAIN_ID, // or MinterTx::TESTNET_CHAIN_ID
    'gasPrice' => 1,
    'gasCoin' => 'MNT',
    'type' => MinterSetCandidateOnTx::TYPE,
    'data' => [
        'pubkey' => 'Mp0eb98ea04ae466d8d38f490db3c99b3996a90e24243952ce9822c6dc1e2c1a43'
    ],
    'payload' => '',
    'serviceData' => '',
    'signatureType' => MinterTx::SIGNATURE_SINGLE_TYPE // or SIGNATURE_MULTI_TYPE
]);

$tx->sign('your private key')
```

###### Example
* Sign the <b>SetCandidateOff</b> transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterSetCandidateOffTx;

$tx = new MinterTx([
    'nonce' => $nonce,
    'chainId' => MinterTx::MAINNET_CHAIN_ID, // or MinterTx::TESTNET_CHAIN_ID
    'gasPrice' => 1,
    'gasCoin' => 'MNT',
    'type' => MinterSetCandidateOffTx::TYPE,
    'data' => [
        'pubkey' => 'Mp0eb98ea04ae466d8d38f490db3c99b3996a90e24243952ce9822c6dc1e2c1a43'
    ],
    'payload' => '',
    'serviceData' => '',
    'signatureType' => MinterTx::SIGNATURE_SINGLE_TYPE // or SIGNATURE_MULTI_TYPE
]);

$tx->sign('your private key')
```

###### Example
* Sign the <b>RedeemCheck</b> transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterRedeemCheckTx;

$tx = new MinterTx([
    'nonce' => $nonce,
    'chainId' => MinterTx::MAINNET_CHAIN_ID, // or MinterTx::TESTNET_CHAIN_ID
    'gasPrice' => 1,
    'gasCoin' => 'MNT',
    'type' => MinterRedeemCheckTx::TYPE,
    'data' => [
        'check' => 'your check',
        'proof' => 'created by MinterCheck proof'
    ],
    'payload' => '',
    'serviceData' => '',
    'signatureType' => MinterTx::SIGNATURE_SINGLE_TYPE // or SIGNATURE_MULTI_TYPE
]);

$tx->sign('your private key')
```

###### Example
* Sign the <b>Unbond</b> transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterUnbondTx;

$tx = new MinterTx([
    'nonce' => $nonce,
    'chainId' => MinterTx::MAINNET_CHAIN_ID, // or MinterTx::TESTNET_CHAIN_ID
    'gasPrice' => 1,
    'gasCoin' => 'MNT',
    'type' => MinterUnbondTx::TYPE,
    'data' => [
        'pubkey' => 'Mp....',
        'coin' => 'MNT',
        'value' => '1'
    ],
    'payload' => '',
    'serviceData' => '',
    'signatureType' => MinterTx::SIGNATURE_SINGLE_TYPE // or SIGNATURE_MULTI_TYPE
]);

$tx->sign('your private key')
```

###### Example
* Sign the <b>MultiSend</b> transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterMultiSendTx;

$tx = new MinterTx([
    'nonce' => $nonce,
    'chainId' => MinterTx::MAINNET_CHAIN_ID, // or MinterTx::TESTNET_CHAIN_ID
    'gasPrice' => 1,
    'gasCoin' => 'MNT',
    'type' => MinterMultiSendTx::TYPE,
    'data' => [
        'list' => [
            [
                'coin' => 'MNT',
                'to' => 'Mxfe60014a6e9ac91618f5d1cab3fd58cded61ee99',
                'value' => '10'
            ], [
                'coin' => 'MNT',
                'to' => 'Mxfe60014a6e9ac91618f5d1cab3fd58cded61ee92',
                'value' => '15'
            ]
        ]
    ],
    'payload' => '',
    'serviceData' => '',
    'signatureType' => MinterTx::SIGNATURE_SINGLE_TYPE // or SIGNATURE_MULTI_TYPE
]);

$tx->sign('your private key')
```

###### Example
* Sign the <b>EditCandidate</b> transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterEditCandidateTx;

$tx = new MinterTx([
    'nonce' => $nonce,
    'chainId' => MinterTx::MAINNET_CHAIN_ID, // or MinterTx::TESTNET_CHAIN_ID
    'gasPrice' => 1,
    'gasCoin' => 'MNT',
    'type' => MinterEditCandidateTx::TYPE,
    'data' => [
        'pubkey' => 'candidate public key',
        'reward_address' => 'Minter address for rewards',
        'owner_address' => 'Minter address of owner'
    ],
    'payload' => '',
    'serviceData' => '',
    'signatureType' => MinterTx::SIGNATURE_SINGLE_TYPE // or SIGNATURE_MULTI_TYPE
]);

$tx->sign('your private key')
```

### Get fee of transaction

* Calculate fee of transaction. You can get fee AFTER signing or decoding transaction.
```php
use Minter\SDK\MinterTx;

$tx = new MinterTx([....]);
$sign = $tx->sign('your private key');

$tx->getFee();
```

### Get hash of transaction

* Get hash of encoded transaction
```php
use Minter\SDK\MinterTx;

$tx = new MinterTx([....]);
$sign = $tx->sign('your private key');

$hash = $tx->getHash();
```

* Get hash of decoded transaction
```php
use Minter\SDK\MinterTx;

$tx = new MinterTx('Mx....');

$hash = $tx->getHash();
```

### Decode transaction

Returns an array with transaction data.

###### Example

* Decode transaction

```php
use Minter\SDK\MinterTx;

$tx = new MinterTx('string tx');

// $tx->from, $tx->data, $tx->nonce ...

```

### Create Minter Check

###### Example

* Create check

```php
use Minter\SDK\MinterCheck;

$check = new MinterCheck([
    'nonce' => $nonce,
    'chainId' => MinterTx::MAINNET_CHAIN_ID, // or MinterTx::TESTNET_CHAIN_ID
    'dueBlock' => 999999,
    'coin' => 'MNT',
    'value' => '10'
], 'your pass phrase');

echo $check->sign('your private key here'); 

// Mc.......

```

* Create proof

```php
use Minter\SDK\MinterCheck;

$check = new MinterCheck('your Minter address here', 'your pass phrase');

echo $check->createProof(); 
```

* Decode check

```php
use Minter\SDK\MinterCheck;

$check = new MinterCheck('your Minter check here');

$check->getBody();  // check body

$check->getOwnerAddress(); // check owner address
```

### Minter Wallet

###### Example

* Create wallet. This method returns generated seed, private key, public key, mnemonic and Minter address.

```php
use Minter\SDK\MinterWallet;

$wallet = MinterWallet::create();
```

* Generate mnemonic.

```php
use Minter\SDK\MinterWallet;

$mnemonic = MinterWallet::generateMnemonic();
```

* Get seed from mnemonic.

```php
use Minter\SDK\MinterWallet;

$seed = MinterWallet::mnemonicToSeed($mnemonic);
```

* Get private key from seed.

```php
use Minter\SDK\MinterWallet;

$privateKey = MinterWallet::seedToPrivateKey($seed);
```

* Get public key from private key.

```php
use Minter\SDK\MinterWallet;

$publicKey = MinterWallet::privateToPublic($privateKey);
```

* Get Minter address from public key.

```php
use Minter\SDK\MinterWallet;

$address = MinterWallet::getAddressFromPublicKey($publicKey);
```

### Minter Link

###### Example

* Create Minter deep link.
* You can pass data of any Minter transaction to the constructor.
* Payload is required.

```php
use Minter\SDK\MinterDeepLink;
use Minter\SDK\MinterCoins\MinterSendCoinTx;

$txData = new MinterSendCoinTx([
    'coin'  => 'BIP',
    'to'    => 'Mx18467bbb64a8edf890201d526c35957d82be3d95',
    'value' => '1.23456789'
]);

$link = new MinterDeepLink($txData);
$link->setPayload('Hello World');

$link->encode(); // returns encoded link as string
```

* You can define optional fields such as nonce, gas price, gas coin.

```php
use Minter\SDK\MinterDeepLink;
use Minter\SDK\MinterCoins\MinterSendCoinTx;

$txData = new MinterSendCoinTx([
    'coin'  => 'BIP',
    'to'    => 'Mx18467bbb64a8edf890201d526c35957d82be3d95',
    'value' => '1.23456789'
]);

$link = new MinterDeepLink($txData);
$link->setPayload('Hello World');
$link->setNonce($nonce);
$link->setGasPrice($gasPrice);
$link->setGasCoin($gasCoin);

$link->encode(); // returns encoded link as string
```


## Tests

To run unit tests: 

```bash
vendor/bin/phpunit tests
```
