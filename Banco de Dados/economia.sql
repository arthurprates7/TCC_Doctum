-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 12/09/2021 às 17:53
-- Versão do servidor: 10.4.17-MariaDB
-- Versão do PHP: 7.4.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `economia`
--
CREATE DATABASE IF NOT EXISTS `economia` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `economia`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `infos`
--

CREATE TABLE `infos` (
  `id` int(11) NOT NULL,
  `user` bigint(20) UNSIGNED NOT NULL,
  `rua` tinyint(1) NOT NULL COMMENT 'true ou false',
  `caixa` decimal(9,2) NOT NULL COMMENT 'em porcentagem',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Despejando dados para a tabela `infos`
--

INSERT INTO `infos` (`id`, `user`, `rua`, `caixa`, `created_at`) VALUES
(2, 1, 0, '92.20', '2021-04-03 21:30:37'),
(3, 1, 1, '50.00', '2021-04-03 21:47:15'),
(4, 1, 0, '60.00', '2021-04-03 21:48:51'),
(5, 1, 1, '60.00', '2021-04-03 21:53:13'),
(6, 1, 1, '60.00', '2021-04-04 13:31:19'),
(7, 1, 1, '60.00', '2021-04-04 13:31:24'),
(8, 1, 1, '60.00', '2021-04-04 13:31:34'),
(9, 1, 1, '60.00', '2021-04-04 13:31:41'),
(10, 1, 1, '60.00', '2021-04-04 13:31:55'),
(11, 1, 1, '60.00', '2021-04-04 13:32:18'),
(12, 1, 1, '60.00', '2021-04-04 13:33:40'),
(13, 1, 1, '60.00', '2021-04-04 13:34:00'),
(14, 1, 1, '60.00', '2021-04-04 13:37:50'),
(15, 1, 1, '60.00', '2021-04-04 13:38:10'),
(16, 1, 1, '60.00', '2021-04-04 13:38:33'),
(17, 1, 1, '60.00', '2021-04-04 13:38:48'),
(18, 1, 1, '60.00', '2021-04-04 13:39:08'),
(19, 1, 1, '60.00', '2021-04-04 13:39:22'),
(20, 1, 0, '60.00', '2021-04-04 13:39:38'),
(21, 1, 1, '60.00', '2021-04-04 14:01:35'),
(22, 1, 1, '60.00', '2021-04-04 14:01:50');

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expo_token` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `expo_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', 'administrador@aptechs.com.br', '$2y$10$EPvSkiyFPKmOop6WsBfoc.2n8/bGCrUrc/d66owwI1ARtnhxOiKwe', 'dasjhdlasdlkjdaslkd', '2020-06-14 00:43:18', '2020-06-14 00:43:18'),
(2, 'Arthur', 'arthur.prates7@gmail.com', '$2y$10$b3tobMgVpsaBtJt3tRlWEOCmmjtx0hUIuobLPuYyHcAYaO8cxrKAm', 'dasjhdlasdlkjdaslkd', NULL, NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `infos`
--
ALTER TABLE `infos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_info` (`user`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `infos`
--
ALTER TABLE `infos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `infos`
--
ALTER TABLE `infos`
  ADD CONSTRAINT `fk_user_info` FOREIGN KEY (`user`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
