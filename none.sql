-- SQLGateway example SQL file

-- Query definition header format:
-- Single line starts with --! followed by metadata in JSON
-- Metadata JSON format: {"name":<query name>, "param_mode":<parameters mode> [,"returns_value":<return mode>]}
-- <query name> is a valid K&R indentifier string that becomes a method name;
-- <parameters mode> is one of "NONE", "NAMED" or "POSITIONAL"
-- <return mode> (optional) is true or false (default)
-- See the example below

-- No parameters
--! {"name":"RUFFUS", "param_mode":"NONE"}
SELECT v, to_char(123 + v, 'FMRN') AS rn
 FROM generate_series (10, 12, 1) AS t(v);