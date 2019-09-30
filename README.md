# SQL Methods
Lightweight PHP class with no dependencies to dynamically expose methods defined as queries in a SQL file.<br/>
Clean separation of concerns inspired by [this](https://www.youtube.com/watch?v=q9IXCdy_mtY) talk and __YeSQL__.
Al that matters is there in the PHP CLI example.

Queries have names that become methods' names.  
### File _example.sql_

``` SQL
-- SQLMethods example SQL file

-- Query definition header format:
-- Single line starts with --! followed by metadata in JSON
-- Metadata JSON format: {"name":<query name>, "param_mode":<parameters mode>}
-- <query name> is a valid indentifier string that becomes a method name;
-- <parameters mode> is one of "NONE", "NAMED" or "POSITIONAL"
-- See the example below

-- No parameters, param_mode is NONE
--! {"name":"Ruffus", "param_mode":"NONE"}
SELECT v, to_char(123 + v, 'FMRN') AS rn
 FROM generate_series (10, 12, 1) AS t(v);

-- Named parameters, param_mode is NAMED
--! {"name":"Buster", "param_mode":"NAMED"}
SELECT v, to_char(234 + v, 'FMRN') AS rn
 FROM generate_series (:lo, :hi, 1) AS t(v);

-- Positional parameters, param_mode is POSITIONAL
--! {"name":"Gracie", "param_mode":"POSITIONAL"}
SELECT v, to_char(345 + v, 'FMRN') AS rn
 FROM generate_series (?, ?, 1) AS t(v);
```
- **Note these lines** that provide a name and parameters' mode value to each query:  
```
--! {"name":"Ruffus", "param_mode":"NONE"}  
--! {"name":"Buster", "param_mode":"NAMED"}  
--! {"name":"Gracie", "param_mode":"POSITIONAL"}
```

- **Methods return** a [PDOStatement](https://www.php.net/manual/en/class.pdostatement.php) object.

### SQLMethods constructor

A SQLMethods object is created by instantiating the `SQLMethods` class.  
`SQLMethods::__construct(<sql file name>, optional <PDO connection>);`
 - `<sql file name>` - qualified file name of the SQL file (as the one above)
 - `<PDO connection>>` - existing PDO connection object

### Connection getter/setter method

`SQLMethods::connection(optional <PDO connection>);`
 - `<PDO connection>>` - existing PDO connection object
 - returns the current connection or NULL if none exisits

### Usage (PHP CLI) in file _example.php_  
``` PHP
<?php
// Example/unit test
require ('SQLMethods.class.php'); // Or use your preferred class loading mechanism
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
$result = $osql -> Buster([':hi' => 17, ':lo' => 15]);
show($result);

// positional parameters
$result = $osql -> Gracie(18, 20);
show($result);
```
- Queries are now **methods** of the SQLMethods instance.  
```
$result = $osql -> Ruffus();  
$result = $osql -> Buster([':hi' => 17, ':lo' => 15]);  
$result = $osql -> Gracie(18, 20);  
```
   
### Here is the modest result.  
```
D:\devel\SQLMethods>php example.php
10 CXXXIII
11 CXXXIV
12 CXXXV

15 CCXLIX
16 CCL
17 CCLI

18 CCCLXIII
19 CCCLXIV
20 CCCLXV

```
