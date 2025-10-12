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

4. Luego la creacion de las tablas, la cual tiene datos del tipo integer y varchar, con las claves primarias y foraneas.

° Tabla categoria

    ```sql
     CREATE TABLE IF NOT EXISTS `finaldb`.`categoria` (`cate_id`
      INT NOT NULL AUTO_INCREMENT,
      `categoria`
      VARCHAR(255) NOT NULL,
      PRIMARY KEY (`cate_id`)7
      );
        
° Tabla subcategiria

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



   

   



