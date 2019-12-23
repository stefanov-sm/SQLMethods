-- SQLMethods example SQL file

-- Query definition header format:
-- Single line starts with --! followed by metadata in JSON
-- Metadata JSON format: {"name":<query name>, "param_mode":<parameters mode> [,"returns_value":<return mode>]}
-- <query name> is a valid K&R indentifier string that becomes a method name;
-- <parameters mode> is one of "NONE", "NAMED" or "POSITIONAL"
-- <return mode> (optional) is true or false (default)
-- See the example below

-- Positional parameters, returns a single value
--! {"name":"ISOTime", "param_mode":"POSITIONAL", "returns_value": true}
SELECT to_char(now() - ?::interval, 'YYYY-MM-DD"T"HH24:MI:SS');
