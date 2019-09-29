<?php
// Example/unit test
require ('SQLMethods.class.php'); // Use your preferred class loading mechanism
$conn = new PDO('pgsql:host=<host name or IP address>;port=<port>;dbname=<database name>', '<dbUser>', '<dbPassword>', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$osql = new SQLMethods('example.sql', $conn);
// -----------------------------------------------------------------------------
// helper
function show($rs)
{
	foreach ($rs as $rec) echo sprintf('%s %s'.PHP_EOL, $rec['v'], $rec['rn']);
	echo PHP_EOL;
}
// -----------------------------------------------------------------------------
// no parameters
$result = $osql -> Ruffus();
show($result);

// named parameters
$result = $osql -> Buster([':hi' => 20, ':lo' => 18]);
show($result);

// positional parameters
$result = $osql -> Gracie(18, 20);
show($result);
