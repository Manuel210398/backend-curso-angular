CREATE DATABASE IF NOT EXISTS curso_angular;
USE curso_angular;
CREATE TABLE productos
(
  idProducto int(255) auto_increment not null,
  nombre varchar (255) not null,
  descripcion text not null,
  precio varchar (255) not null,
  imagen varchar (255) not null,
  CONSTRAINT pk_productos PRIMARY KEY (idProducto)
);
