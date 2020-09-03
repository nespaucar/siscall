<?php

class Cado
{
    private $db;
    public function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;dbname=siscall', 'root', '');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $this->db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, 1);
        $this->db->setAttribute(PDO::ATTR_PERSISTENT, true);
    }

    public function conn() {
        $conn = mysqli_connect("localhost","root","","siscall");
        return $conn;
    }

    public function ejecutarConsulta($sql)
    {
        $this->db->beginTransaction();
        $this->db->query("SET NAMES 'utf8'");
        $result = $this->db->query($sql);
        $this->db->commit();
        return $result;
    }
}
