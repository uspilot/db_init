# DBInit class
Use this class to do initialization of MySQL database using PDO engine 
 
# Installation

```
$composer require uspilot/db_init
```

# Usage
```php
use \DBInit\DBInit;
$pdo = DBInit::initialize();
```
Check that connection is still alive or not:
```php
if (!DBInit::checkAlive()) $pdo = DBInit::initialize();
```
Get handler to active PDO connection:
```php
$pdo = DBInit::getPdo()?: DBInit::initialize();
``` 


Default ini file is 'ini/db_config.php', default path to <ini> folder is your php program working dir

'host' file contain section name from db_config.php configuration file.

 
