<?php

class SQLMethods
{
  const PARAM_MODES_RX      = '/^(none|named|positional)$/i',
        QUERY_DEF_HEADER_RX = '/^--!(.*)$/i',
        QUERY_IDENT_RX      = '/^[a-z_]\w{2,31}$/i',
        IGNORE_LINE_RX      = '/(^\s*--([^!]|$))|(^\s*$)/';

  private $conn, $qlist;

  public function __construct($sql_filename = null, $conn = null)
  {
    $this->conn = $conn;
    $this->qlist = (object)[];
    if (!is_null($sql_filename))
    {
      $this->import($sql_filename);
    }
  }

  public static function createInstance($conn = null)
  {
    return new self(null, $conn);
  }

  // PDO connection setter
  // ----------------------------------------------------
  public function connection($conn)
  {
    $this->conn = $conn;
    return $this;
  }

  // SQL file parse/import
  // ----------------------------------------------------
  public function import($sql_filename)
  {
    $query_object = (object)['query' => ''];
    $query_name = null;

    $sqlfile = @ fopen($sql_filename, 'r');
    if (!$sqlfile)
    {
      throw new Exception("SQLMethods: Failed to open SQL file '{$sql_filename}' for import");
    }
    $linenumber = 0;
    while (($line = fgets($sqlfile)) !== FALSE)
    {
      $linenumber++;
      if (preg_match(self::IGNORE_LINE_RX, $line)) continue;

      if (preg_match(self::QUERY_DEF_HEADER_RX, $line, $result))
      {
        $query_parameters = json_decode(trim($result[1]));
        if
        (
          is_null($query_parameters) || (count((array) $query_parameters) != 2) ||
          !isset($query_parameters->name) || !isset($query_parameters->param_mode) ||
          !preg_match(self::QUERY_IDENT_RX, $query_parameters->name) ||
          !preg_match(self::PARAM_MODES_RX, $query_parameters->param_mode)
        )
        {
          throw new Exception("SQLMethods: Bad query definition header, file '{$sql_filename}', line {$linenumber}");
        }
        if (!is_null($query_name))
        {
          $this->qlist->{strtoupper($query_name)} = $query_object;
          $query_object = (object)['query' => ''];
        }
        $query_name = $query_parameters->name;
        $query_object->parameterized = $query_parameters->param_mode;
        continue;
      }
      if (!is_null($query_name))
      {
        $query_object->query .= $line;
      }
    }
    fclose($sqlfile);

    if (!is_null($query_name))
    {
        $this->qlist->{strtoupper($query_name)} = $query_object;
    }
    return $this;
  }

  // Dynamic method invocation handler
  // ----------------------------------------------------
  public function __call($method, $arguments = null)
  {
    $method = strtoupper($method);
    if (!isset($this->qlist->{$method}))
    {
      throw new Exception("SQLMethods: Method/query '{$method}' not defined");
    }

    switch (strtoupper($this->qlist->{$method}->parameterized))
    {
      case 'NONE':
        $rs = $this->conn->query($this->qlist->{$method}->query);
        break;

      case 'NAMED':
        if (!isset($this->qlist->{$method}->prepared))
        {
          $this->qlist->{$method}->prepared = $this->conn->prepare($this->qlist->{$method}->query);
        }
        $rs = $this->qlist->{$method}->prepared;
        $rs->execute($arguments[0]);
        break;

      case 'POSITIONAL':
        if (!isset($this->qlist->{$method}->prepared))
        {
          $this->qlist->{$method}->prepared = $this->conn->prepare($this->qlist->{$method}->query);
        }
        $rs = $this->qlist->{$method}->prepared;
        $rs->execute($arguments);
    }
    return $rs;
  }

  // For testing & debugging purposes
  // ----------------------------------------------------
  public function __toString()
  {
    return print_r($this->qlist, TRUE);
  }

  public static function dump_rs($rs)
  {
    $retvalArray = [];
    foreach($rs as $running_record)
      foreach($running_record as $key => $value)
        if (is_string($key))
          $retvalArray[] = "{$key}: {$value}";
    return implode(PHP_EOL, $retvalArray);
  }
}
