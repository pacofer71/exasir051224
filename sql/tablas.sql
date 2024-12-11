create table cursos(
    id int auto_increment primary key,
    nombre varchar(60) unique not null,
    descripcion varchar(150) not null,
    gratuito enum("SI", "NO"),
    tipo enum("MATEMATICAS", "FISICA", "LENGUA", "HISTORIA")
);

-- Datos
INSERT INTO cursos (nombre, descripcion, gratuito, tipo) VALUES
('Álgebra Básica', 'Introducción a los conceptos básicos del álgebra', 'SI', 'MATEMATICAS'),
('Historia Contemporánea', 'Análisis de eventos clave desde 1900', 'NO', 'HISTORIA'),
('Física Cuántica', 'Bases teóricas de la mecánica cuántica', 'NO', 'FISICA'),
('Redacción Creativa', 'Mejorando tus habilidades de escritura', 'SI', 'LENGUA'),
('Historia del Arte', 'Exploración de las principales corrientes artísticas', 'SI', 'HISTORIA'),
('Trigonometría Avanzada', 'Resolviendo problemas complejos de trigonometría', 'NO', 'MATEMATICAS'),
('Física Clásica', 'Leyes de Newton y movimientos mecánicos', 'SI', 'FISICA'),
('Ortografía y Gramática', 'Domina las normas del idioma español', 'NO', 'LENGUA'),
('Geometría Espacial', 'Estudio de figuras tridimensionales', 'SI', 'MATEMATICAS'),
('Historia Mundial', 'Una mirada a las civilizaciones más importantes', 'NO', 'HISTORIA');