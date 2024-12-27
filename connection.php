<?php

class SQLServerConnection {
    private $connection = null;
    private $server;
    private $database;
    private $username;
    private $password;

    public function __construct($server, $database, $username, $password) {
        $this->server = $server;
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
    }

    public function connect() {
        try {
            $this->connection = new PDO(
                "sqlsrv:Server=$this->server;Database=$this->database",
                $this->username,
                $this->password
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return true;
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
            return false;
        }
    }

    public function select($query, $params = []) {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error en select: " . $e->getMessage();
            return [];
        }
    }

    public function update($query, $params = []) {
        try {
            $stmt = $this->connection->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            echo "Error en update $query: " . $e->getMessage();
            return false;
        }
    }

    public function executeSP($spName, $params = []) {
        try {
            $query = "EXEC " . $spName;
            if (!empty($params)) {
                $paramPlaceholders = str_repeat('?,', count($params) - 1) . '?';
                $query .= " " . $paramPlaceholders;
            }
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);

            try {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                if ($e->getCode() === 'IMSSP') {
                    return [];
                }
                throw $e;
            }
        } catch (PDOException $e) {
            echo "Error en stored procedure: " . $e->getMessage();
            return false;
        }
    }

    public function insert($query, $params = []) {
        try {
            // Validate params is an array
            if (!is_array($params)) {
                throw new InvalidArgumentException("Parameters must be an array");
            }

            // Validate each parameter
            foreach ($params as $param) {
                if (!is_string($param) && !is_numeric($param) and !is_null($param)) {
                    print_r_f($params);
                    throw new InvalidArgumentException("Parameters must be strings or numbers");
                }
            }

            $stmt = $this->connection->prepare($query);
            if ($stmt->execute($params)) {
                return $this->connection->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            echo "Error en insert: " . $e->getMessage();
            return false;
        } catch (InvalidArgumentException $e) {
            echo "Error de validación: " . $e->getMessage();
            return false;
        }
    }
}

class MySQLConnection {
    private $connection = null;
    private $host;
    private $database;
    private $username;
    private $password;

    public function __construct($host, $database, $username, $password) {
        $this->host = $host;
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
    }

    public function connect() {
        try {
            $this->connection = new PDO(
                "mysql:host=$this->host;dbname=$this->database;charset=utf8",
                $this->username,
                $this->password
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return true;
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
            return false;
        }
    }

    public function select($query, $params = []) {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error en select: " . $e->getMessage();
            return false;
        }
    }

    public function update($query, $params = []) {
        
        try {
            $stmt = $this->connection->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            echo "Error en update $query: " . $e->getMessage();
            return false;
        }
    }

    public function insert($query, $params = []) {
        try {
            // Validate params is an array
            if (!is_array($params)) {
                throw new InvalidArgumentException("Parameters must be an array");
            }

            // Validate each parameter
            foreach ($params as $param) {
                if (!is_string($param) && !is_numeric($param)) {
                    throw new InvalidArgumentException("Parameters must be strings or numbers");
                }
            }

            $stmt = $this->connection->prepare($query);
            if ($stmt->execute($params)) {
                return $this->connection->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            echo "Error en insert: " . $e->getMessage();
            return false;
        } catch (InvalidArgumentException $e) {
            echo "Error de validación: " . $e->getMessage();
            return false;
        }
    }
}

class PostgreSQLConnection {
    private $connection = null;
    private $host;
    private $port;
    private $database;
    private $username;
    private $password;

    public function __construct($host, $port, $database, $username, $password) {
        $this->host = $host;
        $this->port = $port;
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
    }

    public function connect() {
        try {
            $this->connection = new PDO(
                "pgsql:host=$this->host;port=$this->port;dbname=$this->database",
                $this->username,
                $this->password
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return true;
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
            return false;
        }
    }

    public function select($query, $params = []) {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error en select: " . $e->getMessage();
            return [];
        }
    }

    public function update($query, $params = []) {
        try {
            $stmt = $this->connection->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            echo "Error en update $query: " . $e->getMessage();
            return false;
        }
    }

    public function executeSP($spName, $params = []) {
        try {
            // En PostgreSQL, los stored procedures se llaman usando SELECT o CALL
            // dependiendo de si devuelven un resultado o no
            $query = "SELECT * FROM " . $spName . "(";
            if (!empty($params)) {
                $paramPlaceholders = str_repeat('?,', count($params) - 1) . '?';
                $query .= $paramPlaceholders;
            }
            $query .= ")";
            
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
            
            try {
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $results !== false ? $results : [];
            } catch (PDOException $e) {
                return [];
            }
        } catch (PDOException $e) {
            echo "Error en stored procedure: " . $e->getMessage();
            return [];
        }
    }

    public function insert($query, $params = []) {
        try {
            // Validate params is an array
            if (!is_array($params)) {
                throw new InvalidArgumentException("Parameters must be an array");
            }

            // Validate each parameter
            foreach ($params as $param) {
                if (!is_string($param) && !is_numeric($param)) {
                    throw new InvalidArgumentException("Parameters must be strings or numbers");
                }
            }

            $stmt = $this->connection->prepare($query);
            if ($stmt->execute($params)) {
                return $this->connection->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            echo "Error en insert: " . $e->getMessage();
            return false;
        } catch (InvalidArgumentException $e) {
            echo "Error de validación: " . $e->getMessage();
            return false;
        }
    }
}