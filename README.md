# SQL Methods
Lightweight PHP class with no dependencies that dynamically exposes methods defined as queries in a SQL file.<br/>
Clean separation of concerns inspired by [this](https://www.youtube.com/watch?v=q9IXCdy_mtY) talk and __YeSQL__.
Al that matters is there in the PHP CLI example.

Queries that become methods. The SQL file:

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
 FROM generate_series (10, 20, 1) AS t(v);

-- Named parameters, param_mode is NAMED
--! {"name":"Buster", "param_mode":"NAMED"}
SELECT v, to_char(234 + v, 'FMRN') AS rn
 FROM generate_series (:lo, :hi, 1) AS t(v);

-- Positional parameters, param_mode is POSITIONAL
--! {"name":"Gracie", "param_mode":"POSITIONAL"}
SELECT v, to_char(345 + v, 'FMRN') AS rn
 FROM generate_series (?, ?, 1) AS t(v);
```

How to use (PHP CLI): 

```
D:\devel\SQLMethods>php example.php
10 CXXXIII
11 CXXXIV
12 CXXXV
13 CXXXVI
14 CXXXVII
15 CXXXVIII
16 CXXXIX
17 CXL
18 CXLI
19 CXLII
20 CXLIII

18 CCLII
19 CCLIII
20 CCLIV

18 CCCLXIII
19 CCCLXIV
20 CCCLXV
```
