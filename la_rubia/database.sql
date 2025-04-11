CREATE DATABASE la_rubia;
USE la_rubia;

CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(10) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL
);

CREATE TABLE articulos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    precio DECIMAL(10, 2) NOT NULL
);

CREATE TABLE facturas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_recibo VARCHAR(10) UNIQUE NOT NULL,
    fecha DATE NOT NULL,
    codigo_cliente VARCHAR(10) NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    comentario TEXT,
    FOREIGN KEY (codigo_cliente) REFERENCES clientes(codigo)
);

CREATE TABLE detalle_factura (
    id INT AUTO_INCREMENT PRIMARY KEY,
    factura_id INT NOT NULL,
    articulo_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (factura_id) REFERENCES facturas(id),
    FOREIGN KEY (articulo_id) REFERENCES articulos(id)
);

INSERT INTO clientes (codigo, nombre) VALUES ('123', 'Juan Pérez'), ('456', 'María Gómez');
INSERT INTO articulos (nombre, precio) VALUES ('Refresco', 50.00), ('Galleta', 20.00), ('Agua', 30.00);