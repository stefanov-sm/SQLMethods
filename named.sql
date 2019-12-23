-- SQLGateway example SQL file

-- Query definition header format:
-- Single line starts with --! followed by metadata in JSON
-- Metadata JSON format: {"name":<query name>, "param_mode":<parameters mode> [,"returns_value":<return mode>]}
-- <query name> is a valid K&R indentifier string that becomes a method name;
-- <parameters mode> is one of "NONE", "NAMED" or "POSITIONAL"
-- <return mode> (optional) is true or false (default)
-- See the example below

-- Named parameters
--! {"name":"Buster", "param_mode":"NAMED"}
SELECT v, to_char(234 + v, 'FMRN') AS rn
 FROM generate_series (:lo, :hi, 1) AS t(v);