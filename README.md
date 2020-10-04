# DBInit class
Use this class to do initialization of MySQL database using PDO engine 
 
# Installation
composer require uspilot/db_init

# How to use
use \DBInit\DBInit;
$pdo = DBInit::initialize();

DBInit::checkAlive() checks that connection is still alive or not
DBInit::getPdo() return handler to active PDO connection 

Default ini file is 'ini/db_config.php', default path to <ini> folder is your php program working dir

'host' file contain section name from db_config.php configuration file.

 
