<p align="center" background="black"><img src="minter-logo.svg" width="400"></p>

## About

This is a pure PHP SDK for working with <b>Minter</b> blockchain

* [Installation](#installing)
* [Minter Api](#using-minterapi)
    - Methods:
	    - [getBalance](#getbalance)
	    - [getNonce](#getnonce)
	    - [send](#send)
	    - [getAddresses](#getaddresses)
	    - [getStatus](#getstatus)
	    - [getValidators](#getvalidators)
	    - [estimateCoinBuy](#estimatecoinbuy)
	    - [estimateCoinSell](#estimatecoinsell)
	    - [estimateCoinSellAll](#estimatecoinsellall)
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
		- [CreateMultisig](#example-16)
	- [Sign transaction with multisignatures](#sign-transaction-with-multisignatures)
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

You can get all valid responses and full documentation at [Minter Node Api](https://docs.minter.network/)

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

:warning: <b>To ensure that transaction was successfully committed to the blockchain, you need to find the transaction by the hash and ensure that the status code equals to 0.</b>

``
send(string $tx): \stdClass
``

###### Example

```php
$api->send('f873010101aae98a4d4e540000000000000094fe60014a6e9ac91618f5d1cab3fd58cded61ee99880de0b6b3a764000080801ca0ae0ee912484b9bf3bee785f4cbac118793799450e0de754667e2c18faa510301a04f1e4ed5fad4b489a1065dc1f5255b356ab9a2ce4b24dde35bcb9dc43aba019c')
```

### getAddresses

Returns addresses balances.

``
getAddresses(array $addresses, ?int $height = null): \stdClass
``

### getStatus

Returns node status info.

``
getStatus(): \stdClass
``

### getValidators

Returns list of active validators.

``
getValidators(?int $height = null, ?int $page = 1, ?int $perPage = null): \stdClass
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

### estimateCoinSellAll

Return estimate of sell coin all transaction.

``
estimateCoinSellAll(string $coinToSell, string $valueToSell, string $coinToBuy, ?int $height = null): \stdClass
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
estimateTxCommission(string $tx, ?int $height = null): \stdClass
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

### getGenesis

Return network genesis.

``
getGenesis(): \stdClass
``

### getNetworkInfo

Return node network information.

``
getNetworkInfo(): \stdClass
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
    'type' => MinterSendCoinTx::TYPE,
    'data' => [
        'coin' => '12345',
        'to' => 'Mxfe60014a6e9ac91618f5d1cab3fd58cded61ee99',
        'value' => '10'
    ]
]);

$tx->sign('your private key')
```

At all type of transactions you can also pass: 
<b>gasPrice, gasCoin, payload, serviceData</b>

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterSendCoinTx;

$tx = new MinterTx([
    'nonce' => $nonce,
    'chainId' => MinterTx::MAINNET_CHAIN_ID, // or MinterTx::TESTNET_CHAIN_ID
    'gasPrice' => 1,
    'gasCoin' => '12345',
    'type' => MinterSendCoinTx::TYPE,
    'data' => [
        'coin' => '12345',
        'to' => 'Mxfe60014a6e9ac91618f5d1cab3fd58cded61ee99',
        'value' => '10'
    ],
    'payload' => 'some message',
    'serviceData' => 'some service data'
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
    'type' => MinterSellCoinTx::TYPE,
    'data' => [
         'coinToSell' => '12345',
         'valueToSell' => '1',
         'coinToBuy' => 'TEST',
         'minimumValueToBuy' => 1
    ]
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
    'type' => MinterSellAllCoinTx::TYPE,
    'data' => [
         'coinToSell' => 'TEST',
         'coinToBuy' => '12345',
         'minimumValueToBuy' => 1
    ]
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
    'type' => MinterBuyCoinTx::TYPE,
    'data' => [
         'coinToBuy' => '12345',
         'valueToBuy' => '1',
         'coinToSell' => 'TEST',
         'maximumValueToSell' => 1
    ]
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
    'type' => MinterCreateCoinTx::TYPE,
    'data' => [
        'name' => 'TEST COIN',
        'symbol' => 'TEST',
        'initialAmount' => '100',
        'initialReserve' => '10',
        'crr' => 10,
        'maxSupply' => '10000'
    ]
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
    'type' => MinterDeclareCandidacyTx::TYPE,
    'data' => [
        'address' => 'Mxa7bc33954f1ce855ed1a8c768fdd32ed927def47',
        'pubkey' => 'Mp023853f15fc1b1073ad7a1a0d4490a3b1fadfac00f36039b6651bc4c7f52ba9c02',
        'commission' => 10,
        'coin' => '12345',
        'stake' => '5'
    ]
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
    'type' => MinterDelegateTx::TYPE,
    'data' => [
        'pubkey' => 'Mp0eb98ea04ae466d8d38f490db3c99b3996a90e24243952ce9822c6dc1e2c1a43',
        'coin' => '12345',
        'stake' => '5'
    ]
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
    'type' => MinterSetCandidateOnTx::TYPE,
    'data' => [
        'pubkey' => 'Mp0eb98ea04ae466d8d38f490db3c99b3996a90e24243952ce9822c6dc1e2c1a43'
    ]
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
    'type' => MinterSetCandidateOffTx::TYPE,
    'data' => [
        'pubkey' => 'Mp0eb98ea04ae466d8d38f490db3c99b3996a90e24243952ce9822c6dc1e2c1a43'
    ]
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
    'type' => MinterRedeemCheckTx::TYPE,
    'data' => [
        'check' => 'your check',
        'proof' => 'created by MinterCheck proof'
    ]
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
    'type' => MinterUnbondTx::TYPE,
    'data' => [
        'pubkey' => 'Mp....',
        'coin' => '12345',
        'value' => '1'
    ]
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
    'type' => MinterMultiSendTx::TYPE,
    'data' => [
        'list' => [
            [
                'coin' => '12345',
                'to' => 'Mxfe60014a6e9ac91618f5d1cab3fd58cded61ee99',
                'value' => '10'
            ], [
                'coin' => '12345',
                'to' => 'Mxfe60014a6e9ac91618f5d1cab3fd58cded61ee92',
                'value' => '15'
            ]
        ]
    ]
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
    'type' => MinterEditCandidateTx::TYPE,
    'data' => [
        'pubkey' => 'current candidate public key',
        'new_pubkey' => 'new candidate public key',
        'reward_address' => 'Minter address for rewards',
        'owner_address' => 'Minter address of owner',
        'control_address' => 'Minter address for control'
    ]
]);

$tx->sign('your private key')
```

###### Example
* Sign the <b>CreateMultisig</b> transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterCreateMultisigTx;

$tx = new MinterTx([
    'nonce' => $nonce,
    'chainId' => MinterTx::MAINNET_CHAIN_ID, // or MinterTx::TESTNET_CHAIN_ID
    'type' => MinterCreateMultisigTx::TYPE,
    'data' => [
        'threshold' => 7,
        'weights' => [1, 3, 5],
        'addresses' => [
            'Mxee81347211c72524338f9680072af90744333143',
            'Mxee81347211c72524338f9680072af90744333145',
            'Mxee81347211c72524338f9680072af90744333144'
        ]
    ]
]);

$tx->sign('your private key')
```

###### Example
* Sign the <b>SetHaltBlock</b> transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterSetHaltBlockTx;

$tx = new MinterTx([
    'nonce' => $nonce,
    'chainId' => MinterTx::MAINNET_CHAIN_ID, // or MinterTx::TESTNET_CHAIN_ID
    'type' => MinterSetHaltBlockTx::TYPE,
    'data' => [
        'pubkey' => 'Mp....',
        'height' => '12345'
    ]
]);

$tx->sign('your private key')
```

###### Example
* Sign the <b>RecreateCoin</b> transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterRecreateCoinTx;

$tx = new MinterTx([
    'nonce' => $nonce,
    'chainId' => MinterTx::MAINNET_CHAIN_ID, // or MinterTx::TESTNET_CHAIN_ID
    'type' => MinterRecreateCoinTx::TYPE,
    'data' => [
        'name' => 'TEST COIN',
        'symbol' => 'TEST',
        'initialAmount' => '100',
        'initialReserve' => '10',
        'crr' => 10,
        'maxSupply' => '10000'
    ]
]);

$tx->sign('your private key')
```

###### Example
* Sign the <b>ChangeOwner</b> transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterChangeOwnerTx;

$tx = new MinterTx([
    'nonce' => $nonce,
    'chainId' => MinterTx::MAINNET_CHAIN_ID, // or MinterTx::TESTNET_CHAIN_ID
    'type' => MinterChangeOwnerTx::TYPE,
    'data' => [
        'symbol'   => 'TEST',
        'newOwner' => 'Mxee81347211c72524338f9680072af90744333143'
    ]
]);

$tx->sign('your private key')
```

### Sign transaction with multisignatures

Returns a signed tx.

###### Example

* To sign transaction with multisignatures, you need to call <b>signMultisig</b> method 
and pass <b>multisig Minter address</b> and his <b>private keys</b> (in any order).

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterSendCoinTx;

$tx = new MinterTx([
    'nonce' => $nonce,
    'chainId' => MinterTx::MAINNET_CHAIN_ID, // or MinterTx::TESTNET_CHAIN_ID
    'type' => MinterSendCoinTx::TYPE,
    'data' => [
        'coin' => '12345',
        'to' => 'Mxfe60014a6e9ac91618f5d1cab3fd58cded61ee99',
        'value' => '10'
    ]
]);

$signedTx = $tx->signMultisig('Mxdb4f4b6942cb927e8d7e3a1f602d0f1fb43b5bd2', [
    'b354c3d1d456d5a1ddd65ca05fd710117701ec69d82dac1858986049a0385af9',
    '38b7dfb77426247aed6081f769ed8f62aaec2ee2b38336110ac4f7484478dccb',
    '94c0915734f92dd66acfdc48f82b1d0b208efd544fe763386160ec30c968b4af'
])
```

###### Example

* To get the <b>signature</b> of transaction (not signed transaction)
you need to call <b>createSignature</b>

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterSendCoinTx;

$tx = new MinterTx([
    'nonce' => $nonce,
    'chainId' => MinterTx::MAINNET_CHAIN_ID, // or MinterTx::TESTNET_CHAIN_ID
    'type' => MinterSendCoinTx::TYPE,
    'data' => [
        'coin' => '12345',
        'to' => 'Mxfe60014a6e9ac91618f5d1cab3fd58cded61ee99',
        'value' => '10'
    ]
]);

$txSignature = $tx->createSignature($privateKey);
```

###### Example

* To sign transaction with ready signatures, you need to call <b>signMultisigBySigns</b> method 
and pass <b>multisig Minter address</b> and your <b>signatures</b> (in any order).

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterSendCoinTx;

$tx = new MinterTx([
    'nonce' => $nonce,
    'chainId' => MinterTx::MAINNET_CHAIN_ID, // or MinterTx::TESTNET_CHAIN_ID
    'type' => MinterSendCoinTx::TYPE,
    'data' => [
        'coin' => '12345',
        'to' => 'Mxfe60014a6e9ac91618f5d1cab3fd58cded61ee99',
        'value' => '10'
    ]
]);

$signature1 = $tx->createSignature($privateKey1);
$signature2 = $tx->createSignature($privateKey2);
$signature3 = $tx->createSignature($privateKey3);

$signedTx = $tx->signMultisigBySigns('Mxdb4f4b6942cb927e8d7e3a1f602d0f1fb43b5bd2', [
     $signature1, $signature2, $signature3
])
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
    'coin' => '12345',
    'value' => '10',
    'gasCoin' => '12345'
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

* Get private key from mnemonic.

```php
use Minter\SDK\MinterWallet;

$privateKey = MinterWallet::mnemonicToPrivateKey($seed);
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

```php
use Minter\SDK\MinterDeepLink;
use Minter\SDK\MinterCoins\MinterSendCoinTx;

$txData = new MinterSendCoinTx([
    'coin'  => '12345',
    'to'    => 'Mx18467bbb64a8edf890201d526c35957d82be3d95',
    'value' => '1.23456789'
]);

$link = new MinterDeepLink($txData);
$link->encode(); // returns encoded link as string
```

* You can define optional fields such as host, payload, nonce, gas price, gas coin, check password.

```php
use Minter\SDK\MinterDeepLink;
use Minter\SDK\MinterCoins\MinterSendCoinTx;

$txData = new MinterSendCoinTx([
    'coin'  => '12345',
    'to'    => 'Mx18467bbb64a8edf890201d526c35957d82be3d95',
    'value' => '1.23456789'
]);

$link = new MinterDeepLink($txData);
$link->setPayload('Hello World')
    ->setNonce($nonce)
    ->setGasPrice($gasPrice)
    ->setGasCoin($gasCoin)
    ->setHost('https://testnet.bip.to/tx')
    ->setPassword('some check password');

$link->encode(); // returns encoded link as string
```


## Tests

To run unit tests: 

```bash
vendor/bin/phpunit tests
```
