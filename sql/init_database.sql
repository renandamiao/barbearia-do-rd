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