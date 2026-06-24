-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 18/06/2026 às 02:57
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `atendelab`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `atendimentos`
--

CREATE TABLE `atendimentos` (
  `id` int(11) NOT NULL,
  `pessoa_id` int(11) NOT NULL,
  `tipo_atendimento` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `data_atendimento` date NOT NULL,
  `hora_atendimento` time NOT NULL,
  `descricao` text DEFAULT NULL,
  `observacao` text DEFAULT NULL,
  `status` enum('PENDENTE','EM ANDAMENTO','CONCLUIDO','CANCELADO') DEFAULT 'PENDENTE',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `atendimentos`
--

INSERT INTO `atendimentos` (`id`, `pessoa_id`, `tipo_atendimento`, `usuario_id`, `data_atendimento`, `hora_atendimento`, `descricao`, `observacao`, `status`, `criado_em`) VALUES
(2, 1, 2, 7, '2026-06-10', '19:30:05', '', '', 'EM ANDAMENTO', '2026-06-11 00:45:48'),
(3, 2, 1, 8, '2026-06-10', '21:32:05', '', '', 'PENDENTE', '2026-06-11 00:51:43');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pessoas`
--

CREATE TABLE `pessoas` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `documento` varchar(20) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `curso` varchar(100) NOT NULL,
  `periodo` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pessoas`
--

INSERT INTO `pessoas` (`id`, `nome`, `documento`, `telefone`, `curso`, `periodo`, `status`) VALUES
(1, 'Emilly Lemke', '12345678901', '47996013847', 'Engenharia de Software', '5º Semestre', 'ativo'),
(2, 'Mateus Tavares', '12345678902', '47999504398', 'Engenharia de Software', '5º Semestre', 'ativo'),
(4, 'Giovana Gomes', '12345678904', '47999505678', 'Sistemas de Informação', '5º Semestre', 'ativo'),
(5, 'Sabrina Pietruza', '12345678905', '47997179090', 'Psicologia', '5º Semestre', 'ativo');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipo_atendimento`
--

CREATE TABLE `tipo_atendimento` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `status` enum('ATIVO','INATIVO') DEFAULT 'ATIVO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tipo_atendimento`
--

INSERT INTO `tipo_atendimento` (`id`, `nome`, `descricao`, `status`) VALUES
(1, 'Suporte Técnico', 'Atendimento especializado para problemas de TI', 'ATIVO'),
(2, 'Atendimento RH', 'Atendimento especializado para problemas de RH relacionados a Férias, Folha de pagamento e Planos de saúde.', 'ATIVO'),
(3, 'Infraestrutura', 'Atendimento para problemas de hardware e cadeiras.', 'ATIVO');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `perfil` enum('admin','aluno','atendente') DEFAULT 'atendente',
  `status` enum('ativo','inativo') DEFAULT 'ativo',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `perfil`, `status`, `criado_em`) VALUES
(1, 'Monica Tavares', 'monicatavares@atendelab.com', '$2y$10$J9P2kU2BAMZ3TZcuxTsW4e1D/lka8EocYHzvyoOZmCNcWDQz3RuVC', 'admin', 'ativo', '2026-05-29 00:36:55'),
(2, 'João Pedro Lopes', 'joao.pedro@atendelab.com', '$2y$10$HqKfogCR5eY313.dGqmjGOlT7LqgjYns5YvX61j2rhSmtLUNAL1v.', 'atendente', 'ativo', '2026-06-04 00:37:41'),
(3, 'Maria Eduarda Silva', 'mariaesilva@gmail.com', '$2y$10$h4yJ7jNnrSFkO2W1CahCT.4wTBR09gu7OXH2m/9sr0qpSTvJo0De2', 'aluno', 'ativo', '2026-06-04 00:38:20'),
(4, 'Eduarda Costa', 'eduardacosta@email.com', '$2y$10$jBGiwOM0EV/nHG89ikhjEuM4u6Lx0tErEqUb36lbRIS41D70QPC6m', 'aluno', 'ativo', '2026-06-04 00:40:27'),
(6, 'Henrique Alcantara', 'halcantara@gmail.com', '$2y$10$2bay4sWeV4QMWHT0/ilUI.zDiAeeGmN2x5MqNjGiW/WOC4AdGNgOa', 'aluno', 'ativo', '2026-06-04 00:56:24'),
(7, 'Emilly Lemke', 'emillylemke@gmail.com', '$2y$10$4jiFc229.JFP9injbRr5Kuh6e56l0.NLz/0kXJ2XjGsa2IZd/zFF2', 'aluno', 'ativo', '2026-06-10 23:51:39'),
(8, 'Mateus T Santos', 'm76@jec.com', '$2y$10$PXfNxFbIoRYa5pcjJdl1HuA6P7CcScngjZQhhwvZhyY9spdXZDDc6', 'aluno', 'ativo', '2026-06-11 00:51:13'),
(10, 'Aline Pereira', 'alinp@gmail.com', '$2y$10$GvRyribLgMg9kTFazoe.sOhu41c/sTPMMwH55Q5WESl5gVg4B4TXe', 'aluno', 'ativo', '2026-06-17 23:52:56'),
(11, 'Pedro Hecht', 'pedro@atendelab.com', '$2y$10$vfj2f6w86W/3l0/ermiHPe8gX8tCsxzvNde9ptUmluWfRDy737NOy', 'admin', 'ativo', '2026-06-17 23:59:48'),
(12, 'Larissa Lemes', 'lari@gmail.com', '$2y$10$cQUfNp7pCwJA7P6F1uy1VePhe5OAeFzk0SajCTkTxh0pHKqZTvQ8u', 'aluno', 'ativo', '2026-06-18 00:00:15'),
(13, 'Admin', 'admin@atendelab.com', '$2y$10$oUM0n2q1SKysrMtz378w/OgCUGUcRS..f33Dx8HRHN2rfL5jDm2tW', 'admin', 'ativo', '2026-06-18 00:16:10');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `atendimentos`
--
ALTER TABLE `atendimentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pessoa_id` (`pessoa_id`),
  ADD KEY `tipo_atendimento` (`tipo_atendimento`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `pessoas`
--
ALTER TABLE `pessoas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `documento` (`documento`);

--
-- Índices de tabela `tipo_atendimento`
--
ALTER TABLE `tipo_atendimento`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `atendimentos`
--
ALTER TABLE `atendimentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `pessoas`
--
ALTER TABLE `pessoas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `tipo_atendimento`
--
ALTER TABLE `tipo_atendimento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `atendimentos`
--
ALTER TABLE `atendimentos`
  ADD CONSTRAINT `atendimentos_ibfk_1` FOREIGN KEY (`pessoa_id`) REFERENCES `pessoas` (`id`),
  ADD CONSTRAINT `atendimentos_ibfk_2` FOREIGN KEY (`tipo_atendimento`) REFERENCES `tipo_atendimento` (`id`),
  ADD CONSTRAINT `atendimentos_ibfk_3` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
