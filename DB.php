<?php

class DB {
    private static $conn;
    private $sql;

    /**
     * @var PDOStatement
     */
    private $stmt;
    private $params;
    private $where;

    public function __construct() {
        $config = parse_ini_file("config.ini");
        $host = $config["host"];
        $dbname = $config["dbname"];

        try {
            self::$conn = new PDO(
                "mysql:host=$host;dbname=$dbname",
                $config["username"],
                $config["password"]);
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        if (!self::$conn) {
            die(self::$conn->errorInfo());
        }
    }

    public function query($table) {
        $this->where = false;
        $this->params = [];

        $this->sql = "SELECT * FROM {$table}";
        return $this;
    }

    public function join($table, $column, $type = "LEFT") {
        $this->sql .= " $type JOIN $table USING ($column) ";
        return $this;
    }

    public function where($column, $value, $logic = "AND") {
        if ($this->where) {
            $this->sql .= " $logic $column = :$column";
        }
        else {
            $this->sql .= " WHERE $column = :$column";
            $this->where = true;
        }
        $this->params[$column] = $value;

        return $this;
    }

    public function execute() {
        $this->stmt = self::$conn->prepare($this->sql);
        $this->stmt->execute($this->params);

        return $this;
    }

    public function fetch() {
        return $this->stmt->fetch();
    }

    public function fetchAll() {
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($table, $object) {
        $keys = [];
        $placeholders = [];
        $values = [];
        foreach ($object as $key => $value) {
            $keys[] = $key;
            $placeholders[] = ":$key";
            $values[$key] = $value;
        }
        $keys = implode(",", $keys);
        $placeholders = implode(",", $placeholders);

        $this->sql = "INSERT INTO $table ($keys) VALUES ($placeholders)";
        $this->stmt = self::$conn->prepare($this->sql);
        $this->stmt->execute($values);

        return self::$conn->lastInsertId();
    }

    public function __destruct() {
        self::$conn = null;
    }
}