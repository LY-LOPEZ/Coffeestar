CREATE DATABASE IF NOT EXISTS coffestar CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE coffestar;

DROP TABLE IF EXISTS detalle_pedidos;
DROP TABLE IF EXISTS facturas;
DROP TABLE IF EXISTS pedidos;
DROP TABLE IF EXISTS productos;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS mesas;
DROP TABLE IF EXISTS inventario;
DROP TABLE IF EXISTS clientes;
DROP TABLE IF EXISTS caja;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin','cajero') NOT NULL
);

INSERT INTO usuarios (nombre, usuario, password, rol) VALUES
('Administrador', 'admin', 'admin123', 'admin'),
('Cajero Principal', 'cajero', 'caja123', 'cajero');

CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    tiempo_preparacion INT NOT NULL DEFAULT 5,
    imagen TEXT
);

INSERT INTO productos (nombre, precio, tiempo_preparacion, imagen) VALUES
('Espresso', 10.00, 2, 'https://images.unsplash.com/photo-1510707577719-ae7c14805e3a?q=80&w=800&auto=format&fit=crop'),
('Americano', 12.00, 3, 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?q=80&w=800&auto=format&fit=crop'),
('Latte', 18.00, 5, 'https://images.unsplash.com/photo-1570968915860-54d5c301fa9f?q=80&w=800&auto=format&fit=crop'),
('Capuccino', 18.00, 5, 'https://images.unsplash.com/photo-1534778101976-62847782c213?q=80&w=800&auto=format&fit=crop'),
('Mocaccino', 20.00, 6, 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?q=80&w=800&auto=format&fit=crop'),
('Frappuccino', 24.00, 8, 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?q=80&w=800&auto=format&fit=crop'),
('Brownie', 12.00, 2, 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?q=80&w=800&auto=format&fit=crop'),
('Cheesecake', 16.00, 2, 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?q=80&w=800&auto=format&fit=crop'),
('Sandwich', 22.00, 10, 'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?q=80&w=800&auto=format&fit=crop');

CREATE TABLE mesas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero INT NOT NULL UNIQUE,
    estado ENUM('Libre','Ocupada','Preparando','Entregado') DEFAULT 'Libre'
);

INSERT INTO mesas (numero, estado) VALUES
(1,'Libre'),(2,'Libre'),(3,'Libre'),(4,'Libre'),(5,'Libre'),
(6,'Libre'),(7,'Libre'),(8,'Libre'),(9,'Libre'),(10,'Libre');

CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(120) NOT NULL,
    telefono VARCHAR(50),
    puntos INT DEFAULT 0,
    total_gastado DECIMAL(10,2) DEFAULT 0
);

CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_nombre VARCHAR(120),
    telefono VARCHAR(50),
    tipo ENUM('mesa','llevar','delivery') NOT NULL,
    mesa_id INT NULL,
    metodo_pago ENUM('QR','Efectivo','Tarjeta/Débito') NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    estado ENUM('Preparando','En camino','Entregado','Finalizado','Cancelado') DEFAULT 'Preparando',
    mesero VARCHAR(100),
    tiempo_estimado INT DEFAULT 5,
    direccion TEXT,
    referencia TEXT,
    nit_ci VARCHAR(50),
    razon_social VARCHAR(150),
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_entrega DATETIME NULL,
    mesa_liberada DATETIME NULL,
    FOREIGN KEY (mesa_id) REFERENCES mesas(id)
);

CREATE TABLE detalle_pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);

CREATE TABLE facturas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    codigo VARCHAR(50) NOT NULL UNIQUE,
    nit_ci VARCHAR(50),
    razon_social VARCHAR(150),
    total DECIMAL(10,2) NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
);

CREATE TABLE inventario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    insumo VARCHAR(100) NOT NULL,
    stock DECIMAL(10,2) NOT NULL,
    unidad VARCHAR(30) NOT NULL,
    minimo DECIMAL(10,2) NOT NULL
);

INSERT INTO inventario (insumo, stock, unidad, minimo) VALUES
('Café en grano', 8, 'kg', 3),
('Leche', 12, 'litros', 4),
('Chocolate', 5, 'kg', 2),
('Azúcar', 7, 'kg', 2),
('Vasos', 80, 'unidades', 25),
('Tapas', 80, 'unidades', 25),
('Servilletas', 150, 'unidades', 40),
('Brownies', 16, 'unidades', 6),
('Cheesecake', 12, 'porciones', 5);

CREATE TABLE caja (
    id INT AUTO_INCREMENT PRIMARY KEY,
    monto_inicial DECIMAL(10,2) NOT NULL DEFAULT 0,
    fecha_apertura DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_cierre DATETIME NULL,
    estado ENUM('Abierta','Cerrada') DEFAULT 'Abierta'
);
