CREATE DATABASE tienda;
USE tienda;

-- Tabla de usuarios
CREATE TABLE usuario (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario VARCHAR(50),
  contraseña VARCHAR(255),
  rol ENUM('CLIENTE','VENDEDOR','ALMACEN','CAJERO')
);

-- Tabla de productos
CREATE TABLE producto (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100),
  descripcion TEXT,
  precio DECIMAL(10,2),
  stock INT
);

-- Tabla de transacciones (flujo general)
CREATE TABLE tramite (
  nro_tramite INT AUTO_INCREMENT PRIMARY KEY,
  cliente_id INT,
  fecha_inicio DATETIME,
  estado ENUM('EN_PROCESO','FINALIZADO') DEFAULT 'EN_PROCESO',
  FOREIGN KEY (cliente_id) REFERENCES usuario(id)
);

-- Seguimiento de procesos por flujo
CREATE TABLE flujoseguimiento (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nro_tramite INT,
  flujo VARCHAR(50),
  proceso VARCHAR(50),
  usuario VARCHAR(50),
  fecha DATETIME,
  observacion TEXT,
  FOREIGN KEY (nro_tramite) REFERENCES tramite(nro_tramite)
);

-- Tabla intermedia para productos seleccionados por el cliente
CREATE TABLE detalle_pedido (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nro_tramite INT,
  producto_id INT,
  cantidad INT,
  FOREIGN KEY (nro_tramite) REFERENCES tramite(nro_tramite),
  FOREIGN KEY (producto_id) REFERENCES producto(id)
);
--------------------DATOS------------------
-- Usuarios base
INSERT INTO usuario (usuario, contraseña, rol) VALUES
('cliente1', '1234', 'CLIENTE'),
('vendedor1', '1234', 'VENDEDOR'),
('almacen1',  '1234', 'ALMACEN'),
('cajero1',   '1234', 'CAJERO');

-- Productos de prueba
INSERT INTO producto (nombre, descripcion, precio, stock) VALUES
('Laptop HP', 'Laptop gama media', 4200.00, 10),
('Mouse Logitech', 'Mouse inalámbrico', 150.00, 30),
('Monitor Samsung', '24 pulgadas LED', 1100.00, 15);
