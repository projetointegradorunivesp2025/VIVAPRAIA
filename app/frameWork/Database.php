<?php
/**
 * app/frameWork/Database.php
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Grupo Projeto Integrador UNIVESP
 * @copyright  Copyright (c) 2025 
 * @license    Licença Pública Geral GNU (GPL3)
 */

class Database {
    private $host;
    private $dbName;
    private $username;
    private $password;
    private $conn;

    // Construtor: Estabelece a conexão automaticamente ao criar a instância
    public function __construct() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Exceções para erros
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Retorno como array associativo
                PDO::ATTR_EMULATE_PREPARES => false, // Desabilita a emulação de consultas preparadas segurança contra SQL Injection
            ];
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            die("Erro de conexão: " . $e->getMessage());
        }
    }

    // Método genérico para SELECT
    public function select($query, $params = []) {
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            die("Erro na consulta: " . $e->getMessage());
        }
    }

    // Método genérico para INSERT, UPDATE, DELETE
    public function execute($query, $params = []) {
        try {
            $stmt = $this->conn->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            die("Erro na execução: " . $e->getMessage());
        }
    }

    // Método para obter o último ID inserido
    public function lastInsertId() {
        return $this->conn->lastInsertId();
    }
}

