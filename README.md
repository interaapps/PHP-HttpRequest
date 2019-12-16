# Simple HTTP Client for PHP (UPPM Project)

Features:
- Very Simple
- Pattern Builder (Returns always this except for send and getters)

```php
use modules\httprequest\HttpRequest;

// Simple get
$res = HttpRequest::get("https://interaapps.de")->send();

$res->getData(); // Gets the body
$res->getCode(); // Gets the httpcode
// ...

// POST-Request
$res = HttpRequest::post("https://interaapps.de")
        ->parameter("username", "homer.simpson")
        ->parameter("username", "ilovedonuts")
        ->send();

```