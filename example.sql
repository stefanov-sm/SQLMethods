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
