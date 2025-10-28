<?php
require_once __DIR__ . '/../../core/Model.php';

class ProductoModel extends Model
{
    protected $table = 'productos';
    const RECORDS_PER_PAGE = 10;

    public function getPaginated($page = 1)
    {
        $offset = ($page - 1) * self::RECORDS_PER_PAGE;
        $limit = self::RECORDS_PER_PAGE;

        $sql = "SELECT prod_id, prod_cod, prod_subc, prod_nom
                FROM {$this->table} 
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalRecords()
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM {$this->table}");
        return (int) $stmt->fetchColumn();
    }

    public function getByIdPro($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE prod_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createPro($codigo, $subc, $nombre)
    {
        $sql = "INSERT INTO {$this->table} (prod_cod, prod_subc, prod_nom)
                VALUES (:codigo, :subc, :nombre)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':codigo', $codigo);
        $stmt->bindParam(':subc', $subc);
        $stmt->bindParam(':nombre', $nombre);

        return $stmt->execute();
    }


    public function getFilteredPro($page = 1, $search = '', $sort = 'id_asc')
    {
        $offset = ($page - 1) * self::RECORDS_PER_PAGE;
        $limit = self::RECORDS_PER_PAGE;

        // Orden
        $sortParts = explode('_', $sort);
        $orderColumn = match ($sortParts[0]) {
            'id' => 'prod_id',
            'codigo' => 'prod_cod',
            'subc' => 'prod_subc',
            'nombre' => 'prod_nom',
            default => 'prod_id'
        };
        $orderDir = ($sortParts[1] ?? 'asc') === 'asc' ? 'ASC' : 'DESC';



        $sql = "SELECT * FROM {$this->table} 
            WHERE prod_cod LIKE :s OR prod_subc LIKE :s OR prod_nom LIKE :s
            ORDER BY $orderColumn $orderDir
            LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':s', "%$search%");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalRecordsFilteredPro($search = '')
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} 
        WHERE prod_cod LIKE :s OR prod_subc LIKE :s OR prod_nom LIKE :s");
        $stmt->bindValue(':s', "%$search%");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function updatePro($id, $codigo, $subc, $nombre)
    {
        $sql = "UPDATE {$this->table} SET prod_cod = :codigo, prod_subc = :subc, prod_nom = :nombre WHERE prod_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':codigo', $codigo);
        $stmt->bindParam(':subc', $subc);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function cambiarEstado($id, $estado)
    {
        $sql = "UPDATE {$this->table} SET prod_activo = :estado WHERE prod_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
