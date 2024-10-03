<?php
if (!defined("_crmfb")) die("Truy cập trái phép");
class database
{
    var $db;
    var $result;
    var $insert_id;
    var $sql = "";
    var $refix = "";
    var $servername;
    var $username;
    var $password;
    var $database;
    var $table = "";
    var $where = "";
    var $order = "";
    var $limit = "";
    function database(
        $config = array()
    ) {
        if (!empty($config)) {
            $this->init($config);
            $this->connect();
        }
    }
    function init($config = array())
    {
        foreach ($config as $k => $v) $this->$k = $v;
    }
    // Thay thế hàm khởi tạo
    function __construct($config = array())
    {
        if (!empty($config)) {
            $this->init($config);
            $this->connect();
        }
    }
    function connect()
    {
        $this->db = mysqli_connect($this->servername, $this->username, $this->password);
        if (!$this->db) {
            die('Lỗi kết nối');
        }
        if (!mysqli_select_db($this->db, $this->database)) {
            die(mysqli_errno($this->db) . ": " . mysqli_error($this->db));
            return false;
        }
        mysqli_query($this->db, 'SET NAMES "utf8"');
    }
    function query($sql = "")
    {
        if ($sql) $this->sql = str_replace('#_', $this->refix, $sql);
        $this->result = mysqli_query($this->db, $this->sql);
        if (!$this->result) {
            die("Truy vấn sai");
        }
        return $this->result;
    }
    function insert($data = array())
    {
        $key = "";
        $value = "";
        foreach ($data as $k => $v) {
            $key .= "," . $k;
            $value .= ",'" . $v . "'";
        }
        if ($key[0] == ",") $key[0] = "(";
        $key .= ")";
        if ($value[0] == ",") $value[0] = "(";
        $value .= ")";
        $this->sql = "insert into " . $this->refix . $this->table . $key . " values " . $value;
        $this->query();
        $this->insert_id = mysqli_insert_id($this->db);
        return $this->result;
    }
    function update($data = array())
    {
        $values = "";
        foreach ($data as $k => $v) {
            $values .= ", " . $k . " = '" . $v . "'";
        }
        if ($values[0] == ",") $values[0] = " ";
        $this->sql = "update " . $this->refix . $this->table . " set " . $values;
        $this->sql .= $this->where;
        return $this->query();
    }
    function delete()
    {
        $this->sql = "delete from " . $this->refix . $this->table . $this->where;
        return $this->query();
    }
    function select($str = "*")
    {
        $this->sql = "select " . $str;
        $this->sql .= " from " . $this->refix . $this->table;
        $this->sql .= $this->where;
        $this->sql .= $this->order;
        $this->sql .= $this->limit;
        return $this->query();
    }
    function num_rows()
    {
        return mysqli_num_rows($this->result);
    }
    function fetch_array()
    {
        return mysqli_fetch_assoc($this->result);
    }
    function result_array()
    {
        $arr = array();
        while ($row = mysqli_fetch_assoc($this->result)) $arr[] = $row;
        return $arr;
    }
    function setTable($str)
    {
        $this->table = $str;
    }
    function setWhere($key, $value = "")
    {
        if ($value != "") {
            if ($this->where == "") $this->where = " where " . $key . " = '" . $value . "'";
            else $this->where .= " and " . $key . " = '" . $value . "'";
        } else {
            if ($this->where == "") $this->where = " where " . $key;
            else $this->where .= " and " . $key;
        }
    }
}
$d = new database($config['database']);
