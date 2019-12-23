<?php
// SQLMethods example/unit test
// Use your preferred class loading mechanism
require ('SQLMethods.class.php');

// Obtain a PDO connection in your preferred way
$conn = new PDO (
    'pgsql:host=<host>;port=<port>;dbname=<database name>',
    '<user>', '<password>',
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => FALSE]
);

/* There are different ways to build SQLMethod instances and inport SQL files 
// Use a single SQL file
$dbgw = new SQLMethods('sql/example.sql', $conn);

// Use many SQL files
$dbgw = new SQLMethods;
$dbgw -> connection($conn);
$dbgw -> import('sql/none.sql');
$dbgw -> import('sql/named.sql');
$dbgw -> import('sql/positional.sql');
$dbgw -> import('sql/value.sql');
*/

// Use many SQL files w/ method chaining
$dbgw = SQLMethods::createInstance($conn)
 -> import('sql/none.sql')
 -> import('sql/named.sql')
 -> import('sql/positional.sql')
 -> import('sql/value.sql');
 
// call a method with no arguments
$result = $dbgw -> Ruffus();
echo SQLMethods::dump_rs($result);
echo PHP_EOL.PHP_EOL;

// call a method with named arguments
$result = $dbgw -> Buster([':hi' => 17, ':lo' => 15]);
echo SQLMethods::dump_rs($result);
echo PHP_EOL.PHP_EOL;

// call a method with positional arguments
$result = $dbgw -> Gracie(18, 20);
echo SQLMethods::dump_rs($result);
echo PHP_EOL.PHP_EOL;

// call a method with positional arguments that returns a single value
$result = $dbgw -> ISOTime('P2D');
echo $result;
echo PHP_EOL.PHP_EOL;
