# Enchainté SDK -  PHP

This SDK offers all the features available in the Enchainté Toolset:
- Write messages
- Get messages proof
- Validate proof
- Get messages details


## Installation

The SDK can be installed with the composer package manager:

```shell
$ composer require enchainte/sdk
```


## Usage

The following examples summarize how to access the different functionalities available:

### Prepare data

In order to interact with the SDK, the data should be converted to bytes. The SDK provides the basic methods to do so.


```php
<?php

use EnchainteClient;

try {
    // you need an api key to get access to the sdk functionality
    $sdkClient = new EnchainteClient("apiKey");
    // convert data to bytes
    
    // from string to bytes
    $stringData = "Example data";
    $bytesData = $sdkClient->string2Bytes($stringData);    

    // from hexadecimal to bytes
    $hexData = "4578616d706c652064617461";
    $bytesData = $sdkClient->hex2Bytes($hexData);    

    // from bytes to string and bytes to hexadecimal
    $bytesData = [69, 120, 97, 109, 112, 108, 101, 32, 100, 97, 116, 97];
    $stringData = $sdkClient->bytes2String($bytesData);    
    $hexData = $sdkClient->bytes2Hex($bytesData);    

} catch (Exception $e) {
    // handle exception
}
```

### Send messages

This example shows how to send data to Enchainté

```php
<?php

use EnchainteClient;
use Exception;

try {
    $sdkClient = new EnchainteClient("apiKey");
    
    $bytesData = $sdkClient->string2Bytes("Example data");
    $sdkClient->sendMessage($bytesData);

} catch (Exception $e) {
    // handle exception
}
```

### Get messages status

This example shows how to get all the details and status of messages:

```php
<?php

use EnchainteClient;
use Exception;

try {
    $sdkClient = new EnchainteClient("apiKey");
    
    $messages = [
        $sdkClient->string2Bytes("Example data 1"),
        $sdkClient->string2Bytes("Example data 2"),
        $sdkClient->string2Bytes("Example data 3"),
    ];
    $messageReceipts = $sdkClient->getMessages($messages);

} catch (Exception $e) {
    // handle exception
}
```

### Wait for message receipts

This example shows how to wait for one or more messages to be processed by Enchainté after sending it.

```php
<?php

use EnchainteClient;
use Exception;

try {
    $sdkClient = new EnchainteClient("apiKey");
    
    $message1 = $sdkClient->string2Bytes("Example data 1");
    $message2 = $sdkClient->string2Bytes("Example data 2");

    $sdkClient->sendMessage($message1);
    $sdkClient->sendMessage($message2);

    $messageReceipts = $sdkClient->waitMessageReceipt([$message1, $message2]);

} catch (Exception $e) {
    // handle exception
}
```


### Get and validate messages proof

This example shows how to get a proof for an array of messages and validate it:

```php
<?php

use EnchainteClient;
use Exception;

try {
    $sdkClient = new EnchainteClient("apiKey");
    
    $messages = [
        $sdkClient->string2Bytes("Example data 1"),
        $sdkClient->string2Bytes("Example data 2"),
        $sdkClient->string2Bytes("Example data 3"),
    ];
    $proof = $sdkClient->getProof($messages);

    $valid = $sdkClient->verifyProof($proof["leaves"], $proof["nodes"], $proof["depth"], $proof["bitmap"]);

} catch (Exception $e) {
    // handle exception
}
```

### Full example

This snippet shows a complete data cycle including: write, message status polling and proof retrieval and validation.

```php
<?php

use EnchainteClient;
use Exception;

// Helper function to get a random data string
function generateRandomString() {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    $length = rand(10, 40);
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

try {
    $apiKey = getenv("API_KEY");
    $sdkClient = new EnchainteClient("apiKey");

    $randomString = $this->generateRandomString();

    $dataInBytes = $sdkClient->string2Bytes($randomString);
    // writing message
    $ok = $sdkClient->sendMessage($dataInBytes);

    if (!$ok) {
        return;
    }
    $sdkClient->waitMessageReceipt([$dataInBytes]);
    
    // retrieving message proof
    $proof = $sdkClient->getProof([$dataInBytes]);

    $valid = false;
    // this while loop is necessary at the moment but it will not be in future versions of the SDK
    while (!$valid) {
        // validating message proof
        $valid = $sdkClient->verifyProof($proof["leaves"], $proof["nodes"], $proof["depth"], $proof["bitmap"]);
        usleep(500);
    }
    
} catch (Exception $e) {
    echo $e->getMessage();
}
```