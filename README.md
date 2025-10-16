# Base de datos Libre de Global Superstore

## Descripción

Este proyecto fue realizado con el objetivo de aprender el proceso de normalización de datos a partir de archivos en formato .csv, para luego cargarlos en un motor de base de datos —en este caso, MySQL— y modificarlos según nuestras necesidades.

Con este propósito, elegimos un dataset que contiene información sobre Global Superstore.

El dataset mencionado puede encontrarse en el siguiente enlace.
https://www.kaggle.com/datasets/fatihilhan/global-superstore-dataset/data

## Ecplicación de la Normalización Realizada

1. Primero se descargó el archivo .csv y se verificó que los datos estuvieran organizados correctamente, además de identificar el tipo de separador utilizado, con el fin de comenzar a planificar la estructura y las tablas de la base de datos.

2. Se crearon las tablas de la base de datos a partir del diagrama Entidad–Relación (E-R). Utilizando MySQL Workbench, se generó automáticamente el código para la creación de la base de datos, las tablas, sus atributos, las claves primarias y las claves foráneas.
   DIAGRAMA ENTIDAD-RELACIÓN

   <img width="788" height="548" alt="image" src="https://github.com/user-attachments/assets/505f6bf8-61a4-4917-907a-c56055a05238" />

3. Creación de la base de datos con la siguiente consulta

   ```sql
   CREATE DATABASE finaldb;
   ```

4. Luego la creación de las tablas, la cual tiene datos del tipo integer y varchar, con las claves primarias y foraneas.

- Tabla categoria

  ```sql
    CREATE TABLE IF NOT EXISTS `finaldb`.`categoria` (`cate_id`
     INT NOT NULL AUTO_INCREMENT,
     `categoria`
     VARCHAR(255) NOT NULL,
     PRIMARY KEY (`cate_id`)7
     );
  ```

- Tabla subcategiria

```sql
 CREATE TABLE IF NOT EXISTS `finaldb`.`subcategoria` (
  `subc_id` INT NOT NULL AUTO_INCREMENT,
  `subc_nom` VARCHAR(255) NOT NULL,
  `subc_cate` INT NOT NULL,
  PRIMARY KEY (`subc_id`),
  INDEX `cate_id_fk` (`subc_cate` ASC),
  CONSTRAINT fk_subc_cate
      FOREIGN KEY (`subc_cate`)
      REFERENCES `finaldb`.`categoria` (`cate_id`)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION);
```

- Tabla region_mercado

```sql
  CREATE TABLE IF NOT EXISTS `mydb`.`region_mercado` (
  `regi_id` INT NOT NULL AUTO_INCREMENT,
  `regi_nom` VARCHAR(255) NOT NULL,
  `mer_nom` VARCHAR(45) NOT NULL,
  `mer_nomd` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`regi_id`));
```

- Tabla grografia

```sql
  CREATE TABLE IF NOT EXISTS `mydb`.`geografia` (
  `geo_id` INT NOT NULL AUTO_INCREMENT,
  `pais` VARCHAR(255) NOT NULL,
  `estado` VARCHAR(45) NOT NULL,
  `ciudad` VARCHAR(45) NOT NULL,
  `geo_regi` INT NOT NULL,
  PRIMARY KEY (`geo_id`),
  INDEX `regi_id_idx` (`geo_regi` ASC) VISIBLE,
  CONSTRAINT `geo_regi`
    FOREIGN KEY (`geo_regi`)
    REFERENCES `mydb`.`region y mercado` (`regi_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
```

- Tabla clientes

```sql
CREATE TABLE IF NOT EXISTS `mydb`.`clientes` (
 `clie_id` INT NOT NULL AUTO_INCREMENT,
 `clie_cod` VARCHAR(255) NOT NULL,
 `clie_nom` VARCHAR(45) NOT NULL,
 `clie_seg` VARCHAR(45) NOT NULL,
 PRIMARY KEY (`clie_id`));
```

- Tabla fecha_pedido

```sql
CREATE TABLE IF NOT EXISTS `mydb`.`fecha de pedido` (
  `fech_id` INT NOT NULL AUTO_INCREMENT,
  `fech_com` DATE NOT NULL,
  `fech_ani` INT NULL,
  `fech_sem` INT NULL,
  PRIMARY KEY (`fech_id`));
```

- Tabla productos

```sql
CREATE TABLE IF NOT EXISTS `mydb`.`productos` (
  `prod_id` INT NOT NULL AUTO_INCREMENT,
  `prod_cod` VARCHAR(255) NOT NULL,
  `prod_subc` INT NOT NULL,
  `prod_nom` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`prod_id`),
  INDEX `subc_id_idx` (`prod_subc` ASC) VISIBLE,
  CONSTRAINT `produ_subc`
    FOREIGN KEY (`prod_subc`)
    REFERENCES `mydb`.`subcategoria` (`subc_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
```

- Tabla ventas

```sql
  DROP TEMPORARY TABLE IF EXISTS tmp_ventas_clie;
  CREATE TEMPORARY TABLE tmp_ventas_clie AS
  SELECT
      t.Order_ID,
      c.clie_id,
      t.Product_ID,
      t.City, t.State, t.Country,
      t.Order_Date,
      t.Order_Priority, t.Ship_Date, t.Ship_Mode,
      t.Sales, t.Quantity, t.Discount, t.Profit, t.Shipping_Cost
  FROM temp_full t
  JOIN clientes c ON c.clie_cod = t.Customer_ID
  LIMIT 1000 OFFSET 51000;

  -- se utiliza LIMIT ya que la base de datos contiene muchas finalas
  --  unir productos
  DROP TEMPORARY TABLE IF EXISTS tmp_ventas_prod;
  CREATE TEMPORARY TABLE tmp_ventas_prod AS
  SELECT v.*, p.prod_id
  FROM tmp_ventas_clie v
  JOIN productos p ON p.prod_cod = v.Product_ID;

  -- unir geografía
  DROP TEMPORARY TABLE IF EXISTS tmp_ventas_geo;
  CREATE TEMPORARY TABLE tmp_ventas_geo AS
  SELECT v.*, g.geo_id
  FROM tmp_ventas_prod v
  JOIN geografia g ON g.ciudad = v.City AND g.estado = v.State AND g.pais = v.Country;

  --  unir fecha
  DROP TEMPORARY TABLE IF EXISTS tmp_ventas_final;
  CREATE TEMPORARY TABLE tmp_ventas_final AS
  SELECT v.*, f.fech_id
  FROM tmp_ventas_geo v
  JOIN fecha_pedido f ON f.fech_com = v.Order_Date;

  -- Finalmente insertar
  INSERT INTO ventas (
      vent_ped_id, vent_clie, vent_prod, vent_geo, vent_fech,
      vent_priori, vent_fech_envi, vent_mod_env,
      vent_ventas, vent_cant, vent_desc, vent_bene, vent_cost_envi
  )
  SELECT
      Order_ID, clie_id, prod_id, geo_id, fech_id,
      Order_Priority, Ship_Date, Ship_Mode,
      Sales, Quantity, Discount, Profit, Shipping_Cost
  FROM tmp_ventas_final;
```

5. A partir de ahí comenzamos cargando los datos a la base con el siguiente comando:

```sql
   LOAD DATA local INFILE 'C:/xampp/htdocs/finalbd/super/data.csv'
INTO TABLE temp_full
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;
```

5. Una vez con la tablas ya creadas podremos empezar la carga de los datos:

- En esta etapa se procedió a importar los registros del archivo CSV a la base de datos MySQL.
  Para ello se utilizó la instrucción:

  ```sql
    LOAD DATA local INFILE 'C:/xampp/htdocs/finalbd/super/data.csv'
    INTO TABLE temp_full
    FIELDS TERMINATED BY ','
    ENCLOSED BY '"'
    LINES TERMINATED BY '\n'
    IGNORE 1 ROWS;
  ```

6. Inserción de datos normalizados.

- Una vez cargados los registros en la tabla temporal temp_full, se procedió a distribuir la información en las distintas tablas del modelo normalizado, de acuerdo con el diagrama Entidad–Relación.
  Para esto, se ejecutaron las siguientes consultas SQL que permiten poblar cada tabla con los datos correspondientes, evitando duplicados mediante el uso de DISTINCT y aplicando las relaciones entre entidades mediante claves foráneas.

- Tabla region_mercado

```sql
  INSERT INTO region_y_mercado (regi_nom, mer_nom, mer_nomd)
  SELECT DISTINCT Region, Market, Market2
  FROM temp_full;
```

- Tabla geografia

```sql
  INSERT INTO geografia (pais, estado, ciudad, geo_regi)
  SELECT DISTINCT Country, State, City, rm.regi_id
  FROM temp_full t
  JOIN region_mercado rm ON rm.regi_nom = t.Region;
```

- Tabla categoria

```sql
  INSERT INTO categoria (categoria)
  SELECT DISTINCT Category FROM temp_full;
```

- Tabla subcategoria

```sql
  INSERT INTO subcategoria (subc_nom, subc_cate)
  SELECT DISTINCT t.Sub_Category, c.cate_id
  FROM temp_full t
  JOIN categoria c ON c.categoria = t.Category;
```

- Tabla producto

```sql
  INSERT INTO productos (prod_cod, prod_nom, prod_subc)
  SELECT DISTINCT t.Product_ID, t.Product_Name, s.subc_id
  FROM temp_full t
  JOIN subcategoria s ON s.subc_nom = t.Sub_Category;
```

-Tabla clientes

```sql
  INSERT INTO clientes (clie_cod, clie_nom, clie_seg)
  SELECT DISTINCT t.Customer_ID, t.Customer_Name, rm.regi_id
  FROM temp_full t
  JOIN region_mercado rm ON rm.regi_nom = t.Region;
```

- Tabla fecha_pedido

```sql
  INSERT INTO fecha_pedido (fech_com, fech_ani, fech_sem)
  SELECT DISTINCT
    STR_TO_DATE(LEFT(t.Order_Date, 19), '%Y-%m-%d %H:%i:%s'),
    t.Year,
    t.weeknum
  FROM temp_full t;
```

- Tabla ventas

```sql
  INSERT INTO ventas (
vent_ped_id, vent_clie, vent_prod, vent_geo,vent_fech,
vent_priori, vent_fech_envi, vent_mod_env,
vent_ventas, vent_cant, vent_desc, vent_bene, vent_cost_envi
)
SELECT
  t.Order_ID,
  c.clie_id,
  p.prod_id,
  g.geo_id,
  f.fech_id,
  t.Order_Priority,
  t.Ship_Date,
  t.Ship_Mode,
  t.Sales,
  t.Quantity,
  t.Discount,
  t.Profit,
  t.Shipping_Cost
FROM temp_full t
JOIN clientes c ON c.clie_cod = t.Customer_ID
JOIN productos p ON p.prod_cod = t.Product_ID
JOIN geografia g ON g.ciudad = t.City AND g.estado = t.State AND g.pais = t.Country
JOIN fecha_pedido f ON f.fech_com = STR_TO_DATE(LEFT(t.Order_Date, 19), '%Y-%m-%d %H:%i:%s');

```
