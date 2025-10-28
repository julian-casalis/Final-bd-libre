<?php
require_once __DIR__ . '/../../core/Model.php';

class CategoriaModel extends Model
{
    protected $table = 'categoria';
    const RECORDS_PER_PAGE = 10;

    public function getPaginated($page = 1)
    {
        $offset = ($page - 1) * self::RECORDS_PER_PAGE;
        $limit = self::RECORDS_PER_PAGE;

        $sql = "SELECT cate_id, categoria 
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

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE cate_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createCategoria($categoria)
    {
        $sql = "INSERT INTO {$this->table} (categoria)
                VALUES (:categoria)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':categoria', $categoria);

        return $stmt->execute();
    }


    public function getFiltered($page = 1, $search = '', $sort = 'id_asc')
    {
        $offset = ($page - 1) * self::RECORDS_PER_PAGE;
        $limit = self::RECORDS_PER_PAGE;

        // Orden
        $sortParts = explode('_', $sort);
        $orderColumn = match ($sortParts[0]) {
            'id' => 'cate_id',
            'categoria' => 'categoria',
            default => 'cate_id'
        };
        $orderDir = ($sortParts[1] ?? 'asc') === 'asc' ? 'ASC' : 'DESC';

        $sql = "SELECT * FROM {$this->table} 
            WHERE categoria LIKE :s
            ORDER BY $orderColumn $orderDir
            LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':s', "%$search%");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalRecordsFiltered($search = '')
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} 
        WHERE categoria LIKE :s ");
        $stmt->bindValue(':s', "%$search%");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function updateCategoria($id, $categoria)
    {
        $sql = "UPDATE {$this->table} SET categoria= :categoria WHERE cate_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function cambiarEstado($id, $estado)
    {
        $sql = "UPDATE {$this->table} SET cate_activo = :estado WHERE cate_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
