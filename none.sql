-- SQLGateway example SQL file            
                                          
-- Query definition header format:        
-- Single line starts with --! followed by
-- Metadata JSON format: {"name":<query na
-- <query name> is a valid K&R indentifier
-- <parameters mode> is one of "NONE", "NA
-- See the example below                  
                                          
-- No parameters                          
--! {"name":"RUFFUS", "param_mode":"NONE"}
SELECT v, to_char(123 + v, 'FMRN') AS rn  
 FROM generate_series (10, 12, 1) AS t(v);