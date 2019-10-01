<?php
// Example/unit test

// helper
function show($rs) {
    foreach ($rs as $rec) echo sprintf('%s %s'.PHP_EOL, $rec['v'], $rec['rn']);
    echo PHP_EOL;
}

require ('SQLMethods.class.php'); // Use your preferred class loading mechanism
$conn = new PDO (
    'pgsql:host=<host name or IP address>;port=<port>;dbname=<database name>',
    '<dbUser>', '<dbPassword>',
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => FALSE]
);
$osql = new SQLMethods('example.sql');
$osql -> connection($conn);
// -----------------------------------------------------------------------------
// no parameters
$result = $osql -> Ruffus();
show($result);

// named parameters
$result = $osql -> Buster([':hi' => 17, ':lo' => 15]);
show($result);

// positional parameters
$result = $osql -> Gracie(18, 20);
show($result);
