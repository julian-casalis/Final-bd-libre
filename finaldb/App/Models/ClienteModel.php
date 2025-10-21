<?php
require_once __DIR__ . '/../../core/Model.php';

class ClienteModel extends Model
{
    protected $table = 'clientes';
    const RECORDS_PER_PAGE = 10;

    public function getPaginated($page = 1)
    {
        $offset = ($page - 1) * self::RECORDS_PER_PAGE;
        $limit = self::RECORDS_PER_PAGE;

        $sql = "SELECT clie_id, clie_cod, clie_nom, clie_seg 
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
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE clie_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createCliente($codigo, $nombre, $seg)
    {
        $sql = "INSERT INTO {$this->table} (clie_cod, clie_nom, clie_seg)
                VALUES (:codigo, :nombre, :seg)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':codigo', $codigo);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':seg', $seg);

        return $stmt->execute();
    }


    public function getFiltered($page = 1, $search = '', $sort = 'id_asc')
    {
        $offset = ($page - 1) * self::RECORDS_PER_PAGE;
        $limit = self::RECORDS_PER_PAGE;

        // Orden
        $sortParts = explode('_', $sort);
        $orderColumn = match ($sortParts[0]) {
            'id' => 'clie_id',
            'codigo' => 'clie_cod',
            'nombre' => 'clie_nom',
            'seg' => 'clie_seg',
            default => 'clie_id'
        };
        $orderDir = ($sortParts[1] ?? 'asc') === 'asc' ? 'ASC' : 'DESC';



        $sql = "SELECT * FROM {$this->table} 
            WHERE clie_cod LIKE :s OR clie_nom LIKE :s OR clie_seg LIKE :s
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
        WHERE clie_cod LIKE :s OR clie_nom LIKE :s OR clie_seg LIKE :s");
        $stmt->bindValue(':s', "%$search%");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function updateCliente($id, $codigo, $nombre, $seg)
    {
        $sql = "UPDATE {$this->table} SET clie_cod = :codigo, clie_nom = :nombre, clie_seg = :seg WHERE clie_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':codigo', $codigo);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':seg', $seg);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function cambiarEstado($id, $estado)
    {
        $sql = "UPDATE {$this->table} SET clie_activo = :estado WHERE clie_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
