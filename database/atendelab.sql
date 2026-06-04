CREATE TABLE usuarios (
 id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
 nome VARCHAR(100) NOT NULL,
 email VARCHAR(100) NOT NULL UNIQUE,
 senha VARCHAR(255) NOT NULL,
 perfil ENUM('admin', 'aluno', 'atendente') DEFAULT 'atendente';
 status ENUM('ativo', 'inativo') DEFAULT 'ativo',
 criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE pessoas (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    nome VARCHAR(100) NOT NULL,
    documento VARCHAR(20) UNIQUE NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    curso VARCHAR(100) NOT NULL,
    periodo VARCHAR(100) NOT NULL,
    status VARCHAR(100) NOT NULL
);    

CREATE TABLE tipo_atendimento (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    status ENUM ('ATIVO', 'INATIVO') DEFAULT 'ATIVO' 
);
    
CREATE TABLE atendimentos (
id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
pessoa_id INT NOT NULL,
tipo_atendimento INT NOT NULL,
usuario_id INT NOT NULL,
data_atendimento DATE NOT NULL,
hora_atendimento TIME NOT NULL,
descricao TEXT,
observacao TEXT,
status ENUM ('PENDENTE', 'EM ANDAMENTO', 'CONCLUIDO', 'CANCELADO') DEFAULT 'PENDENTE',
criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY(pessoa_id) REFERENCES pessoas(id),
FOREIGN KEY (tipo_atendimento) REFERENCES tipo_atendimento(id),
FOREIGN KEY(usuario_id) REFERENCES usuarios(id)
)