-- SQLGateway example SQL file

-- Query definition header format:
-- Single line starts with --! followed by metadata in JSON
-- Metadata JSON format: {"name":<query name>, "param_mode":<parameters mode>}
-- <query name> is a valid K&R indentifier string that becomes a method name;
-- <parameters mode> is one of "NONE", "NAMED" or "POSITIONAL"
-- See the example below

-- Positional parameters
--! {"name":"Gracie", "param_mode":"POSITIONAL"}
SELECT v, to_char(345 + v, 'FMRN') AS rn
 FROM generate_series (?, ?, 1) AS t(v);