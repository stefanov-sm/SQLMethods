-- SQLGateway example SQL file

-- Query definition header format:
-- Single line starts with --! followed by m
-- Metadata JSON format: {"name":<query name
-- <query name> is a valid K&R indentifier s
-- <parameters mode> is one of "NONE", "NAME
-- See the example below

-- Named parameters
--! {"name":"Buster", "param_mode":"NAMED"}
SELECT v, to_char(234 + v, 'FMRN') AS rn
 FROM generate_series (:lo, :hi, 1) AS t(v);