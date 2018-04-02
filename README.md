API Microservice Core for Laravel
=======
API Core for microservice,

Installation
------------

Use composer to manage your dependencies and download Core API:

```bash
composer require lattesoft/api-core
```

Example
-------
```php
<?php
use \Finiz\Response\IResponse;
/*
 * For get translated response message
 */

IResponse::responseService(2001000)

?>
```

Changelog
---------

#### 1.0.0 / 2018-04-02
- Create Project.

New Lines in private keys
-----

If your private key contains `\n` characters, be sure to wrap it in double quotes `""`
and not single quotes `''` in order to properly interpret the escaped characters.

License
-------
[LATTESOFT (Thailand)](https://lattesoft.in.th)
