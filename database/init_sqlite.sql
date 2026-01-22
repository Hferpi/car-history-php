PRAGMA foreign_keys = ON;

-- =====================
-- MARCAS
-- =====================
CREATE TABLE marca (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL UNIQUE
);

-- =====================
-- MODELOS
-- =====================
CREATE TABLE modelo (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    marca_id INTEGER NOT NULL,
    nombre TEXT NOT NULL,
    UNIQUE (marca_id, nombre),
    FOREIGN KEY (marca_id) REFERENCES marca(id) ON DELETE CASCADE
);

-- =====================
-- USUARIOS
-- =====================
CREATE TABLE usuario (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- =====================
-- VEHICULOS
-- =====================
CREATE TABLE vehiculo (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    usuario_id INTEGER NOT NULL,
    matricula TEXT NOT NULL UNIQUE,
    modelo_id text NOT NULL,
    marca text not null,
    kilometros INTEGER,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE,
    FOREIGN KEY (modelo_id) REFERENCES modelo(id)
);

-- =====================
-- TALLERES
-- =====================
CREATE TABLE taller (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL,
    ubicacion TEXT,
    tipo TEXT
);

-- =====================
-- RECIBOS
-- =====================
CREATE TABLE recibo (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    vehiculo_id INTEGER NOT NULL,
    taller_id INTEGER NOT NULL,
    fecha DATETIME NOT NULL,
    precio REAL,
    tipo_servicio TEXT,
    observaciones TEXT,
    FOREIGN KEY (vehiculo_id) REFERENCES vehiculo(id) ON DELETE CASCADE,
    FOREIGN KEY (taller_id) REFERENCES taller(id)
);
