<?php
// Example/unit test
require ('SQLMethods.class.php'); // Use your preferred class loading mechanism
$conn = new PDO (
    'pgsql:host=<host name or IP address>;port=<port>;dbname=<database name>',
    '<dbUser>', '<dbPassword>',
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => FALSE]
);
// -----------------------------------------------------------------------------
$osql = new SQLMethods('example.sql', $conn);

// no parameters
$result = $osql -> Ruffus();
echo $osql -> dump_rs($result).PHP_EOL;

// named parameters
$result = $osql -> Buster([':hi' => 17, ':lo' => 15]);
echo $osql -> dump_rs($result).PHP_EOL;

// positional parameters
$result = $osql -> Gracie(18, 20);
echo $osql -> dump_rs($result).PHP_EOL;
