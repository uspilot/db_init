<?php
/**
 * Copyright (c) 2020
 *  @file  DB.php
 *  @author  Sergey
 *  @date  2020/10/03
 *  @time  16:04
 *
 */

//use PDO;
//use PDOException;
use uspilot\IniParser;

class DBInit
{
    /**
     * MySQL credentials
     *
     * @var array
     */
    protected static $mysql_credentials = [];

    /**
     * PDO object
     *
     * @var PDO
     */
    protected static $pdo = null;

    /**
     * Table prefix
     *
     * @var string
     */

    /**
     * Initialize
     *
     * @param string $ini_file INI filename for DB initialization
     * @param string $encoding Database character encoding
     * @param string $host Host section for INI file
     *
     * @return PDO PDO database object
     * @throws Exception
     */
    public static function initialize(
        $ini_file = 'ini/db_config.php',
        $encoding = 'utf8',
        $host = 'localhost'
    ) {
        $iniparser = new uspilot\IniParser($ini_file);
        $ini = $iniparser->parse();
        /* Check if 'host' file exists in current directory.
         *  if not - use 'host' file from INI directory
        */
        if (file_exists('host')) $hostFile = 'host';
        else {
            $dir = pathinfo($ini_file, PATHINFO_DIRNAME);
            $hostFile = $dir.'/host';
        }
        $f = fopen($hostFile,'r');
        if ($f){
            $host = fgets($f);
        }
        if (empty($ini[$host])){
            throw new Exception('Host section not found in DataBase ini file!');
        }
        if (empty($ini[$host]['host']) ||
            empty($ini[$host]['port']) ||
            empty($ini[$host]['user']) ||
            empty($ini[$host]['password']) ||
            empty($ini[$host]['database'])
        ){
            throw new Exception('Host section not found in DataBase ini file!');
        }
        $credentials = $ini[$host];
        $dsn = 'mysql:host=' . $credentials['host'] . ';dbname=' . $credentials['database'];
        if (!empty($credentials['port'])) {
            $dsn .= ';port=' . $credentials['port'];
        }

        $options = [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . $encoding,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_PERSISTENT => true
        ];
        try {
            $pdo = new PDO($dsn, $credentials['user'], $credentials['password'], $options);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, /*PDO::ERRMODE_WARNING*/PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(),$e->getCode());
        }

        self::$pdo               = $pdo;
        self::$mysql_credentials = $credentials;

        return self::$pdo;
    }

    /**
     * External Initialize
     *
     * Let you use the class with an external already existing Pdo Mysql connection.
     *
     * @param PDO      $external_pdo_connection PDO database object
     *
     * @return PDO PDO database object
     * @throws Exception
     */
    public static function externalInitialize(
        $external_pdo_connection
    ) {
        if ($external_pdo_connection === null) {
            throw new Exception('MySQL external connection not provided!');
        }

        self::$pdo               = $external_pdo_connection;
        self::$mysql_credentials = [];

        return self::$pdo;
    }

    /**
     * Check if database connection has been created
     *
     * @return bool
     */
    public static function isDbConnected()
    {
        return self::$pdo !== null;
    }

    /**
     * Get the PDO object of the connected database
     *
     * @return PDO
     */
    public static function getPdo()
    {
        return self::$pdo;
    }

    /**
     * Get MySQL connection credentials
     *
     * @return array
     */
    public static function getMysqlCredentials()
    {
        return self::$mysql_credentials;
    }

    /**
     * Check MySQL connection
     * @return bool
     */
    public static function checkAlive()
    {
        if (is_null(self::$pdo)) {
            return false;
        }

        try {
            $testRes = self::$pdo->query('SELECT 1+2+3 as result');
            $test = $testRes->fetch();
            if ($test['result'] == 6) {
                return true;
            }
        }
        catch (PDOException $e) {
            return false;
        }
        return false;
    }
}