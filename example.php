<?php
// SQLMethods example/unit test

// Use your preferred class loading mechanism
require ('SQLMethods.class.php');

// Obtain a PDO connection in your preferred way
$conn = new PDO (
    'pgsql:host=172.30.0.10;port=5432;dbname=playground',
    'phpUser', 'Baba123Meca',
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => FALSE]
);

/* There are different ways to build SQLMethod instances
// Use a single SQL file
$dbgw = new SQLMethods('example.sql', $conn);

// Use many SQL files
$dbgw = new SQLMethods;
$dbgw -> connection($conn);
$dbgw -> import('none.sql');
$dbgw -> import('named.sql');
$dbgw -> import('positional.sql');
*/
// Use many SQL files w/ method chaining
$dbgw = SQLMethods::createInstance($conn)
 -> import('none.sql')
 -> import('named.sql')
 -> import('positional.sql');

// call a method with no parameters
$result = $dbgw -> Ruffus();
echo SQLMethods::dump_rs($result);
echo PHP_EOL.PHP_EOL;

// call a method with named parameters
$result = $dbgw -> Buster([':hi' => 17, ':lo' => 15]);
echo SQLMethods::dump_rs($result);
echo PHP_EOL.PHP_EOL;

// call a method with positional parameters
$result = $dbgw -> Gracie(18, 20);
echo SQLMethods::dump_rs($result);
echo PHP_EOL.PHP_EOL;
