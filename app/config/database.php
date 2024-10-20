<?php
class Database
{
    protected $servername = DB_SERVER;
    protected $username = DB_USERNAME;
    protected $password = DB_PASSWORD;
    protected $dbname = DB_NAME;
    protected $pdo;
    protected $table;

    public function __construct()
    {
        try {
            $this->pdo = new PDO("mysql:host=".$this->servername.";dbname=".$this->dbname, $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function __destruct()
    {
        $this->pdo = null;
    }

    public function setTable($table)
    {
        $this->table = $table;
    }

    public function select($columns = "*", $condition = null, $params = [])
    {
        $sql = "SELECT $columns FROM " . $this->table;

        if ($condition) {
            $sql .= " WHERE $condition";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($data)
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        
        $sql = "INSERT INTO " . $this->table . " ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function find($id)
    {
        $sql = "SELECT * FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function count($condition = null, $params = [])
    {
        $sql = "SELECT COUNT(*) as count FROM " . $this->table;

        if ($condition) {
            $sql .= " WHERE $condition";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function delete($condition, $params = [])
    {
        $sql = "DELETE FROM " . $this->table . " WHERE $condition";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function update($data, $condition, $params = [])
    {
        $set = "";
        foreach ($data as $column => $value) {
            $set .= "$column = :$column, ";
        }
        $set = rtrim($set, ", ");
        
        $sql = "UPDATE " . $this->table . " SET $set WHERE $condition";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array_merge($data, $params));
    }
}
