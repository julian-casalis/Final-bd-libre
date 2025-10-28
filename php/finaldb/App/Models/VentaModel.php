<?php
require_once __DIR__ . '/../../core/Model.php';

class VentaModel extends Model
{
    protected $table = 'ventas';
    const RECORDS_PER_PAGE = 20;

    public function getPaginated($page = 1)
    {
        $offset = ($page - 1) * self::RECORDS_PER_PAGE;
        $limit = self::RECORDS_PER_PAGE;

        $sql = "SELECT 
            -- v.vent_id,
            v.vent_ped_id,
            c.clie_nom AS cliente,
            p.prod_nom AS producto,
            ca.categoria AS categoria,
            s.subc_nom AS subc,
            g.ciudad AS ciudad,
            rm.regi_nom AS region,
            rm.mer_nom AS mercado,
            f.fech_com as fecha,
            v.vent_mod_env,
            v.vent_priori,
            v.vent_ventas,
            v.vent_cant,
            v.vent_bene,
            v.vent_cost_envi
        FROM ventas v
        JOIN clientes c ON v.vent_clie = c.clie_id
        JOIN productos p ON v.vent_prod = p.prod_id
        JOIN subcategoria s ON p.prod_subc = s.subc_id
        JOIN categoria ca ON s.subc_cate = ca.cate_id
        JOIN geografia g ON v.vent_geo = g.geo_id
        JOIN region_mercado rm ON g.geo_regi = rm.regi_id
        JOIN fecha_pedido f ON v.vent_fech = f.fech_id
        ORDER BY v.vent_id DESC
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
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE vent_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getFiltered($filters = [], $page = 1)
    {
        $offset = ($page - 1) * self::RECORDS_PER_PAGE;
        $limit = self::RECORDS_PER_PAGE;

        $sql = "SELECT DISTINCT
    -- v.vent_id,
            v.vent_ped_id,
            c.clie_nom AS cliente,
            p.prod_nom AS producto,
            ca.categoria AS categoria,
            s.subc_nom AS subc,
            g.ciudad AS ubicacion,
            f.fech_com as fecha,
            rm.regi_nom AS region,
            rm.mer_nom AS mercado,
            v.vent_fech,
            v.vent_mod_env,
            v.vent_priori,
            v.vent_ventas,
            v.vent_cant,
            v.vent_bene,
            v.vent_cost_envi
            FROM ventas v
            JOIN clientes c ON v.vent_clie = c.clie_id
            JOIN productos p ON v.vent_prod = p.prod_id
            JOIN subcategoria s ON p.prod_subc = s.subc_id
            JOIN categoria ca ON s.subc_cate = ca.cate_id
            JOIN geografia g ON v.vent_geo = g.geo_id
            JOIN region_mercado rm ON g.geo_regi = rm.regi_id
            JOIN fecha_pedido f ON v.vent_fech  = f.fech_id
            WHERE 1=1";

        $params = [];

        // 🔍 Filtros
        if (!empty($filters['subcategoria'])) {
            $sql .= " AND s.subc_id = :subcategoria";
            $params[':subcategoria'] = $filters['subcategoria'];
        }
        if (!empty($filters['region'])) {
            $sql .= " AND rm.regi_id = :region";
            $params[':region'] = $filters['region'];
        }
        if (!empty($filters['mercado'])) {
            $sql .= " AND rm.mer_nom = :mercado";
            $params[':mercado'] = $filters['mercado'];
        }
        if (!empty($filters['ciudad'])) {
            $sql .= " AND g.ciudad = :ciudad";
            $params[':ciudad'] = $filters['ciudad'];
        }

        if (!empty($filters['modo_envio'])) {
            $sql .= " AND v.vent_mod_env = :modo_envio";
            $params[':modo_envio'] = $filters['modo_envio'];
        }

        if (!empty($filters['priori'])) {
            $sql .= " AND v.vent_priori = :priori";
            $params[':priori'] = $filters['priori'];
        }


        if (!empty($filters['buscar'])) {
            $sql .= " AND (
            agregar pais estado
                    subcategoria
                    
             LIKE :buscar OR
            g.ciudad LIKE :buscar OR
            v.vent_mod_env LIKE :buscar OR
            v.vent_priori LIKE :buscar
        )";
            $params[':buscar'] = '%' . $filters['buscar'] . '%';
        }

        // 🧭 ORDENAMIENTO
        $allowedOrderColumns = [
            'codigo' => 'v.vent_ped_id',
            'fecha' => 'f.fech_com',
            'cliente' => 'c.clie_nom',
            'producto' => 'p.prod_nom',
            'prioridad' => 'v.vent_priori',
            'ventas' => 'v.vent_ventas',
            'cantidad' => 'v.vent_cant',
            'beneficio' => 'v.vent_bene',
            'envio' => 'v.vent_cost_envi'
        ];

        $orderBy = $allowedOrderColumns[$filters['ordenar_por'] ?? 'id'] ?? 'v.vent_id';
        $orderDir = strtoupper($filters['direccion'] ?? 'DESC');

        // Evitar inyección
        if (!in_array($orderDir, ['ASC', 'DESC'])) {
            $orderDir = 'DESC';
        }

        $sql .= " ORDER BY $orderBy $orderDir LIMIT :limit OFFSET :offset";

        // Ejecutar consulta
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategorias()
    {
        $stmt = $this->db->query("SELECT cate_id, categoria FROM categoria ORDER BY categoria");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSubcategorias()
    {
        $stmt = $this->db->query("SELECT subc_id, subc_nom FROM subcategoria ORDER BY subc_nom");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRegiones()
    {
        $stmt = $this->db->query("SELECT regi_id, regi_nom FROM region_mercado ORDER BY regi_nom");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMercados()
    {
        $stmt = $this->db->query("SELECT DISTINCT mer_nom FROM region_mercado ORDER BY mer_nom");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCiudades()
    {
        $stmt = $this->db->query("SELECT DISTINCT ciudad FROM geografia ORDER BY ciudad");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getModosEnvio()
    {
        $stmt = $this->db->query("SELECT DISTINCT vent_mod_env FROM ventas ORDER BY vent_mod_env");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPriori()
    {
        $stmt = $this->db->query("SELECT DISTINCT vent_priori FROM ventas ORDER BY vent_priori");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReporte($tipo, $filters = [])
    {
        $sql = "";
        $params = [];

        switch ($tipo) {
            case 'promedio_ventas':
                $sql = "SELECT p.prod_nom AS Producto, ROUND(AVG(v.vent_cant),2) AS 'Promedio Ventas'
                    FROM ventas v
                    JOIN productos p ON v.vent_prod = p.prod_id
                    GROUP BY p.prod_nom
                    ORDER BY `Promedio Ventas` DESC";
                break;

            case 'total_ventas':
                $sql = "SELECT p.prod_nom AS Producto, SUM(v.vent_cant) AS 'Cantidad Total'
                    FROM ventas v
                    JOIN productos p ON v.vent_prod = p.prod_id
                    GROUP BY p.prod_nom
                    ORDER BY `Cantidad Total` DESC";
                break;

            case 'total_beneficio':
                $sql = "SELECT p.prod_nom AS Producto, SUM(v.vent_ventas) AS 'Ventas total'
                    FROM ventas v
                    JOIN productos p ON v.vent_prod = p.prod_id
                    GROUP BY p.prod_nom
                    ORDER BY `Ventas total` DESC";
                break;

            case 'total_envio':
                $sql = "SELECT p.prod_nom AS Producto, SUM(v.vent_cost_envi) AS 'Costo Total Envío'
                    FROM ventas v
                    JOIN productos p ON v.vent_prod = p.prod_id
                    GROUP BY p.prod_nom
                    ORDER BY `Costo Total Envío` DESC";
                break;

            // case 'ventas_maximas':
            //     $sql = "SELECT p.prod_nom AS Producto, MAX(v.vent_ventas) AS 'Venta Máxima'
            //         FROM ventas v
            //         JOIN productos p ON v.vent_prod = p.prod_id
            //         GROUP BY p.prod_nom
            //         ORDER BY `Venta Máxima` DESC";
            //     break;

            // case 'ventas_minimas':
            //     $sql = "SELECT p.prod_nom AS Producto, MIN(v.vent_ventas) AS 'Venta Mínima'
            //         FROM ventas v
            //         JOIN productos p ON v.vent_prod = p.prod_id
            //         GROUP BY p.prod_nom
            //         ORDER BY `Venta Mínima` ASC";
            //     break;

            case 'ventas_prioridad':
                $sql = "SELECT v.vent_priori AS Prioridad, COUNT(*) AS 'Cantidad Ventas', SUM(v.vent_ventas) AS 'Total Vendido'
                    FROM ventas v
                    GROUP BY v.vent_priori
                    ORDER BY `Total Vendido` DESC";
                break;

            case 'ventas_envio':
                $sql = "SELECT v.vent_mod_env AS 'Modo Envío', COUNT(*) AS 'Cantidad Ventas', SUM(v.vent_ventas) AS 'Total Vendido'
                    FROM ventas v
                    GROUP BY v.vent_mod_env
                    ORDER BY `Total Vendido` DESC";
                break;

            default:
                return [];
        }

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($orden) {
            $col = $orden['col'];
            $dir = strtoupper($orden['dir']) === 'ASC' ? 'ASC' : 'DESC';
            $sql .= " ORDER BY `$col` $dir";
        }

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
