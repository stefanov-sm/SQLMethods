# SQLMethods
Lightweight PHP class with no dependencies to dynamically define methods as queries in SQL files.<br/>
Clean separation of concerns inspired by [this](https://www.youtube.com/watch?v=q9IXCdy_mtY) talk and **YeSQL**.
All that matters is there in the PHP CLI example.

### File _example.sql_ - the queries that become methods
Individual queries have names (Ruffus, Buster, Gracie in the example below) that become methods' names.

```SQL
-- SQLMethods example SQL file

-- Query definition header format:
-- Single line starts with --! followed by metadata in JSON
-- Metadata JSON format: {"name":<query name>, "param_mode":<parameters mode>}
-- <query name> is a valid K&R indentifier string that becomes a method name;
-- <parameters mode> is one of "NONE", "NAMED" or "POSITIONAL"
-- See the example below

-- No parameters
--! {"name":"RUFFUS", "param_mode":"NONE"}
SELECT v, to_char(123 + v, 'FMRN')
 FROM generate_series (10, 12, 1) t(v);

-- Named parameters
--! {"name":"Buster", "param_mode":"NAMED"}
SELECT v, to_char(234 + v, 'FMRN')
 FROM generate_series (:lo, :hi, 1) t(v);

-- Positional parameters
--! {"name":"Gracie", "param_mode":"POSITIONAL"}
SELECT v, to_char(345 + v, 'FMRN')
 FROM generate_series (?, ?, 1) t(v);
```
- Note these query definition header lines that provide a name and parameters' mode value to each query:  
```
--! {"name":"RUFFUS", "param_mode":"NONE"}  
--! {"name":"Buster", "param_mode":"NAMED"}  
--! {"name":"Gracie", "param_mode":"POSITIONAL"}
```

- Each query definition header consists of a single line that starts with `--!` prefix followed by a JSON expression with exactly these attributes: `"name"` and `"param_mode"`.
- The value of "param_mode" must be one of `"NONE"`, `"NAMED"` or `"POSITIONAL"`. The semantics of those are best seen in the example.
- The value of "name" must be a valid K&R-style identifier.
- Real-life queries may be of any size and complexity.
- Block comments are not supported.
- Methods return [PDOStatement](https://www.php.net/manual/en/class.pdostatement.php) objects.  

### SQLMethods constructor

A SQLMethods object is created by instantiating the SQLMethods class.  
`SQLMethods::__construct(<sql file name>, optional <PDO connection>)`
 - `<sql file name>` - name of the SQL file (as the one above)
 - `<PDO connection>` - existing PDO connection object

### SQLMethods instance factory

Static method. Creates and returns a SQLMethods instance object that can be chained.   
`SQLMethods::createInstance(optional <PDO connection>)`
 - `<PDO connection>` - existing PDO connection object

### Connection setter

Sets the PDO connection  
`SQLMethods::connection(<PDO connection>)`
 - `<PDO connection>` - existing PDO connection object
 - returns the object instance that an be chained

### SQL file importer

Parses and imports SQL method definitions.   
`SQLMethods::import(<filename>)`
 - `<filename>` - existing SQL file
 - returns the object instance that an be chained


### Usage example (PHP CLI) in file _example.php_  
This particular example uses [PostgreSQL](https://www.postgresql.org/) PDO connection.
```PHP
<?php
// SQLMethods example/unit test

// Use your preferred class loading mechanism
require ('SQLMethods.class.php');

// Obtain a PDO connection in your preferred way
$conn = new PDO (
    '<your connection string>', '<username>', '<password>',
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => FALSE]
);

/* There are different ways to build SQLMethod instances and inport SQL files
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
```
Query names become methods. Query/method names are case-insensitive as it is common in SQL.
```PHP
$result = $dbgw -> Ruffus();
$result = $dbgw -> Buster([':hi' => 17, ':lo' => 15]);
$result = $dbgw -> Gracie(18, 20);
```
And here is the modest result.  
```
C:\ ... \SQLMethods>php example.php
v: 10
rn: CXXXIII
v: 11
rn: CXXXIV
v: 12
rn: CXXXV

v: 15
rn: CCXLIX
v: 16
rn: CCL
v: 17
rn: CCLI

v: 18
rn: CCCLXIII
v: 19
rn: CCCLXIV
v: 20
rn: CCCLXV
```
