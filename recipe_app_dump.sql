-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 16-Jul-2025 às 18:56
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `recipe_app1`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `categoria`
--

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `categoria`
--

INSERT INTO `categoria` (`id`, `nome`) VALUES
(1, 'Sobremesa'),
(2, 'Salada');

-- --------------------------------------------------------

--
-- Estrutura da tabela `ingrediente`
--

CREATE TABLE `ingrediente` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `ingrediente`
--

INSERT INTO `ingrediente` (`id`, `nome`) VALUES
(1, 'Chocolate'),
(2, 'Cenoura'),
(3, 'Farinha'),
(4, 'Ovos'),
(5, 'Peito de frango'),
(6, 'Alface'),
(7, 'Tomate'),
(8, 'Croutons'),
(9, 'Queijo');

-- --------------------------------------------------------

--
-- Estrutura da tabela `receita`
--

CREATE TABLE `receita` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `modo_preparacao` varchar(200) NOT NULL,
  `duracao` varchar(100) NOT NULL,
  `doses` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `receita`
--

INSERT INTO `receita` (`id`, `nome`, `modo_preparacao`, `duracao`, `doses`) VALUES
(1, 'Bolo de Cenoura com Calda de Chocolate', 'Misture os ingredientes e asse.', '40 minutos', 4),
(2, 'Salada Ceasar de Frango', 'Cozinhe o frango, misture com legumes, tempere e adicione molhos e os croutons.', '10 minutos', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `receita_categoria`
--

CREATE TABLE `receita_categoria` (
  `id` int(11) NOT NULL,
  `id_receita` int(11) DEFAULT NULL,
  `id_categoria` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `receita_categoria`
--

INSERT INTO `receita_categoria` (`id`, `id_receita`, `id_categoria`) VALUES
(1, 1, 1),
(2, 2, 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `receita_ingrediente`
--

CREATE TABLE `receita_ingrediente` (
  `id` int(11) NOT NULL,
  `id_receita` int(11) DEFAULT NULL,
  `id_ingrediente` int(11) DEFAULT NULL,
  `quantidade` varchar(100) NOT NULL,
  `unidade_de_medida` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `receita_ingrediente`
--

INSERT INTO `receita_ingrediente` (`id`, `id_receita`, `id_ingrediente`, `quantidade`, `unidade_de_medida`) VALUES
(1, 1, 2, '3', 'unidades'),
(2, 1, 3, '2', 'xícaras'),
(3, 1, 4, '4', 'unidades'),
(4, 1, 1, '200', 'gramas'),
(5, 2, 5, '1', 'unidade'),
(6, 2, 6, '1', 'unidade'),
(7, 2, 7, '2', 'unidades'),
(8, 2, 8, '1', 'xícara'),
(9, 2, 9, '100', 'gramas');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `ingrediente`
--
ALTER TABLE `ingrediente`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `receita`
--
ALTER TABLE `receita`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `receita_categoria`
--
ALTER TABLE `receita_categoria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_receita` (`id_receita`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Índices para tabela `receita_ingrediente`
--
ALTER TABLE `receita_ingrediente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ingrediente` (`id_ingrediente`),
  ADD KEY `id_receita` (`id_receita`);

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `receita_categoria`
--
ALTER TABLE `receita_categoria`
  ADD CONSTRAINT `receita_categoria_ibfk_1` FOREIGN KEY (`id_receita`) REFERENCES `receita` (`id`),
  ADD CONSTRAINT `receita_categoria_ibfk_2` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id`);

--
-- Limitadores para a tabela `receita_ingrediente`
--
ALTER TABLE `receita_ingrediente`
  ADD CONSTRAINT `receita_ingrediente_ibfk_1` FOREIGN KEY (`id_ingrediente`) REFERENCES `ingrediente` (`id`),
  ADD CONSTRAINT `receita_ingrediente_ibfk_2` FOREIGN KEY (`id_receita`) REFERENCES `receita` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
