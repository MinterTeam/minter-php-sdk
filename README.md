# Minter PHP SDK

## About

This is a pure PHP SDK for working with <b>Minter</b> blockhain

## Installing

```bash
composer require minter/minter-php-sdk
```

## Usage MinterAPI

Create instance

```php
use Minter\MinterAPI;

$nodeUrl = 'http://156.123.34.5:8841'; // example node url

$api = new MinterAPI($nodeUrl);
```

### getBalance

Returns the coins and their balance of the given address.

``
getBalance(string $minterAddress): \stdClass
``

###### Example

```php
$api->getBalance('Mxfe60014a6e9ac91618f5d1cab3fd58cded61ee99')

// {MTN: 1000000, TESTCOIN: 2000000}

```

### getNonce

Returns the nonce of the given address.

``
getNonce(string $minterAddress): integer
``

###### Example

```php
$api->getNonce('Mxfe60014a6e9ac91618f5d1cab3fd58cded61ee99')

// 5
```

### send

Returns the result of sending <b>signed</b> tx.

``
send(string $tx): \stdClass
``

###### Example

```php
$api->send('Mxf86d010101a6e58a4d494e540000000000009432143b4d9674b13b0868da425d049fd66910ebae843b9aca001ba0f0c64ae99c1f3f1acb9ad44cc1beeb3e29339353841b2a25dfa14529c41f6bbea02055b472434f7119ea5d7e928a2c357d9f5189b396cd1908bb1f9940391a79e4')

// {code: 0, tx: "Mx2f37ad1c22cf912c02a9f00c735a039d7da3169b"}
```

### getTransactionsFrom

Returns the outcoming transactions of the given address

``
getTransactionsFrom(string $minterAddress): \stdClass
``

###### Example

```php
$api->getTransactionsFrom('Mxfe60014a6e9ac91618f5d1cab3fd58cded61ee99')

// {code: 0, result: [{ height: 1, index: 0, proof: ..., tx: 'Mx...', tx_result: {...} }]}
```

### getTransactionsTo

Returns the incoming transactions of the given address

``
getTransactionsTo(string $minterAddress): \stdClass
``

###### Example

```php
$api->getTransactionsTo('Mxfe60014a6e9ac91618f5d1cab3fd58cded61ee99')

// {code: 0, result: [{ height: 1, index: 0, proof: ..., tx: 'Mx...', tx_result: {...} }]}
```

## Usage MinterSDK

### Sign transaction

Returns the signed tx

###### Example

* Sign the <b>sending</b> coin transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterSendCoinTx;

$txData = new MinterSendCoinTx([
    'coin' => 'MTN',
    'to' => 'Mxfe60014a6e9ac91618f5d1cab3fd58cded61ee99',
    'value' => 10 
]);

$tx = new MinterTx([
    'nonce' => $nonce,
    'gasPrice' => 1,
    'type' => 1,
    'data' => $txData->serialize()
]);

$tx->sign('your private key')
```

* Sign the <b>converting</b> coin transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterConvertCoinTx;

$txData = new MinterConvertCoinTx([
    'coin_from' => 'MNT',
    'coin_to' => 'TESTCOIN',
    'value' => 1
]);

$tx = new MinterTx([
    'nonce' => $nonce,
    'gasPrice' => 1,
    'type' => 2,
    'data' => $txData->serialize()
]);

$tx->sign('your private key')
```

* Sign the <b>creating</b> coin transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterCreateCoinTx;

$txData = new MinterCreateCoinTx([
    'name' => 'TEST COIN',
    'symbol' => 'TEST',
    'initialAmount' => 100,
    'initialReserve' => 10,
    'crr' => 10
]);

$tx = new MinterTx([
    'nonce' => $nonce,
    'gasPrice' => 1,
    'type' => 3,
    'data' => $txData->serialize()
]);

$tx->sign('your private key')
```

### Decode transaction

Returns the array of transaction data

###### Example

* Decode transaction

```php
use Minter\SDK\MinterTx;
use Minter\SDK\MinterSendCoinTx;
use Minter\SDK\MinterCreateCoinTx;
use Minter\SDK\MinterConvertCoinTx;

$tx = new MinterTx('string tx');

// {height: .., data: [{...}], from: 'Mx...', type: ...}

// now we need to parse data of transaction
if($tx->type === 1) {
    $data = new MinterSendCoinTx($tx->data, true);
    
    // {to: 'Mx...', coin: '...', 'value': ...}
}

if($tx->type === 2) {
    $data = new MinterConvertCoinTx($tx->data, true);
    
    // {coin_from: '...', coin_to: '...', 'value': ...}
}

if($tx->type === 3) {
    $data = new MinterCreateCoinTx($tx->data, true);
    
    // {name: '...', symbol: '...', 'initialAmount': ... , 'initialReserve': ... , 'crr': ...}
}   

```


## License

The Minter PHP SDK is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
