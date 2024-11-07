-----------Tabela agendamentos------------

DROP TABLE IF EXISTS agendamentos;
CREATE TABLE agendamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(15) NOT NULL,
    data DATE NOT NULL,
    horario TIME NOT NULL,
    status ENUM('concluído', 'cancelado') DEFAULT 'concluído',
    data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

----------Tabela historico_agendamentos----------

DROP TABLE IF EXISTS historico_agendamentos;
CREATE TABLE historico_agendamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(15) NOT NULL,
    data DATE NOT NULL,
    horario TIME NOT NULL,
    status ENUM('concluído', 'cancelado') DEFAULT 'concluído',
    data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


----Adicionar uma restrição de unicidade à tabela agendamentos, garantindo que não haja registros duplicados para combinações específicas de telefone, data e horario-----

ALTER TABLE agendamentos
ADD UNIQUE KEY unique_agendamento (telefone, data, horario);



-----Tabela horarios_disponiveis-------------

DROP TABLE IF EXISTS horarios_disponiveis;
CREATE TABLE horarios_disponiveis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    horario TIME NOT NULL,
    dentro_expediente BOOLEAN NOT NULL
);


-------horários de segunda a sexta-feira, das 08:00 às 11:00 e das 14:00 às 19:00--------------

INSERT INTO horarios_disponiveis (horario, dentro_expediente) VALUES
('08:00:00', 1), ('08:30:00', 1), ('09:00:00', 1), ('09:30:00', 1),
('10:00:00', 1), ('10:30:00', 1), ('11:00:00', 1),
('14:00:00', 1), ('14:30:00', 1), ('15:00:00', 1), ('15:30:00', 1),
('16:00:00', 1), ('16:30:00', 1), ('17:00:00', 1), ('17:30:00', 1),
('18:00:00', 1), ('18:30:00', 1), ('19:00:00', 1);
