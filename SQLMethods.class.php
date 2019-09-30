<?php

class SQLMethods
{
    const   PARAM_NONE = 'NONE', PARAM_NAMED = 'NAMED', PARAM_POSITIONAL = 'POSITIONAL';
    const   QUERY_DEF_HEADER_RX = '/^--!(.*)$/i',
            QUERY_IDENT_RX = '/^[a-z_]\w{2,31}$/i',
            SQL_COMMENT_RX = '/^\s*--/';
    private $conn, $qlist;

    public function __construct($sql_filename, $conn = null)
    {
        $this -> conn = $conn;
        $this -> qlist = (object)[];
        $query_object = (object)['query' => ''];
        $query_name = null;

        $sqlfile = @fopen($sql_filename, 'r');
        if (!$sqlfile)
        {
            throw new Exception("SQLMethods: SQL file {$sql_filename} failed to open");
        }
        $linenumber = 0;
        while (($line = fgets($sqlfile)) !== FALSE)
        {
            $linenumber++;
            if (trim($line) == '') continue;

            if (preg_match(self::QUERY_DEF_HEADER_RX, $line, $result))
            {
                $query_parameters = json_decode(trim($result[1]));
                if
                (
                    is_null($query_parameters) || (count((array) $query_parameters) != 2) ||
                    !isset($query_parameters -> name) || !isset($query_parameters -> param_mode) ||
                    !preg_match(self::QUERY_IDENT_RX, $query_parameters -> name) ||
                    !in_array($query_parameters -> param_mode, [self::PARAM_NONE, self::PARAM_NAMED, self::PARAM_POSITIONAL])
                )
                {
                    throw new Exception("SQLMethods: Bad query definition header, file '{$sql_filename}', line {$linenumber}");
                }
                if (!is_null($query_name))
                {
                    $this -> qlist -> $query_name = $query_object;
                    $query_object = (object)['query' => ''];
                }
                $query_name = $query_parameters -> name;
                $query_object -> parameterized = $query_parameters -> param_mode;
                continue;
            }

            if (!is_null($query_name) && !preg_match(self::SQL_COMMENT_RX, $line))
            {
                $query_object -> query .= $line;
            }
        }
        fclose($sqlfile);

        if (!is_null($query_name))
        {
            $this -> qlist -> $query_name = $query_object;
        }
    }

    public function connection($conn = null)
    {
        $current_connection = $this -> conn;
        if (!is_null($conn))
        {
            $this -> conn = $conn;
        }
        return $current_connection;
    }

    public function __call($function_name, $arguments = null)
    {
        if (!isset($this -> qlist -> $function_name))
        {
            throw new Exception("SQLMethods: Method (query) '{$function_name}' not defined");
        }

        $qdef = $this -> qlist -> $function_name;
        switch ($qdef -> parameterized)
        {
            case self::PARAM_NONE:
                $rs = $this -> conn -> query($qdef->query);
                break;

            case self::PARAM_NAMED:
                $rs = $this -> conn -> prepare($qdef->query);
                $rs -> execute($arguments[0]);
                break;

            case self::PARAM_POSITIONAL:
                $rs = $this -> conn -> prepare($qdef->query);
                $rs -> execute($arguments);
        }
        return $rs;
    }

    // For debugging purposes
    public function __toString()
    {
        return print_r($this -> qlist, TRUE);
    }
}
