-- Inicialización exclusiva de la base nueva actividad02. No contiene DROP.
SET NAMES utf8mb4;
CREATE TABLE categorias (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 nombre VARCHAR(60) NOT NULL,
 descripcion VARCHAR(150) NOT NULL,
 CONSTRAINT uq_categoria_nombre UNIQUE(nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
CREATE TABLE socios (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 nombre VARCHAR(80) NOT NULL,
 comunidad VARCHAR(60) NOT NULL,
 telefono VARCHAR(20) NULL,
 CONSTRAINT chk_socio_nombre CHECK(CHAR_LENGTH(TRIM(nombre)) BETWEEN 3 AND 80),
 CONSTRAINT chk_socio_comunidad CHECK(CHAR_LENGTH(TRIM(comunidad)) BETWEEN 2 AND 60),
 INDEX idx_socio_nombre(nombre,id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
CREATE TABLE productos (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 codigo VARCHAR(8) NOT NULL,
 nombre VARCHAR(80) NOT NULL,
 categoria_id INT UNSIGNED NOT NULL,
 precio DECIMAL(10,2) NOT NULL,
 stock INT NOT NULL DEFAULT 0,
 socio_id INT UNSIGNED NOT NULL,
 CONSTRAINT uq_producto_codigo UNIQUE(codigo),
 CONSTRAINT fk_producto_categoria FOREIGN KEY(categoria_id) REFERENCES categorias(id) ON DELETE RESTRICT,
 CONSTRAINT fk_producto_socio FOREIGN KEY(socio_id) REFERENCES socios(id) ON DELETE RESTRICT,
 CONSTRAINT chk_producto_precio CHECK(precio>0 AND precio<=10000),
 CONSTRAINT chk_producto_stock CHECK(stock BETWEEN 0 AND 100000),
 CONSTRAINT chk_producto_nombre CHECK(CHAR_LENGTH(TRIM(nombre)) BETWEEN 3 AND 80),
 INDEX idx_producto_nombre(nombre,id),
 INDEX idx_producto_categoria_nombre(categoria_id,nombre,id),
 INDEX idx_producto_socio_nombre(socio_id,nombre,id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
