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
		- [SetHaltBlock](#example-17)
		- [RecreateCoin](#example-18)
		- [EditCoinOwner](#example-19)
		- [EditMultisig](#example-20)
	- [Sign transaction with multisignatures](#sign-transaction-with-multisignatures)
	- [Get fee of transaction](#get-fee-of-transaction)
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

$data = new MinterSendCoinTx('MNT', 'Mxfe60014a6e9ac91618f5d1cab3fd58cded61ee99', '10');
$tx = new MinterTx($nonce, $data);

$tx->sign('your private key')
```

<b>At all type of transactions you can also set optional fields: 
gas price, gas coin, payload, serviceData, chain id</b>

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterSendCoinTx;

$data = new MinterSendCoinTx('MNT', 'Mxfe60014a6e9ac91618f5d1cab3fd58cded61ee99', '10');
$tx   = (new MinterTx($nonce, $data))
   ->setChainID(MinterTx::TESTNET_CHAIN_ID)
   ->setGasPrice(1)
   ->setGasCoin(MinterTx::BASE_COIN_ID)
   ->setPayload('some payload')
   ->setServiceData('some data')

$tx->sign('your private key')
```

###### Example
* Sign the <b>SellCoin</b> transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterSellCoinTx;

$data = new MinterSellCoinTx(123, '1', 321, '1');
$tx   = new MinterTx($nonce, $data);

$tx->sign('your private key')
```

###### Example
* Sign the <b>SellAllCoin</b> transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterSellAllCoinTx;

$data = new MinterSellAllCoinTx(123, 321, '1');
$tx   = new MinterTx($nonce, $data);

$tx->sign('your private key')
```

###### Example
* Sign the <b>BuyCoin</b> transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterBuyCoinTx;

$data = new MinterBuyCoinTx(123, '1', 321, '1');
$tx   = new MinterTx($nonce, $data);

$tx->sign('your private key')
```

###### Example
* Sign the <b>CreateCoin</b> transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterCreateCoinTx;

$data = new MinterCreateCoinTx('TEST COIN', 'TEST', '10000', '10', 10, '10000');
$tx   = new MinterTx($nonce, $data);

$tx->sign('your private key')
```

###### Example
* Sign the <b>DeclareCandidacy</b> transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterDeclareCandidacyTx;

$data = new MinterDeclareCandidacyTx(
    'Mxa7bc33954f1ce855ed1a8c768fdd32ed927def47', 
    'Mp023853f15fc1b1073ad7a1a0d4490a3b1fadfac00f36039b6651bc4c7f52ba9c02', 
    10, 0, '10000'
);

$tx = new MinterTx($nonce, $data);
$tx->sign('your private key')
```

###### Example
* Sign the <b>Delegate</b> transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterDelegateTx;

$data = new MinterDelegateTx('Mp0eb98ea04ae466d8d38f490db3c99b3996a90e24243952ce9822c6dc1e2c1a43', 123, '10000');
$tx   = new MinterTx($nonce, $data);

$tx->sign('your private key')
```

###### Example
* Sign the <b>SetCandidateOn</b> transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterSetCandidateOnTx;

$data = new MinterSetCandidateOnTx('Mp0eb98ea04ae466d8d38f490db3c99b3996a90e24243952ce9822c6dc1e2c1a43');
$tx   = new MinterTx($nonce, $data);

$tx->sign('your private key')
```

###### Example
* Sign the <b>SetCandidateOff</b> transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterSetCandidateOffTx;

$data = new MinterSetCandidateOffTx('Mp0eb98ea04ae466d8d38f490db3c99b3996a90e24243952ce9822c6dc1e2c1a43');
$tx   = new MinterTx($nonce, $data);

$tx->sign('your private key')
```

###### Example
* Sign the <b>RedeemCheck</b> transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterRedeemCheckTx;

$data = new MinterRedeemCheckTx('your check', 'created by MinterCheck proof');
$tx   = new MinterTx($nonce, $data);

$tx->sign('your private key')
```

###### Example
* Sign the <b>Unbond</b> transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterUnbondTx;

$data = new MinterUnbondTx('Mp....', 123, '10000');
$tx   = new MinterTx($nonce, $data);

$tx->sign('your private key')
```

###### Example
* Sign the <b>MultiSend</b> transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterSendCoinTx;
use Minter\SDK\MinterCoins\MinterMultiSendTx;

$data = new MinterMultiSendTx([
    new MinterSendCoinTx(0, 'Mxfe60014a6e9ac91618f5d1cab3fd58cded61ee99', '15'),
    new MinterSendCoinTx(123, 'Mxfe60014a6e9ac91618f5d1cab3fd58cded61ee92', '10')
]);

$tx = new MinterTx($nonce, $data);
$tx->sign('your private key')
```

###### Example
* Sign the <b>EditCandidate</b> transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterEditCandidateTx;

$data = new MinterEditCandidateTx('candidate public key', 'Minter address for rewards', 'Minter address of owner');
$tx   = new MinterTx($nonce, $data);

$tx->sign('your private key')
```

###### Example
* Sign the <b>CreateMultisig</b> transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterCreateMultisigTx;

$data = new MinterCreateMultisigTx(7, [1, 3, 5], [
    'Mxee81347211c72524338f9680072af90744333143',
    'Mxee81347211c72524338f9680072af90744333145',
    'Mxee81347211c72524338f9680072af90744333144'
]);

$tx = new MinterTx($nonce, $data);
$tx->sign('your private key')
```

###### Example
* Sign the <b>SetHaltBlock</b> transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterSetHaltBlockTx;

$data = new MinterSetHaltBlockTx('your public key', 236503);
$tx   = new MinterTx($nonce, $data);
$tx->sign('your private key')
```

###### Example
* Sign the <b>RecreateCoin</b> transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterRecreateCoinTx;

$data = new MinterRecreateCoinTx('TEST', '10000', '10', 10, '10000');
$tx   = new MinterTx($nonce, $data);
$tx->sign('your private key')
```

###### Example
* Sign the <b>EditCoinOwner</b> transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterEditCoinOwnerTx;

$data = new MinterEditCoinOwnerTx('COINSYMBOL', 'Mxee81347211c72524338f9680072af90744333145');
$tx   = new MinterTx($nonce, $data);
$tx->sign('your private key')
```

###### Example
* Sign the <b>EditMultisig</b> transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterEditMultisigTx;

$data = new MinterEditMultisigTx(1, [1, 2], ['Mxee81347211c72524338f9680072af90744333145', 'Mxee81347211c72524338f9680072af90744333146']);
$tx   = new MinterTx($nonce, $data);
$tx->sign('your private key')
```

###### Example
* Sign the <b>PriceVote</b> transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterPriceVoteTx;

$data = new MinterPriceVoteTx(1000);
$tx   = new MinterTx($nonce, $data);
$tx->sign('your private key')
```

###### Example
* Sign the <b>EditCandidatePublicKey</b> transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterEditCandidatePublicKey;

$data = new MinterEditCandidatePublicKey('new public key....');
$tx   = new MinterTx($nonce, $data);
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

$data = new MinterSendCoinTx(123, 'Mxfe60014a6e9ac91618f5d1cab3fd58cded61ee99', '10');
$tx = new MinterTx($nonce, $data);

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

$data = new MinterSendCoinTx(123, 'Mxfe60014a6e9ac91618f5d1cab3fd58cded61ee99', '10');
$tx = new MinterTx($nonce, $data);

$txSignature = $tx->createSignature($privateKey);
```

###### Example

* To sign transaction with ready signatures, you need to call <b>signMultisigBySigns</b> method 
and pass <b>multisig Minter address</b> and your <b>signatures</b> (in any order).

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCoins\MinterSendCoinTx;

$data = new MinterSendCoinTx(123, 'Mxfe60014a6e9ac91618f5d1cab3fd58cded61ee99', '10');
$tx = new MinterTx($nonce, $data);

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

$tx = new MinterTx(...);
$tx->getFee();
```

### Decode transaction

Returns an array with transaction data.

###### Example

* Decode transaction

```php
use Minter\SDK\MinterTx;

$tx = MinterTx::decode('transaction raw starting from 0x...');

// $tx->getSenderAddress()
// $tx->getData()
// $tx->getNonce()
// $tx->getChainID()
// $tx->getGasPrice()
// $tx->getPayload()
// $tx->getSignatureData()

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
    'value' => '10',
    'gasCoin' => 'MNT'
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

* Create wallet. 

```php
use Minter\SDK\MinterWallet;

$wallet = new MinterWallet();

// $wallet->getPublicKey();
// $wallet->getPrivateKey();
// $wallet->getMnemonic();
// $wallet->getAddress();
```

* Create wallet from mnemonic

```php
use Minter\SDK\MinterWallet;

$wallet = MinterWallet::createFromMnemonic($mnemonic);
```

* Create wallet from private key

```php
use Minter\SDK\MinterWallet;

$wallet = MinterWallet::createFromPrivate($privateKey);
```

### Minter Link

###### Example

* Create Minter deep link.
* You can pass data of any Minter transaction to the constructor.

```php
use Minter\SDK\MinterDeepLink;
use Minter\SDK\MinterCoins\MinterSendCoinTx;

$txData = new MinterSendCoinTx(123, 'Mx18467bbb64a8edf890201d526c35957d82be3d95', '1.23456789');
$link   = new MinterDeepLink($txData);
$link->encode(); // returns encoded link as string
```

* You can define optional fields such as host, payload, nonce, gas price, gas coin, check password.

```php
use Minter\SDK\MinterDeepLink;
use Minter\SDK\MinterCoins\MinterSendCoinTx;

$txData = new MinterSendCoinTx(123, 'Mx18467bbb64a8edf890201d526c35957d82be3d95', '1.23456789');
$link   = new MinterDeepLink($txData);

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
