
CREATE DATABASE IF NOT EXISTS fitlife CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE fitlife;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS pagamentos, treinos, agendamentos, aulas, alunos, professores, usuarios;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    perfil ENUM('admin', 'professor', 'aluno') NOT NULL DEFAULT 'aluno',
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE alunos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL UNIQUE,
    cpf VARCHAR(20) UNIQUE,
    telefone VARCHAR(30),
    data_nascimento DATE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE professores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL UNIQUE,
    especialidade VARCHAR(120),
    telefone VARCHAR(30),
    bio TEXT,
    imagem VARCHAR(255),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE aulas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    professor_id INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    dia_semana TINYINT NOT NULL,
    horario TIME NOT NULL,
    duracao_min INT NOT NULL DEFAULT 60,
    vagas INT NOT NULL DEFAULT 10,
    FOREIGN KEY (professor_id) REFERENCES professores(id) ON DELETE RESTRICT
);

CREATE TABLE agendamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aluno_id INT NOT NULL,
    aula_id INT NOT NULL,
    data DATE NOT NULL,
    status ENUM('confirmado', 'cancelado') NOT NULL DEFAULT 'confirmado',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE CASCADE,
    FOREIGN KEY (aula_id) REFERENCES aulas(id) ON DELETE CASCADE,
    UNIQUE KEY aluno_aula_data (aluno_id, aula_id, data)
);

CREATE TABLE treinos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aluno_id INT NOT NULL,
    professor_id INT,
    titulo VARCHAR(150) NOT NULL,
    descricao TEXT NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE CASCADE,
    FOREIGN KEY (professor_id) REFERENCES professores(id) ON DELETE SET NULL
);

CREATE TABLE pagamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aluno_id INT NOT NULL,
    plano VARCHAR(80) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    metodo VARCHAR(30) NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'demonstracao',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_pagamentos_aluno FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE CASCADE
);

INSERT INTO usuarios (nome, email, senha, perfil) VALUES
('Administrador Fitlife', 'admin@fitlife.com', '$2y$12$pyMIkpTYJDvOfpzLsNX0sOc9WEdJrvDWKVXp0O6/P0qp/qy8Gx.om', 'admin'),
('Marcio Klein', 'professor@fitlife.com', '$2y$12$pyMIkpTYJDvOfpzLsNX0sOc9WEdJrvDWKVXp0O6/P0qp/qy8Gx.om', 'professor'),
('Daniel Ferreira', 'carlos@fitlife.com', '$2y$12$pyMIkpTYJDvOfpzLsNX0sOc9WEdJrvDWKVXp0O6/P0qp/qy8Gx.om', 'professor'),
('Mariana Alves', 'aluno@fitlife.com', '$2y$12$pyMIkpTYJDvOfpzLsNX0sOc9WEdJrvDWKVXp0O6/P0qp/qy8Gx.om', 'aluno'),
('Marcio Romero', 'marcio.romero@fitlife.com', '$2y$12$pyMIkpTYJDvOfpzLsNX0sOc9WEdJrvDWKVXp0O6/P0qp/qy8Gx.om', 'professor');

INSERT INTO professores (usuario_id, especialidade, telefone, bio, imagem)
SELECT id,
    CASE email
        WHEN 'professor@fitlife.com' THEN 'Musculação e hipertrofia'
        WHEN 'carlos@fitlife.com' THEN 'Musculação e nutrição esportiva'
        WHEN 'marcio.romero@fitlife.com' THEN 'Powerlifting e condicionamento físico'
    END,
    CASE email
        WHEN 'professor@fitlife.com' THEN '(11) 99876-5432'
        WHEN 'carlos@fitlife.com' THEN '(11) 98765-4321'
        WHEN 'marcio.romero@fitlife.com' THEN '(11) 98888-7766'
    END,
    CASE email
        WHEN 'professor@fitlife.com' THEN 'Personal trainer com foco em musculação, hipertrofia, técnica e evolução consistente.'
        WHEN 'carlos@fitlife.com' THEN 'Profissional focado em desempenho, saúde e planejamento individualizado para cada etapa da evolução.'
        WHEN 'marcio.romero@fitlife.com' THEN 'Apaixonado por powerlifting e condicionamento físico. Trabalha força, técnica, disciplina e evolução progressiva, respeitando os objetivos e o ritmo de cada aluno.'
    END,
    CASE email
        WHEN 'professor@fitlife.com' THEN 'assets/professores/marcio-klein.jpg'
        WHEN 'carlos@fitlife.com' THEN 'assets/professores/daniel-ferreira.jpg'
        WHEN 'marcio.romero@fitlife.com' THEN 'assets/professores/marcio-romero.jpg'
    END
FROM usuarios
WHERE email IN ('professor@fitlife.com', 'carlos@fitlife.com', 'marcio.romero@fitlife.com')
ORDER BY FIELD(email, 'professor@fitlife.com', 'carlos@fitlife.com', 'marcio.romero@fitlife.com');

INSERT INTO alunos (usuario_id, cpf, telefone, data_nascimento)
SELECT id, '111.222.333-44', '(11) 99999-1111', '2002-05-18'
FROM usuarios
WHERE email = 'aluno@fitlife.com';

INSERT INTO aulas (professor_id, nome, descricao, dia_semana, horario, duracao_min, vagas) VALUES
(1, 'Funcional', 'Circuito de força, mobilidade e condicionamento.', 2, '18:00:00', 60, 12),
(2, 'Musculação', 'Treino orientado com foco em hipertrofia.', 3, '19:00:00', 60, 10),
(1, 'HIIT', 'Intervalos de alta intensidade para condicionamento.', 5, '18:30:00', 45, 8),
(2, 'Força', 'Sessão focada em força e técnica.', 6, '10:00:00', 60, 10);

INSERT INTO agendamentos (aluno_id, aula_id, data, status)
VALUES (1, 1, DATE_ADD(CURDATE(), INTERVAL 2 DAY), 'confirmado');

INSERT INTO treinos (aluno_id, professor_id, titulo, descricao)
VALUES (
    1,
    1,
    'Treino funcional A',
    'Aquecimento 10 min.\nAgachamento 3x12.\nFlexão 3x10.\nPrancha 3x30s.\nAlongamento 5 min.'
);
