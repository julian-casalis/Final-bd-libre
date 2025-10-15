# Base de datos Libre de Ventas de amazon

## Descripción

Este proyecto se realizó con la intención de aprender normalizacion de datos en formato .csv para luego cargarlos en un motor de base de datos, en este caso MySQL y modificarlos en base a nuestra necesidad.

Con este objetivo en mente, elegimos un Dataset que contiene informacion sobre Ventas de amazon.

El referido Dataset puede ser encontrado en el siguiente enlace.
https://www.kaggle.com/datasets/fatihilhan/global-superstore-dataset/data

## Ecplicación de la Normalización Realizada

1. Primero descargue el archivo .csv y se corroboro si los datos estaban organizados y que tipo de separador usaban para comenzar a planidicar la estructura y las tablas.
2. Cree las tablas de la base de datos a partir del diagrama ENTIDAD-RELACION, la cual utilizando MySQL Workbech, ya me genera el codigo de la creacion de la base de datos, las tablas, atributos, clave primaria y foraneas.

   DIAGRAMA ENTIDAD-RELACION

   <img width="788" height="548" alt="image" src="https://github.com/user-attachments/assets/505f6bf8-61a4-4917-907a-c56055a05238" />

3. Creacion de la base de datos con la siguiente consulta

   ```sql
   CREATE DATABASE finaldb;
   ```

4. Luego la creacion de las tablas, la cual tiene datos del tipo integer y varchar, con las claves primarias y foraneas.

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
CREATE TABLE IF NOT EXISTS `mydb`.`ventas` (
  `vent_id` INT NOT NULL,
  `vent_ped_id` VARCHAR(255) NOT NULL,
  `vent_clie` INT NOT NULL,
  `vent_prod` INT NOT NULL,
  `vent_geo` INT NOT NULL,
  `vent_fech` INT NOT NULL,
  `vent_pedi` VARCHAR(45) NOT NULL,
  `vent_fech_envi` VARCHAR(45) NOT NULL,
  `vent_mod_env` VARCHAR(45) NOT NULL,
  `vent_priori` VARCHAR(45) NOT NULL,
  `ventas` DECIMAL(10,2) NOT NULL,
  `vent_cant` INT NOT NULL,
  `vent_desc` DECIMAL(5,2) NOT NULL,
  `vent_bene` DECIMAL(10,2) NOT NULL,
  `vent_cost_envi` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`vent_id`),
  INDEX `clie_id_idx` (`vent_clie` ASC) VISIBLE,
  INDEX `prod_id_idx` (`vent_prod` ASC) VISIBLE,
  INDEX `geo_id_idx` (`vent_geo` ASC) VISIBLE,
  INDEX `fech_id_idx` (`vent_fech` ASC) VISIBLE,
  CONSTRAINT `vent_clie`
    FOREIGN KEY (`vent_clie`)
    REFERENCES `mydb`.`clientes` (`clie_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `vent_prod`
    FOREIGN KEY (`vent_prod`)
    REFERENCES `mydb`.`productos` (`prod_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `vent_geo`
    FOREIGN KEY (`vent_geo`)
    REFERENCES `mydb`.`geografia` (`geo_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `vent_fech`
    FOREIGN KEY (`vent_fech`)
    REFERENCES `mydb`.`fecha de pedido` (`fech_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
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

Para evitar repetición, solo incluímos ese comando ya que lo unico que cambia es la ruta del archivo y el nombre de la tabla.
