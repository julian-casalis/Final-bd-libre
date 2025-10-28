<?php
class Model
{
    protected $db;
    protected $table;

    public function __construct()
    {
        try {
            $this->db = new PDO(
                'mysql:host=localhost;dbname=finaldb;charset=utf8mb4',
                'root',
                ''
            );
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("<strong>❌ Error de conexión:</strong> " . $e->getMessage());
        }
    }
}
