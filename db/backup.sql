-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 21-Abr-2025 às 22:03
-- Versão do servidor: 9.1.0
-- versão do PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Banco de dados: `4x1`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `administrador`
--

DROP TABLE IF EXISTS `administrador`;
CREATE TABLE IF NOT EXISTS `administrador` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pass` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `img` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'https://www.google.com/url?sa=i&url=https%3A%2F%2Fbr.freepik.com%2Ffotos-vetores-gratis%2Fuser-icon&psig=AOvVaw3d95dQ6o0U0qDmh29NZRCh&ust=1738437993975000&source=images&cd=vfe&opi=89978449&ved=0CBEQjRxqFwoTCNDnnqnYoIsDFQAAAAAdAAAAABAJ',
  `adminMor` tinyint NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `administrador`
--

INSERT INTO `administrador` (`id`, `nome`, `email`, `pass`, `img`, `adminMor`, `created`, `updated`, `active`) VALUES
(1, 'Geral 4x1', 'geral@4x1.pt', '$2y$10$0yaLAc4f1IhwPgPOVYAoQeKbbvFbAUOJPrY6cDhI1CubjU5mPb3DK', 'https://admin.4x1.pt/images/IconUser.png', 1, '2025-04-17 15:51:15', '2025-04-17 15:51:15', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `administrador_logs`
--

DROP TABLE IF EXISTS `administrador_logs`;
CREATE TABLE IF NOT EXISTS `administrador_logs` (
  `idAdministrador` int NOT NULL,
  `dataLog` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `logFile` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  KEY `idAdministrator` (`idAdministrador`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `administrador_logs`
--

INSERT INTO `administrador_logs` (`idAdministrador`, `dataLog`, `logFile`) VALUES
(1, '2025-04-20 20:59:59', 'O administrador [1]Geral 4x1 atualizou o aluno [15]MATILDE GONÇALVES ARAÚJO.'),
(1, '2025-04-20 21:11:33', 'O administrador [1]Geral 4x1 registrou a presença do aluno [22]DINIS MANUEL SOUSA PACHECO.'),
(8, '2025-04-21 16:19:48', 'O administrador [8]Natália Luciano atualizou o seu perfil'),
(8, '2025-04-21 16:26:45', 'O administrador [8]Natália Luciano atualizou o seu perfil'),
(8, '2025-04-21 16:38:16', 'O administrador [8]Natália Luciano atualizou o seu perfil'),
(8, '2025-04-21 16:38:58', 'O administrador [8]Natália Luciano atualizou o seu perfil'),
(8, '2025-04-21 16:42:42', 'O administrador [8]Natália Luciano atualizou o seu perfil'),
(8, '2025-04-21 16:43:44', 'O administrador [8]Natália Luciano atualizou o seu perfil'),
(1, '2025-04-21 20:40:03', 'O administrador [1]Geral 4x1 criou a mensalidade [330].'),
(1, '2025-04-21 20:40:12', 'O administrador [1]Geral 4x1 eliminou a mensalidade [330].'),
(1, '2025-04-21 20:40:53', 'O administrador [1]Geral 4x1 criou a mensalidade [331].'),
(1, '2025-04-21 20:41:06', 'O administrador [1]Geral 4x1 eliminou a mensalidade [331].');

-- --------------------------------------------------------

--
-- Estrutura da tabela `administrador_modulos`
--

DROP TABLE IF EXISTS `administrador_modulos`;
CREATE TABLE IF NOT EXISTS `administrador_modulos` (
  `idProfessor` int NOT NULL,
  `idModule` int NOT NULL,
  `pView` tinyint(1) NOT NULL DEFAULT '0',
  `pInsert` tinyint(1) NOT NULL DEFAULT '0',
  `pUpdate` tinyint(1) NOT NULL DEFAULT '0',
  `pDelete` tinyint(1) NOT NULL DEFAULT '0',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idProfessor`,`idModule`),
  KEY `idModule` (`idModule`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `administrador_modulos`
--

INSERT INTO `administrador_modulos` (`idProfessor`, `idModule`, `pView`, `pInsert`, `pUpdate`, `pDelete`, `updated`, `created`) VALUES
(1, 1, 1, 1, 1, 1, '2025-01-22 00:29:58', '2025-04-17 16:21:50'),
(1, 3, 1, 1, 1, 1, '2025-01-22 00:29:58', '2025-04-17 16:21:51'),
(1, 4, 1, 1, 1, 1, '2025-01-22 00:29:58', '2025-04-17 16:21:52'),
(1, 5, 1, 1, 1, 1, '2025-01-22 00:29:58', '2025-04-17 16:21:53'),
(1, 6, 1, 1, 1, 1, '2025-01-22 00:29:58', '2025-04-17 16:21:53'),
(1, 8, 1, 1, 1, 1, '2025-01-22 00:29:58', '2025-04-17 16:21:55'),
(1, 10, 1, 0, 0, 0, '2025-01-22 18:00:57', '2025-04-17 16:21:56');

-- --------------------------------------------------------

--
-- Estrutura da tabela `alunos`
--

DROP TABLE IF EXISTS `alunos`;
CREATE TABLE IF NOT EXISTS `alunos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pack` int NOT NULL,
  `balancoGrupo` decimal(10,2) NOT NULL,
  `balancoIndividual` decimal(10,2) NOT NULL,
  `nome` text NOT NULL,
  `morada` text NOT NULL,
  `localidade` text NOT NULL,
  `codigoPostal` varchar(8) NOT NULL,
  `nif` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `dataNascimento` date NOT NULL,
  `email` text NOT NULL,
  `contacto` varchar(20) NOT NULL,
  `escola` text NOT NULL,
  `ano` int NOT NULL,
  `curso` text NOT NULL,
  `turma` varchar(10) NOT NULL,
  `horasGrupo` int NOT NULL,
  `horasIndividual` int NOT NULL,
  `transporte` tinyint NOT NULL DEFAULT '0',
  `idMensalidadeGrupo` int DEFAULT NULL,
  `idMensalidadeIndividual` int DEFAULT NULL,
  `nomeMae` text,
  `tlmMae` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nomePai` text,
  `tlmPai` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `modalidade` text NOT NULL,
  `ativo` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=153 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `alunos`
--

INSERT INTO `alunos` (`id`, `pack`, `balancoGrupo`, `balancoIndividual`, `nome`, `morada`, `localidade`, `codigoPostal`, `nif`, `dataNascimento`, `email`, `contacto`, `escola`, `ano`, `curso`, `turma`, `horasGrupo`, `horasIndividual`, `transporte`, `idMensalidadeGrupo`, `idMensalidadeIndividual`, `nomeMae`, `tlmMae`, `nomePai`, `tlmPai`, `modalidade`, `ativo`) VALUES
(1, 0, 0.00, 0.00, 'LEONARDO LOPES GOMES', 'Rua de Valinhas, 272', 'Regilde', '4815-621', '0', '2006-07-07', '', '956575995', '', 12, '', '', 8, 0, 0, 321, NULL, 'Vera Marciana Teixeira Lopes', '938459221', '', '0', '', 1),
(4, 0, 0.00, 0.00, 'ANTÓNIO MONTEIRO COSTA', 'Calçada Jaime Gomes Guimarães, n111', 'Vila Nova do Campo', '4795-516', '0', '2015-04-11', '', '0', '', 4, '', '', 16, 0, 0, 137, NULL, 'Daniela Monteiro', '918177233', '', '0', '', 1),
(5, 0, 0.00, 0.00, 'CARLA ISABEL SILVA RIBEIRO', 'Rua José Moreira Araújo', 'Vila das Aves', '4795-081', '0', '2016-12-05', '', '0', 'Bom Nome', 3, '', '', 16, 0, 0, 97, NULL, 'Carla Cristina Abreu Silva', '912308573', '', '0', '1 x por semana', 1),
(6, 0, 0.00, 0.00, 'DIOGO MARTINS GONÇALVES', 'Rua Sra do Rosário, n24', 'São Tomé de Negrelos', '4795-701', '0', '2014-12-01', '', '0', '', 4, '', '', 16, 4, 0, 137, NULL, 'Rui Miguel Santos Gonçalves', '914089305', '', '0', '', 1),
(7, 0, 0.00, 0.00, 'FRANCISCO GUIMARÃES MONTEIRO', 'Rua do Mourigo, n22 R/ch', 'Vila Nova do Campo', '4795-516', '0', '2015-05-29', '', '0', '', 4, '', '', 16, 0, 0, 137, NULL, 'Ariana Monteiro', '919124722', '', '0', '', 1),
(8, 0, 0.00, 0.00, 'ÍRIS MARIA PIMENTA ABREU MACHADO', 'R. António Aberu Machado, n499', 'Vila das Aves', '4795-034', '0', '2016-01-09', '', '0', 'Escola do Bom Nome', 3, '', '', 16, 0, 0, 97, NULL, 'Cassilda Isabel Pimenta Abreu', '916504199', 'Rui Jorge Ribeiro Machado', '917683550', '', 1),
(9, 0, 0.00, 0.00, 'ÍRIS SANTOS FERREIRA', 'Rua St. André, n354', 'Vila das Aves', '4795-113', '288027680', '2016-01-08', '', '0', 'Bom Nome', 3, '', 'E', 16, 0, 0, 97, NULL, 'Laura da Conceição Canellhas Santos', '965540489', '', '967828379', '4 x por semana', 1),
(10, 0, 0.00, 0.00, 'LEONOR GUIMARÃES MONTEIRO', 'Rua do Mourigo, n22 R/ch', 'Vila Nova do Campo', '4795-516', '0', '2016-12-21', '', '0', '', 2, '', '', 16, 0, 0, 57, NULL, 'Ariana Monteiro', '919124722', '', '0', '', 1),
(11, 0, 0.00, 0.00, 'LUNA FIGUEIRAS FREITAS', 'Estrada nacional 105, n728', 'Lordelo - GMR', '4785-025', '0', '2016-02-20', '', '0', 'Carreiro', 3, '', '', 12, 0, 0, 93, NULL, 'Mónica Alexandra da Silva Figueiras', '968610806', '', '0', '2 x por semana', 1),
(12, 0, 0.00, 0.00, 'MARGARIDA MENDES DA COSTA', 'Praceta das Fontainhas, n3 3Dt', 'Vila das Aves', '4795-021', '0', '2016-08-07', '', '912860138', 'Escola do Bom Nome', 3, '', '', 16, 0, 0, 97, NULL, 'Sara Cristina Mendes Pedrosa', '916710987', '', '0', '', 1),
(13, 0, 0.00, 0.00, 'MARIA DA SILVA COSTA', 'Rua da Indústria n375', 'Rebordões', '4795-207', '0', '2017-03-16', '', '0', 'Escola da Ponte', 2, '', '', 0, 0, 0, NULL, NULL, 'Patrícia do Rosário Fernandes da Silva', '918308602', '', '0', '', 0),
(14, 0, 0.00, 0.00, 'MARIANA GONÇALVES COSTA', 'Rua Antero de Quental n143', 'Vila das Aves', '4795-033', '0', '2016-11-08', '', '0', 'Bairro', 3, '', 'G3A', 8, 0, 0, 89, NULL, 'Ana Gonçalves', '915764995', '', '0', '2 x por semana', 1),
(15, 0, 0.00, 0.00, 'MATILDE GONÇALVES ARAÚJO', 'Rua dos Aves n15', 'Vila das Aves', '4795-057', '0', '2018-01-04', '', '0', '', 1, '', '', 6, 0, 0, 7, NULL, 'Sónia Sofia Martins Gonçalves', '918141603', '', '0', '', 1),
(16, 0, 0.00, 0.00, 'NAYRA CASTELO OLIVEIRA', 'Rua Dr. Joaquim Santos Simões, n3', 'Lordelo - GMR', '48155-74', '0', '2016-06-18', '', '0', 'Escola Básica do Carreiro', 2, '', '', 8, 0, 0, 49, NULL, '', '0', 'Miguel da Silva', '965039924', '', 1),
(17, 0, 0.00, 0.00, 'SANTIAGO FREITAS MARTINS CARNEIRO', 'Rua do Brejo, n178', 'Santo Tirso', '4825-254', '0', '2017-08-16', '', '0', 'Escla Básica de Quinchães', 1, '', '', 4, 4, 0, 5, NULL, 'Sylvie Freitas Fernandes', '932735468', '', '0', '', 1),
(18, 0, 0.00, 0.00, 'SANTIAGO LEITE MARQUES', 'Rua Monte de Cima 195 B', 'Guardizela - GMR', '4785-025', '0', '2018-10-22', '', '0', 'Bom Nome', 1, '', 'B', 8, 0, 0, 9, NULL, 'Elisabete Castro Leite', '912491531', '', '0', '', 1),
(19, 0, 0.00, 0.00, 'TOMÁS LOPES COSTA', 'Praça do Bom Nome, Ent 6 - 3Esq', 'São Tomé Negrelos', '4795-662', '0', '2015-05-12', 'patriciaclopes23@gmail.com', '0', 'Escola de Bairro', 4, '', 'GB', 0, 0, 0, NULL, NULL, 'Patrícia Carneiro Lopes', '910447006', '', '0', '', 0),
(20, 0, 0.00, 0.00, 'ALESSIA CHIARA CIFELLI', 'Travessa da Carreira, n133', 'Vila das Aves', '4795-', '0', '2014-05-06', '', '0', 'Escola de Ave', 5, '', '', 12, 0, 0, 173, NULL, 'Vanessa Andreia Gomes Lemos', '913198140', '', '0', '', 1),
(21, 0, 0.00, 0.00, 'ANA FRANCISCA OLIVEIRA MENDES DA SILVA', 'Rua das Escolas, n4361', 'Guardizela - GMR', '4765-496', '0', '2013-10-21', '', '0', '', 6, '', '', 12, 0, 1, 193, NULL, 'Maia Goreti Gonçalves Oliveira da Silva', '991550683', 'Pedro Silva', '0', '3 x por semana', 1),
(22, 0, 0.00, 0.00, 'DINIS MANUEL SOUSA PACHECO', 'Rua São José, n280 1andar', 'Vila das Aves', '4785-000', '0', '2013-09-22', '', '0', 'EB 2, 3 de Vila das Aves', 6, '', 'A', 12, 0, 0, 193, NULL, '', '0', 'Alexandre Manuel Ferreira Pacheco', '914576368', '', 1),
(23, 0, 0.00, 0.00, 'FRANCISCO DUARTE PINTO GOMES', 'Rua José Pacheco n63', 'S. Tomé de Negrelos', '4795-641', '0', '2013-10-12', '', '0', '', 6, '', '', 12, 0, 0, 193, NULL, 'Natália de Jesus Ferreira Pinto', '916093982', '', '0', '', 1),
(24, 0, 0.00, 0.00, 'ÍRIS FERREIRA COELHO', 'Rua Santa Clara n 138', 'Vila das Aves', '4795-112', '271666838', '2014-08-19', '', '0', '', 5, '', '', 12, 0, 0, 173, NULL, 'Helena Isabel Pereira Gomes Ferreira', '939330876', '', '0', '', 1),
(25, 0, 0.00, 0.00, 'LUÍS JÚNIOR MACHADO FERREIRA', 'Rua 25 de Abril, 2 Dt', 'Vila das Aves', '4795-023', '0', '2014-02-14', '', '913294202', 'Escola Básica do Ave', 5, '', 'C', 12, 0, 0, 173, NULL, 'Joana Machado', '918419650', '', '0', '3 x por semana', 1),
(74, 0, 0.00, 0.00, 'JOSÉ PEDRO SIMÕES ALVES', 'Rua Alto Sobrado, n203', 'Vila das Aves', '4795-031', '0', '2010-12-09', '', '963873310', '', 9, '', '', 8, 0, 0, 257, NULL, 'Filipa Alves', '961399848', '', '0', '2 x por semana', 1),
(73, 0, 0.00, 0.00, 'JOÃO CARLOS DA SILVA COSTA', 'Rua da Indústria n375', 'Rebordões', '4795-207', '0', '2009-06-27', '', '930681390', 'D. Dinis', 10, 'Curso Desporto', '', 0, 0, 0, NULL, NULL, 'Patrícia do Rosário Fernandes da Silva', '918308602', '', '0', '', 1),
(72, 0, 0.00, 0.00, 'IRIS FONSECA CALÇADA', 'Largo Francisco M. Guimarães, Ent 80 2 E T', 'Vila das Aves', '4795-016', '0', '2012-02-18', '', '0', '', 7, '', '', 12, 0, 0, 205, NULL, 'Carla Cristina Carneiro Fonseca', '919083515', '', '0', '', 1),
(71, 0, 0.00, 0.00, 'HENRIQUE CARDOSO VIEIRA', 'Estrada Nacional 105, n2', 'Lordelo- GMR', '4815-135', '0', '2012-01-02', '', '927293387', '', 7, '', '', 12, 0, 0, 205, NULL, 'Sandra Filipa Gomes Cardoso', '918980555', '', '0', '', 1),
(70, 0, 0.00, 0.00, 'AFONSO RODRIGUES SALGADO', 'Av. Monte dos Saltos, n45', 'Sequeirô - St. Tirso', '4780-641', '275127109', '2008-02-25', '', '0', 'Escola Báica de Ave', 11, '', 'F', 8, 0, 0, 305, NULL, 'Paula Francisca Couto Rodrigues', '932902925', '', '0', '1 x por semana', 1),
(69, 0, 0.00, 0.00, 'GONÇALO MARTINS GUIMARÃES', 'Al. Eng. João Mallen Junior, n15 1Dt.', 'Vila das Aves', '4795-910', '0', '2011-06-02', '', '937155288', 'EB de São Martinho', 8, '', '', 4, 0, 0, 225, NULL, 'Florbela Martins', '916525051', '', '0', '', 1),
(68, 0, 0.00, 0.00, 'FRANCISCA DE CAMPOS MACHADO', 'Travessa Silva Araújo, n49 1 Esq.', 'Vila das Aves', '4795-168', '0', '2011-11-29', '', '0', 'Escola Básica das Aves', 8, '', '', 12, 0, 0, 233, NULL, 'Sandra Sofia da Silva Campos', '912951039', 'Jorge', '912337751', '', 1),
(67, 0, 0.00, 0.00, 'DUARTE ROCHA AZEVEDO', 'Rua Parque de Jogos n50', 'Carreira - VNF', '4765-070', '0', '2010-08-31', '', '0', 'ARTAVE', 9, '', '', 8, 0, 0, 257, NULL, 'Lucia de Jesus Rocha Lopes', '912942692', '', '0', '', 1),
(66, 0, 0.00, 0.00, 'CAROLINA CARDOSO DE MOURA BRANDÃO FERREIRA', 'Rua Municipal de Minhava n418', 'Vila Real', '', '0', '2010-06-27', '', '912835629', 'Escola Básica do Ave', 9, '', '', 4, 0, 0, 253, NULL, 'Paula Cristina Cardoso Brandão', '912321242', '', '0', '', 1),
(65, 0, 0.00, 0.00, 'CAMILA SILVA DIAS', 'R. D. Américo Bispo de Lamego, n980', 'Vila das Aves', '4795-842', '274760002', '2011-03-29', '', '0', '', 8, '', '', 8, 0, 0, 229, NULL, 'Carla Silva', '914756128', '', '0', '', 1),
(64, 0, 0.00, 0.00, 'BRUNA MARIA NICOLAU ALMEIDA', 'Urb. Vila Verde Lote 11', 'Bairro - VNF', '4765-065', '0', '2010-11-02', '', '932575153', '', 9, '', 'C', 0, 8, 0, NULL, 277, 'Elsa Maria Almeida paiva', '932608094', '', '0', '', 1),
(62, 0, 0.00, 0.00, 'ANTHONY COSTA PINHEIRO', 'Rua Salgado 108', 'Oliveira S. Mateus - VNF', '4765-757', '0', '2010-07-16', '', '0', '', 9, '', '', 12, 0, 0, 261, NULL, 'Sofia da Costa', '961819591', '', '0', '1 x por semana', 1),
(61, 0, 0.00, 0.00, 'ANA RITA SILVA COSTA', 'Rua Pe. Luís Maria Ol. Nascimento n220', 'Bente VNF', '4770-060', '0', '2010-08-14', '', '912049298', 'Escola Padre Benjamim Salgado', 9, '', '', 8, 0, 0, 257, NULL, 'Cecília Silva Cruz', '918910007', '', '0', '', 1),
(60, 0, 0.00, 0.00, 'ANA RITA DA SILVA BARROS', 'Av. Das Lameiras', 'Delães', '4765-618', '0', '2010-01-16', '', '912821286', 'Escola Básica 2/3 das Aves', 9, '', '', 8, 0, 0, 257, NULL, 'Isabel Rodrigues da Silva', '914736567', '', '0', '2 x por semana', 1),
(52, 0, 0.00, 0.00, 'ANA INÊS FERREIRA COUTO', 'Rua da Granja, n56', 'Carreira - VNF', '4765-075', '276812301', '2010-12-18', '', '0', 'Básica Vila das Aves', 9, '', '', 12, 0, 0, 261, NULL, 'Bernadete Ferreira', '919872717', '', '0', '', 1),
(63, 0, 0.00, 0.00, 'BEATRIZ GONÇALVES SOUSA', 'Trav. José Dias Oliveira, n27', 'Mogege - VNF', '4770-350', '0', '2004-12-27', '', '912860138', 'Secundária Pe. Benjamim Salgado', 9, '', '', 12, 0, 0, 261, NULL, 'Elisabete Carvalho', '915897387', 'Miguel Sousa', '919167714', '', 1),
(50, 0, 0.00, 0.00, 'ALICE BARBOSA BAPTISTA', 'Rua do regalo, Bloco B 2D', 'Bairro - VNF', '4765-068', '0', '2010-05-25', '', '913664920', 'Escola Básica da Ponte', 9, '', '', 8, 0, 0, 257, NULL, '', '0', 'Ricardo da Silva Baptista', '919730329', '', 1),
(53, 0, 0.00, 0.00, 'AFONSO OLIVEIRA TEIXEIRA', 'Rua de São Pedro, n12', 'Lordelo - GMR', '4815-176', '0', '2001-09-20', '', '912860138', 'Escola do Carreiro', 3, '', '', 12, 0, 0, 93, NULL, 'Anabela Araújo Oliveira', '918971811', 'Marco', '0', '', 1),
(49, 0, 0.00, 0.00, 'AFONSO RODRIGUES SILVA', 'Estrada Nacional 204-5, n 2011 2Esq', 'Landim - VNF', '4770-336', '280040563', '2012-08-30', '', '913197182', 'Escala Básica de Ave', 7, '', '', 12, 0, 0, 205, NULL, 'Alice Manuel Bezerra', '916834978', '', '0', '', 1),
(75, 0, 0.00, 0.00, 'JOSÉ PEDRO FRANCISCO CARNEIRO', 'R. de S. Tiago n15', 'Lordelo - GMR', '', '0', '2010-08-26', '', '0', '', 9, '', '', 8, 0, 0, 257, NULL, 'Luísa da Conceição da Cunha Pereira de Lima Francisco', '919190805', '', '0', '', 1),
(76, 0, 0.00, 0.00, 'LARA SOFIA FERREIRA COELHO', 'Rua Santa Clara n 138', 'Vila das Aves', '4795-112', '271666838', '2011-03-26', '', '0', '', 8, '', '', 8, 0, 0, 229, NULL, 'Helena Isabel Pereira Gomes Ferreira', '939330876', '', '0', '', 1),
(77, 0, 0.00, 0.00, 'LAURA DA SILVA MARTINS', 'Rua Gil Vicente n 1', 'Vila das Aves', '4795-299', '0', '2010-02-15', '', '912837629', 'Agrupamento de Escolas de São Martinho', 9, '', '', 8, 0, 0, 257, NULL, 'Aurora Manuela Martins da Silva', '916569283', '', '0', '', 1),
(78, 0, 0.00, 0.00, 'LEONOR GOUVEIA DE ARAÚJO', 'Rua Pedro Dioga, n 15', 'Vila das Aves', '4795-', '0', '2010-12-16', '', '912031139', 'Escala Básica de Ave', 9, '', 'A', 12, 0, 0, 261, NULL, 'Maria Armanda Gouveia Sousa Reis', '913996001', 'D. Alice - 910 556 587', '910556587', '12h/mês', 1),
(79, 0, 0.00, 0.00, 'LEONOR RIBEIRO SANTOS', 'Estrada Nacional 204-5, n1257', 'Carreira - VNF', '4765-074', '0', '2011-07-13', 'leonor.santos.5611@aeterrsave.net', '934633617', '', 8, '', '', 8, 0, 0, 229, NULL, 'Liliana Maria Marques Ribeiro', '915460505', '', '0', '2 x por semana', 1),
(80, 0, 0.00, 0.00, 'MARIANA BARBOSA DA COSTA', 'Rua Nova n80, 1 Esq. Trás', 'St Maria Oliveira - VNF', '4765-334', '0', '2010-04-03', '', '926863804', '', 9, '', 'A', 4, 0, 0, 253, NULL, 'Carla Andreia Castro Barbosa', '936080586', '', '0', '', 1),
(81, 0, 0.00, 0.00, 'MATILDE LOPES SILVA', 'Travessa da Aves, Lote 2', 'Vila das Aves', '4785-025', '0', '2011-10-29', '', '912076290', 'Didáxis', 8, '', '', 12, 4, 0, 233, NULL, 'Anabela Carneiro Lopes', '911053161', '', '0', '', 1),
(82, 0, 0.00, 0.00, 'SANTIAGO DA CUNHA SILVA', 'Rua General Humberto Delgado', 'Vila das Aves', '4795-072', '0', '2011-08-24', '', '912520371', 'Escola do Ave', 8, '', '', 12, 0, 0, 233, NULL, 'Cidália Manuela da Cunha Oliveira', '915495472', '', '0', '', 1),
(83, 0, 0.00, 0.00, 'TIAGO CAMPOS FERNANDES', 'Rua do Agrelo, n60F 1ª Esq', 'S. Matinho do Campo', '4795-452', '0', '2010-02-03', '', '934636657', 'Escola Secundária D. DINIS', 9, '', '', 0, 0, 0, NULL, NULL, 'Cláudia Goreti Pereira Campos', '916300169', '', '0', '2h/semana', 1),
(150, 0, 0.00, 0.00, 'FILIPE MANUEL ALVES PACHECO', 'Urb. Crapts&Crapts, Casa 4', 'Bairro - VNF', '4765-680', '0', '2005-03-02', 'a16051@aedah.pt', '927542405', '', 0, '', '', 0, 0, 0, NULL, NULL, 'Emilia Alves - emilia.cristina@sapo.pt', '914411513', '', '0', '', 1),
(85, 0, 0.00, 0.00, 'ANA LUÍSA RIBEIRO FERREIRA', 'Rua Aldeia Nova n211', 'Carreira VNF', '4765-071', '0', '2009-12-29', '', '960387958', '', 10, '', '', 8, 0, 0, 285, NULL, 'Elisa Ângela Morais Ribeiro', '916967138', '', '0', '', 1),
(86, 0, 0.00, 0.00, 'BRUNA FRANCISCA PINTO RIBEIRO', 'Rua General Humberto Delgado, n244', 'Oliveira S. Mateus -VNF', '4795-072', '0', '2008-12-02', '', '919904860', 'Escola Secundária D. Afonso Henriques', 11, '', 'H2', 0, 0, 0, NULL, NULL, 'Maria do Céu Moreira Pinto', '917997477', '', '0', '', 0),
(87, 0, 0.00, 0.00, 'BRUNA SOFIA PACHECO QUEIRÓS', 'Rua da Bela Vista, n23', 'Vila das Aves', '4795-039', '0', '2009-02-15', '', '961039238', 'Agrupamento de Escolas Virgínia Moura', 10, '', 'D', 4, 0, 0, 281, NULL, 'Carla Sofia de Almeda Pacheco', '966264259', '938 446 729', '938446729', '', 1),
(88, 0, 0.00, 0.00, 'CAUÃ SANTANA BAHIA ONOFRE', 'Rua Louvazim, 409 2 Dt Frente', 'Vila das Aves', '4795-081', '0', '2006-09-07', '', '0', 'D. H. A.', 12, 'Ciências e Tecnologias', '', 8, 4, 0, 321, NULL, 'Andreia Onofre', '914246393', '914 246 404', '914246404', '', 1),
(89, 0, 0.00, 0.00, 'DIANA BARRA E ANTUNES', 'Av. D. João IV, Edifício Lei Fu 15, 9G', 'Macau', '4785-025', '0', '2008-09-15', 'joana15barra@gmail.com (mãe)', '+853 62758413', 'Escola Portuguesa de Macau', 11, '', '', 0, 0, 0, NULL, NULL, 'Joana Gabriela Ferreira Barra', '+853 62358449', '', '0', '', 0),
(90, 0, 0.00, 0.00, 'DIEGO CIFELLI', 'Travessa da Carreira, n133', 'Vila das Aves', '4795-', '0', '2008-03-12', '', '32', '', 11, '', '', 4, 0, 0, 301, NULL, 'Vanessa Andreia Gomes Lemos', '913198140', '', '0', '', 1),
(91, 0, 0.00, 0.00, 'ELISABETE FILIPA PINTO', 'Rua do Cardal, n179 R/chão', 'Bente - VNF', '4770-060', '0', '2009-04-25', '', '0', '', 10, '', '', 8, 0, 0, 285, NULL, 'Ana Rita Moreira Barbosa Pinto Martins', '910011016', '', '0', '', 1),
(93, 0, 0.00, 0.00, 'FRANCISCA BARROSO PEIXOTO', 'Rua Zeca da Costa, n119', 'Roriz - S. Tirso', '4795-378', '0', '2004-12-27', '', '912451499', 'D. A. H.', 11, 'Ciências e Tecnologias', '', 4, 0, 0, 301, NULL, '', '0', '961 040 772', '961040772', '', 1),
(94, 0, 0.00, 0.00, 'FRANCISCA PIMENTA COELHO', 'Rua Armindo Coielho Cardoso, n67', 'St Tirso - Negrelos', '4795-574', '273607588', '2009-02-20', '', '938155323', 'Colégio da Trofa', 10, 'C. T.', '', 4, 0, 0, 281, NULL, 'Alcina Pimenta', '965475445', '', '0', '1 x por semana', 1),
(95, 0, 0.00, 0.00, 'GUILHERME MONTEIRO COSTA', 'Calçada Jaime Gomes Guimarães, n111', 'Vila Nova do Campo', '4795-516', '0', '2008-12-29', '', '961161546', '', 10, '', '', 4, 4, 0, 281, NULL, 'Daniela Monteiro', '918177233', '', '0', '', 1),
(96, 0, 0.00, 0.00, 'GUILHERME PIMENTA NEVES', 'Rua António Maria Gomes n580', 'Roriz', '4795-268', '0', '2008-01-09', '', '965034514', '', 11, '', '', 4, 0, 0, 301, NULL, 'Sónia Paula Coelho Pimenta', '912860246', '', '0', '', 1),
(97, 0, 0.00, 0.00, 'HELENA ISABEL GOMES RIBEIRO', 'Rua da Ribes, n175', 'Sta. Maria Oliveira - VNF', '4765-332', '0', '2009-03-04', '', '913203409', 'Escola Secundária D. Afonso Henriques', 10, '', 'A', 4, 0, 0, 281, NULL, 'Cassilda Maria Antunes Gomes', '914148453', '', '0', '', 1),
(98, 0, 0.00, 0.00, 'INÊS CASTRO OLIVEIRA', 'Rua Luís de Camões, 89', 'Vila das Aves', '4795-079', '0', '2005-03-16', '', '961574038', 'D.Afonso Henriques', 12, '', '', 4, 0, 0, 317, NULL, 'Carminda Castro', '968519494', '', '0', '', 1),
(99, 0, 0.00, 0.00, 'INÊS FRANCISCA MACHADO GUEDES', 'Rua Antero de Quental, n93', 'Vila das Aves', '4795-033', '0', '2008-07-09', '', '932044120', 'D. Afonso Henriques', 11, '', 'A', 8, 0, 0, 305, NULL, '', '0', '965 791 778', '965791778', '', 1),
(100, 0, 0.00, 0.00, 'JOANA MIGUEL FERREIRA SOARES', 'Lr. Francisco M Guimarães, n80 R/C Dt Tr', 'Vila das Aves', '4795-016', '272153648', '2009-01-27', '', '912092189', '', 10, '', '', 4, 0, 0, 281, NULL, 'Oriana Manuel Cunha Ferreira', '933190540', '', '0', '', 1),
(101, 0, 0.00, 0.00, 'JOANA RODRIGUES FERREIRA', 'Rua José Moreira Araújo, n74 R/CH Dto.', 'Vila das Aves', '4795-081', '265335159', '2006-11-20', '', '937257844', 'D. A. H.', 11, 'C. T.', 'C', 4, 0, 0, 301, NULL, 'Alexandrina Manuela Coelho Rodrigues', '962266036', '', '0', '1 x por semana', 1),
(102, 0, 0.00, 0.00, 'JOAQUIM RAFAEL DE SOUSA NOGUEIRA', 'Rua Pedro Alves Cabral n20', 'Rebodões', '4795-222', '0', '1988-06-06', '', '919705824', '', 21, '', '', 0, 0, 0, NULL, NULL, '', '0', '', '0', '', 0),
(103, 0, 0.00, 0.00, 'LARA BEATRIZ PEREIRA DE SOUSA', 'Rua de  Carrezedo, n210', 'Delães - VNF', '4765-605', '0', '2009-04-06', '', '915525173', 'Escola D. Afonso Henriques', 10, 'Ciências Socioeconómicas', 'SE', 4, 0, 0, 281, NULL, 'Ângela Cristiana Machado Cunha Pereira', '914712379', '', '0', '1 x por semana', 1),
(147, 0, 0.00, 0.00, 'MARIA INÊS LEAL MAGALHÃES', 'Trav. Bernardino Gomes Ferreira', 'Vila das Aves', '4795-055', '0', '2008-10-08', '', '918524967', '', 10, '', '', 8, 0, 0, 285, NULL, 'Paula Cristina da Costa Leal Magalhães', '917878947', '', '0', '', 1),
(105, 0, 0.00, 0.00, 'LEONOR LOPES SILVA', 'Travessa da Aves, Lote 2', 'Vila das Aves', '4785-025', '0', '2008-07-16', '', '919952545', 'Didáxis', 11, 'Ciências e Tecnologias', '', 4, 4, 0, 301, NULL, 'Anabela Carneiro Lopes', '911053161', '', '0', '', 1),
(106, 0, 0.00, 0.00, 'LEONOR MAGALHÃES GONÇALVES', 'Lrg. Dr. Braga da Cruz 135, 1 Esq', 'Vila das Aves', '4795-015', '0', '2008-05-13', '', '935484335', 'D. Dinis', 11, 'Humanidades', 'D', 4, 0, 0, 301, NULL, 'Leonardo Gonçalves', '922123442', '', '0', '', 1),
(107, 0, 0.00, 0.00, 'LÍLIA OLIVEIRA FERREIRA', 'Rua St. Clara, 383 R/Ch', 'Vila das Aves', '4795-112', '0', '2009-03-21', '', '961046691', '', 10, '', 'C', 8, 0, 0, 285, NULL, 'Margarida da Conceição Ferreira Oliveira', '938275438', '', '0', '', 1),
(108, 0, 0.00, 0.00, 'LUÍSA ISABEL MARTINS MOURA', 'Av. De Poldrães 231', 'Vila das Aves', '4795-006', '0', '2009-04-01', '', '938073260', '', 10, '', 'AV', 4, 0, 0, 281, NULL, 'Isabel Moura', '932942448', '', '0', '', 1),
(109, 0, 0.00, 0.00, 'MARGARIDA FILIPA FRAGA MONTEIRO', 'Rua Camilo Castelo Branco, n825', 'Vila das Aves', '4793-045', '0', '2009-11-24', '', '915248075', 'D. Afonso Henriques', 10, '', 'Artes', 4, 0, 0, 281, NULL, 'Cátia Fraga', '916841152', '', '0', '1 x por semana', 1),
(110, 0, 0.00, 0.00, 'MARIA CLARA FERREIRA DE FARIA', 'Rua Professor Jeónimo de Castro, n26', 'Vila das Aves', '4785-000', '0', '2009-01-20', '', '912291806', 'D. Afonso Henriques', 10, '', 'SE', 4, 0, 0, 281, NULL, 'Luisa Ferreira', '913385760', '', '0', '', 1),
(111, 0, 0.00, 0.00, 'MARIA HERDEIRO CARDOSO CARVALHO GUEDES', 'Largo Dr Braga da Cruz, 48 3 Esq', 'Vila das Aves', '4795-015', '0', '2008-11-08', '', '911530537', 'Escola Secundário Afonso Henriques', 10, 'Ciências e Tecnologias', '', 4, 0, 0, 281, NULL, 'Patrícia Cardoso Guedes', '936052285', '', '0', '', 1),
(112, 0, 0.00, 0.00, 'MARIA LUÍS MACHADO FERREIRA', 'Rua 25 de Abril, 2 Dt', 'Vila das Aves', '4795-023', '0', '2009-05-22', '', '912193746', 'Escola D. Afonso Henriques', 10, 'Humanidades', 'E', 0, 0, 0, NULL, NULL, 'Joana Machado', '918419650', '', '0', '2 x por semana', 0),
(113, 0, 0.00, 0.00, 'MARIANA LOPES SANTOS', 'Rua Parque de Jogos, n42', 'Carreira VNF', '4765-071', '0', '2009-06-28', '', '0', 'EB de Pedome', 10, '', '', 8, 0, 0, 285, NULL, 'Silvia Maria Lopes Rocha', '916686511', '', '0', '', 1),
(115, 0, 0.00, 0.00, 'PEDRO LUÍS FERREIRA MENDES', 'Rua do Enxudres, n79', 'Lordelo - GMR', '4815-165', '0', '2008-09-01', '', '916686511', 'D. A. H.', 11, 'Ciências e Tecnologias', '', 8, 0, 0, 305, NULL, 'Carla Susana Ferreira da Cunha', '938508097', '', '0', '', 1),
(116, 0, 0.00, 0.00, 'RAFAEL MACHADO JOÃO LIMA', 'Rua de Santosinho, 127', 'Rebordões', '4795-231', '0', '2007-03-02', '', '930555374', '', 11, '', '', 8, 0, 0, 305, NULL, 'Andreia Presa Ferreira João', '912023336', '', '0', '', 1),
(117, 0, 0.00, 0.00, 'RODRIGO CARDOSO VIEIRA', 'Estrada Nacional 105, n2', 'Lordelo- GMR', '4815-135', '0', '2008-09-15', '', '962044446', 'Escola Secundária Vila das Aves', 11, '', 'SE', 4, 0, 0, 301, NULL, 'Sandra Filipa Gomes Cardoso', '918980555', '', '0', '', 1),
(118, 0, 0.00, 0.00, 'RODRIGO SÁ PIMENTA', 'Rua Associação do Outeiro, n 263', 'Carreira - VNF', '4765-078', '0', '2009-09-27', '', '938565659', '', 10, '', 'B', 8, 0, 0, 285, NULL, 'Rosário de Fátima Sá Ribeiro', '918951934', '916 049 948', '916049948', '', 1),
(119, 0, 0.00, 0.00, 'SANTIAGO FERREIRA DE SOUSA', 'Rua Igreja Sanfins, 49', 'Bairro - VNF', '4765-040', '0', '2009-12-06', '', '919893815', 'Escola Secundária Vila das Aves', 10, '', 'B', 8, 0, 0, 285, NULL, 'Laurentina de Jesus da Silva Ferreira', '914675562', '', '0', '', 1),
(120, 0, 0.00, 0.00, 'SARA DA COSTA ESTEVES', 'Rua São Bento 153', 'Lordelo - GMR', '4815-207', '0', '2007-06-29', '', '933248355', 'Secundária Dom Dinis', 12, '', '', 8, 0, 0, 321, NULL, 'Lucilia Raquel Pereira da Costa', '936772231', '', '0', '', 1),
(121, 0, 0.00, 0.00, 'SORAIA LEAL FONSECA', 'Ru Quinta da Costa 10', 'Roriz - St. Tirso', '4795-327', '0', '2008-07-01', '', '911163391', 'Escola Profissional Oficina', 11, 'Desenho digital 3D', '', 0, 4, 0, NULL, 313, 'Ana Bela Leal', '967855631', '', '0', '', 1),
(122, 0, 0.00, 0.00, 'SORAIA MOUTINHO OLIVEIRA', 'Rua S. André 202', 'Vila das Aves', '4795-113', '0', '2008-06-21', '', '926693330', '', 11, '', '', 8, 0, 0, 305, NULL, 'Soraia Oliveira', '936073904', '', '0', '', 1),
(123, 0, 0.00, 0.00, 'TIAGO ANTÓNIO MACHADO MARTINS', 'Av. Conde Vizela, n36', 'Vila das Aves', '4795-004', '0', '2007-05-17', '', '960071196', 'Escola D. Afonso Henriques', 12, 'C. T.', 'B', 4, 0, 0, 317, NULL, 'Cidália Machado', '968491840', '', '0', '1 x por semana', 1),
(124, 0, 0.00, 0.00, 'TOMÁS EDUARDO BESSA SOUSA', 'Rua dos Louros n10', 'Lordelo', '4815-195', '0', '2009-04-28', '', '961156936', 'S. Tomé de Negrelos', 10, '', '', 12, 0, 0, 289, NULL, 'Luísa Maria Caneiro Bessa', '965663963', '', '0', '', 1),
(125, 0, 0.00, 0.00, 'VASCO MONTEIRO MARTINS', 'Rua da Indústria, n 145', 'Rebordões - St. Tirso', '4795-207', '0', '2004-12-27', '', '966462578', 'Escola Secundária D. Afonso Henriques', 10, '', 'A', 4, 0, 0, 281, NULL, 'Andreia Carla Dias Monteiro', '913418845', '914 099 595', '914099595', '', 1),
(126, 0, 0.00, 0.00, 'VITÓRIA DE MACEDO CAMPOS', 'Rua Monsenhor José Ferreira, n95', 'Vila das Aves', '4795-088', '260282170', '2007-04-12', '', '930527597', 'Secundária D. Afonso Henriques', 12, 'Ciências e Tecnologias', '', 8, 0, 0, 321, NULL, 'Fernanda Liliana Silva Macedo', '964895017', '', '0', '', 1),
(127, 0, 0.00, 0.00, 'VICENTE FERREIRA DA SILVA', 'Lar Dr. Braga da Cruz, n94, 4Dt', 'Vila das Aves', '4795-015', '274051346', '2014-06-17', '', '0', '', 5, '', '', 0, 8, 0, NULL, 181, 'Mónica Maria Ferreira   (Avó Joaquina - 913 822 703)', '938186968', '', '938186967', '', 1),
(128, 0, 0.00, 0.00, 'ANA CAROLINA SOARES PACHECO', 'Rua de Sobrado, 186', 'Vila das Aves', '4795-121', '279765002', '2012-10-25', '', '0', '', 7, '', '', 8, 0, 0, 201, NULL, '', '252872652', 'Carlos Rafael Alves Pacheco', '0', '', 1),
(129, 0, 0.00, 0.00, 'DUARTE TEIXEIRA RIBEIRO', 'Av. Comendador Ab. F. Oliv. N 511 Dto Norte', 'São Martinho do Campo', '4795-443', '0', '2009-10-12', '', '0', '', 9, '', '', 8, 4, 0, 257, NULL, 'Daniela Marina Martins Teixeira', '913592354', '', '0', '', 1),
(130, 0, 0.00, 0.00, 'ERIC PINHEIRO RIBEIRO', 'Alameda João Paulo II, n 74', 'Vila das Aves', '4795-155', '277714486', '2011-11-16', '', '0', '', 8, '', '', 12, 0, 0, 233, NULL, 'Marisa Pinheiro', '917924427', '', '0', '', 1),
(131, 0, 0.00, 0.00, 'LEONOR TEIXEIRA RIBEIRO', 'Av. Comendador Ab. F. Oliv. N 511 Dto Norte', 'São Martinho do Campo', '4795-443', '0', '2011-12-12', '', '0', '', 8, '', '', 8, 0, 0, 229, NULL, 'Daniela Marina Martins Teixeira', '913592354', '', '0', '', 1),
(132, 0, 0.00, 0.00, 'LIA PINHEIRO RIBEIRO', 'Alameda João Paulo II, n 74', 'Vila das Aves', '4795-155', '277714486', '2011-11-16', '', '0', '', 8, '', '', 12, 0, 0, 233, NULL, 'Marisa Pinheiro', '917924427', '', '0', '', 1),
(133, 0, 0.00, 0.00, 'MARIA BEATRIZ DA COSTA BARROSO', 'Rua da Aldeia Nova, 351', 'Roriz- STS', '4765-044', '0', '2010-08-04', '', '910825865', 'S. MARTINHO DO CAMPO', 9, '', 'C', 8, 0, 0, 257, NULL, 'Sandra Marina Ferreira da Costa', '913184657', 'Pedro', '0', '2h /semana', 1),
(134, 0, 0.00, 0.00, 'MARTIM MIGUEL CRUZ OLIVEIRA', 'Rua do Fojo, n 148', 'Carreira - VNF', '4765-076', '280511728', '2012-02-09', 'martimmiguel2001@gmail.com', '912929716', 'EB Vila das Aves', 7, '', '', 12, 0, 0, 205, NULL, 'Marta da Conceição Coutinho Cruz', '910124180', '', '0', '', 1),
(135, 0, 0.00, 0.00, 'RODRIGO MIGUEL SILVA MATOS', 'Alameda Arnauldo Gama n121, 3 Esq', 'Vila das Aves', '4795-001', '271666838', '2010-01-21', '', '0', 'EB Vila das Aves', 9, '', '', 8, 0, 0, 257, NULL, 'Paula Marina Torres Silva', '918394159', 'Ricardo', '919346871', '', 1),
(136, 0, 0.00, 0.00, 'RÚBEN FILIPE SILVA MONTEIRO', 'Rua do Casino, n 301', 'Bairro - VNF', '4765-063', '0', '2010-12-07', '', '0', '', 9, '', '', 12, 0, 0, 261, NULL, 'Silvia', '918169689', 'Hélio Filipe Nogueira Monteiro', '916919586', '', 1),
(137, 0, 0.00, 0.00, 'TIAGO GABRIEL CASTRO DA SILVA', 'Rua das Lages, n23', 'Bairro - VNF', '4765-044', '0', '2010-03-15', '', '0', '', 9, '', '', 8, 0, 0, 257, NULL, 'Marisa Isabel Barbosa Castro Silva', '916030029', 'Pedro', '917103375', '', 1),
(138, 0, 0.00, 0.00, 'AFONSO AZEVEDO FERREIRA', 'Rua de St. Rita n 85', 'Cense - Vila das Aves', '', '0', '2009-02-26', '', '0', '', 10, '', '', 16, 0, 0, 293, NULL, 'Ângela Cristina Azevedo Pereira', '936545223', '', '0', '', 1),
(139, 0, 0.00, 0.00, 'FRANCISCO MARTINS PIMENTA DA SILVA PEREIRA', 'Praça do Bom Nome Ent.2, 1 Esq.', 'Vila das Aves', '4795-025', '0', '2007-10-02', '', '937790784', 'Secundária Afonso Henriques', 12, '', '', 8, 0, 0, 321, NULL, '', '0', 'Manuel Adérito da Silva Pereira', '965057633', '', 1),
(140, 0, 0.00, 0.00, 'GONÇALO DINIS FERREIRA FREITAS', 'Rua Quinta da Vila n95, Roriz', 'Santo Tirso', '4795-503', '272153648', '2009-01-27', '', '0', '', 10, '', '', 4, 0, 0, 281, NULL, 'Adriana Correia Ferreira Freitas', '916646649', '', '0', '', 1),
(141, 0, 0.00, 0.00, 'JOSÉ ANTÓNIO DIAS RASO', 'Rua Cônsul Aristides de Sousa Mendes, n 22', 'Lordelo - GMR', '4815-116', '0', '2008-08-19', 'zeraso3232@gmail.com', '936757374', 'Tomás Pelayo', 10, '', '', 4, 4, 0, 281, NULL, 'Darcília Isabel Dias Gomes', '962580494', '', '0', '', 1),
(142, 0, 0.00, 0.00, 'LEONOR FERREIRA DA SILVA', 'Lar Dr. Braga da Cruz, n94, 4Dt', 'Vila das Aves', '4795-015', '274051346', '2008-03-01', '', '938167704', 'EB 2,3 Bom Nome', 11, '', '', 8, 0, 0, 305, NULL, 'Mónica Maria Ferreira', '938186968', '', '938186967', '', 1),
(143, 0, 0.00, 0.00, 'RODRIGO SANTOS SILVA', 'Rua das Ínsuas, n457', 'Vilarinho -Santo Tirso', '4795-787', '0', '2007-12-27', '', '961468207', 'D. Afonso Heniques', 12, '', '', 4, 0, 0, 317, NULL, 'Leonor Silva', '933337728', '', '0', '', 1),
(151, 0, 0.00, 0.00, 'GUSTAVO RODRIGUES SALGADO', 'Av. Monte dos Saltos, nº45', 'Sequeirô - St. Tirso', '4780-641', '275127109', '2008-02-25', '', '969608175', 'Escola Báica de Ave', 9, '', 'F', 4, 0, 0, 253, NULL, 'Paula Francisca Couto Rodrigues', '932902925', '', '0', '', 1),
(152, 0, 0.00, 0.00, 'PEDRO DINIS ALVES PACHECO', 'Urb. Crapts&Crapts, Casa 4', 'Bairro - VNF', '4765-680', '0', '2008-10-21', '', '964804787', '', 11, '', '', 4, 0, 0, 301, NULL, 'Emilia Alves - emilia.cristina@sapo.pt', '914411513', '', '0', '', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `alunos_disciplinas`
--

DROP TABLE IF EXISTS `alunos_disciplinas`;
CREATE TABLE IF NOT EXISTS `alunos_disciplinas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idAluno` int NOT NULL,
  `idDisciplina` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `alunos_disponibilidade`
--

DROP TABLE IF EXISTS `alunos_disponibilidade`;
CREATE TABLE IF NOT EXISTS `alunos_disponibilidade` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idALuno` int NOT NULL,
  `dia` varchar(7) NOT NULL,
  `hora` time NOT NULL,
  `disponibilidade` tinyint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `alunos_pagamentos`
--

DROP TABLE IF EXISTS `alunos_pagamentos`;
CREATE TABLE IF NOT EXISTS `alunos_pagamentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idAluno` int NOT NULL,
  `mensalidade` int NOT NULL,
  `idMetodo` int NOT NULL,
  `observacao` text NOT NULL,
  `idProfessor` int NOT NULL,
  `estado` varchar(50) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pagoEm` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idAluno` (`idAluno`,`idProfessor`),
  KEY `idMetodo` (`idMetodo`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `alunos_pagamentos`
--

INSERT INTO `alunos_pagamentos` (`id`, `idAluno`, `mensalidade`, `idMetodo`, `observacao`, `idProfessor`, `estado`, `created`, `pagoEm`) VALUES
(1, 1, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(2, 4, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(3, 5, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(4, 6, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(5, 7, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(6, 8, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(7, 9, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(8, 10, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(9, 11, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(10, 12, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(11, 13, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(12, 14, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(13, 15, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(14, 16, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(15, 17, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(16, 18, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(17, 19, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(18, 20, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(19, 21, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(20, 22, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(21, 23, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(22, 24, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(23, 25, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(24, 73, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(25, 67, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(26, 53, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(27, 78, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(28, 83, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(29, 150, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(30, 85, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(31, 86, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(32, 87, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(33, 88, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(34, 89, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(35, 90, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(36, 91, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(37, 149, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(38, 93, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(39, 94, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(40, 95, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(41, 96, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(42, 97, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(43, 98, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(44, 99, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(45, 100, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(46, 101, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(47, 102, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(48, 103, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(49, 147, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(50, 105, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(51, 106, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(52, 107, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(53, 108, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(54, 109, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(55, 110, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(56, 111, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(57, 112, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(58, 113, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(59, 115, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(60, 116, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(61, 117, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(62, 118, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(63, 119, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(64, 120, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(65, 121, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(66, 122, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(67, 123, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(68, 124, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(69, 125, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(70, 126, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(71, 127, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(72, 133, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(73, 138, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(74, 139, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(75, 140, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(76, 141, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(77, 142, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00'),
(78, 143, 0, 0, '', 0, 'Pendente', '2025-03-01 10:07:42', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `alunos_presenca`
--

DROP TABLE IF EXISTS `alunos_presenca`;
CREATE TABLE IF NOT EXISTS `alunos_presenca` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idAluno` int NOT NULL,
  `idProfessor` int NOT NULL,
  `idDisciplina` int NOT NULL,
  `individual` tinyint NOT NULL DEFAULT '0',
  `anoLetivo` varchar(25) NOT NULL,
  `duracao` int NOT NULL,
  `dia` date NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `criado_por` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idALuno` (`idAluno`),
  KEY `idDisciplina` (`idDisciplina`),
  KEY `criado_por` (`criado_por`),
  KEY `idProfessor` (`idProfessor`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `alunos_presenca`
--

INSERT INTO `alunos_presenca` (`id`, `idAluno`, `idProfessor`, `idDisciplina`, `individual`, `anoLetivo`, `duracao`, `dia`, `criado_em`, `criado_por`) VALUES
(1, 22, 1, 4, 0, '2024/2025', 45, '2025-04-20', '2025-04-20 21:11:33', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `alunos_recibo`
--

DROP TABLE IF EXISTS `alunos_recibo`;
CREATE TABLE IF NOT EXISTS `alunos_recibo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idAluno` int NOT NULL,
  `anoAluno` int NOT NULL,
  `packGrupo` int NOT NULL,
  `horasRealizadasGrupo` int NOT NULL,
  `horasBalancoGrupo` int NOT NULL,
  `packIndividual` int NOT NULL,
  `horasRealizadasIndividual` int NOT NULL,
  `horasBalancoIndividual` int NOT NULL,
  `mensalidade` int NOT NULL,
  `ano` int NOT NULL,
  `mes` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `alunos_recibo`
--

INSERT INTO `alunos_recibo` (`id`, `idAluno`, `anoAluno`, `packGrupo`, `horasRealizadasGrupo`, `horasBalancoGrupo`, `packIndividual`, `horasRealizadasIndividual`, `horasBalancoIndividual`, `mensalidade`, `ano`, `mes`) VALUES
(1, 1, 12, 8, 9, -7, 0, 1, -7, 20, 2025, 2),
(2, 2, 1, 0, 0, 0, 0, 0, 0, 0, 2025, 2),
(3, 3, 0, 0, 0, 0, 0, 0, 0, 0, 2025, 2),
(4, 4, 5, 0, 0, 0, 1, 0, 13, 0, 2025, 2),
(5, 5, 7, 8, 0, 16, 8, 0, 16, 0, 2025, 2),
(6, 6, 7, 8, 0, 16, 8, 0, 16, 0, 2025, 2),
(7, 7, 7, 20, 0, 40, 0, 0, 0, 20, 2025, 2),
(8, 1, 12, 8, 9, -8, 0, 1, -8, 20, 2025, 2),
(9, 2, 1, 0, 0, 0, 0, 0, 0, 0, 2025, 2),
(10, 3, 0, 0, 0, 0, 0, 0, 0, 0, 2025, 2),
(11, 4, 5, 0, 0, 0, 1, 0, 14, 0, 2025, 2),
(12, 5, 7, 8, 0, 24, 8, 0, 24, 0, 2025, 2),
(13, 6, 7, 8, 0, 24, 8, 0, 24, 0, 2025, 2),
(14, 7, 7, 20, 0, 60, 0, 0, 0, 20, 2025, 2),
(15, 1, 12, 8, 9, -9, 0, 1, -9, 20, 2025, 2),
(16, 2, 1, 0, 0, 0, 0, 0, 0, 0, 2025, 2),
(17, 3, 0, 0, 0, 0, 0, 0, 0, 0, 2025, 2),
(18, 4, 5, 0, 0, 0, 1, 0, 15, 0, 2025, 2),
(19, 5, 7, 8, 0, 32, 8, 0, 32, 0, 2025, 2),
(20, 6, 7, 8, 0, 32, 8, 0, 32, 0, 2025, 2),
(21, 7, 7, 20, 0, 80, 0, 0, 0, 20, 2025, 2),
(22, 7, 7, 20, 0, 100, 0, 0, 0, 20, 2025, 2),
(23, 7, 7, 20, 0, 120, 0, 0, 0, 20, 2025, 2),
(24, 7, 7, 20, 0, 140, 0, 0, 0, 140, 2025, 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `categorias`
--

DROP TABLE IF EXISTS `categorias`;
CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` text NOT NULL,
  `tipo` enum('receita','despesa') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `despesas`
--

DROP TABLE IF EXISTS `despesas`;
CREATE TABLE IF NOT EXISTS `despesas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `despesa` text NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `disciplinas`
--

DROP TABLE IF EXISTS `disciplinas`;
CREATE TABLE IF NOT EXISTS `disciplinas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `disciplinas`
--

INSERT INTO `disciplinas` (`id`, `nome`) VALUES
(1, 'Matemática'),
(2, 'Português'),
(3, 'Inglês'),
(4, 'Ciências Naturais'),
(5, 'Francês'),
(6, 'História'),
(7, 'Geografia'),
(8, 'Física Química'),
(9, 'Biologia'),
(10, 'Filosofia'),
(11, 'Geometria Descritiva'),
(14, 'Economia');

-- --------------------------------------------------------

--
-- Estrutura da tabela `ensino`
--

DROP TABLE IF EXISTS `ensino`;
CREATE TABLE IF NOT EXISTS `ensino` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `ensino`
--

INSERT INTO `ensino` (`id`, `nome`) VALUES
(1, '1º Ciclo'),
(2, '2º Ciclo'),
(3, '3º Ciclo'),
(4, 'Secundário'),
(5, 'Universidade'),
(7, 'Inscrição'),
(9, 'Transporte');

-- --------------------------------------------------------

--
-- Estrutura da tabela `mensalidade`
--

DROP TABLE IF EXISTS `mensalidade`;
CREATE TABLE IF NOT EXISTS `mensalidade` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ano` int NOT NULL,
  `horasGrupo` int NOT NULL,
  `mensalidadeHorasGrupo` int NOT NULL,
  `horasIndividual` int NOT NULL,
  `mensalidadeHorasIndividual` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=332 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `mensalidade`
--

INSERT INTO `mensalidade` (`id`, `ano`, `horasGrupo`, `mensalidadeHorasGrupo`, `horasIndividual`, `mensalidadeHorasIndividual`) VALUES
(5, 1, 4, 20, 0, 0),
(9, 1, 8, 40, 0, 0),
(13, 1, 12, 50, 0, 0),
(17, 1, 16, 60, 0, 0),
(18, 1, 17, 65, 0, 0),
(19, 1, 18, 70, 0, 0),
(33, 1, 32, 100, 0, 0),
(37, 1, 0, 0, 4, 60),
(41, 1, 0, 0, 8, 120),
(45, 2, 4, 20, 0, 0),
(49, 2, 8, 40, 0, 0),
(53, 2, 12, 50, 0, 0),
(57, 2, 16, 60, 0, 0),
(58, 2, 17, 65, 0, 0),
(59, 2, 18, 70, 0, 0),
(73, 2, 32, 100, 0, 0),
(77, 2, 0, 0, 4, 60),
(81, 2, 0, 0, 8, 120),
(85, 3, 4, 20, 0, 0),
(89, 3, 8, 40, 0, 0),
(93, 3, 12, 50, 0, 0),
(97, 3, 16, 60, 0, 0),
(98, 3, 17, 65, 0, 0),
(99, 3, 18, 70, 0, 0),
(113, 3, 32, 100, 0, 0),
(117, 1, 0, 0, 4, 60),
(121, 3, 0, 0, 8, 120),
(125, 4, 4, 20, 0, 0),
(129, 4, 8, 40, 0, 0),
(133, 4, 12, 50, 0, 0),
(137, 4, 16, 60, 0, 0),
(138, 4, 17, 65, 0, 0),
(139, 4, 18, 70, 0, 0),
(153, 4, 32, 100, 0, 0),
(157, 4, 0, 0, 4, 60),
(161, 4, 0, 0, 8, 120),
(165, 5, 4, 30, 0, 0),
(169, 5, 8, 50, 0, 0),
(173, 5, 12, 60, 0, 0),
(174, 5, 0, 0, 1, 15),
(175, 5, 0, 0, 2, 15),
(176, 5, 0, 0, 3, 15),
(177, 5, 0, 0, 4, 15),
(178, 5, 0, 0, 5, 15),
(179, 5, 0, 0, 6, 15),
(180, 5, 0, 0, 7, 15),
(181, 5, 0, 0, 8, 15),
(185, 6, 4, 35, 0, 0),
(189, 6, 8, 55, 0, 0),
(193, 6, 12, 65, 0, 0),
(197, 7, 4, 35, 0, 0),
(201, 7, 8, 65, 0, 0),
(205, 7, 12, 80, 0, 0),
(209, 7, 16, 100, 0, 0),
(213, 7, 20, 120, 0, 0),
(214, 7, 0, 0, 1, 15),
(215, 7, 0, 0, 2, 15),
(216, 7, 0, 0, 3, 15),
(217, 7, 0, 0, 4, 15),
(218, 7, 0, 0, 5, 15),
(219, 7, 0, 0, 6, 15),
(220, 7, 0, 0, 7, 15),
(221, 7, 0, 0, 8, 15),
(225, 8, 4, 35, 0, 0),
(229, 8, 8, 65, 0, 0),
(233, 8, 12, 80, 0, 0),
(237, 8, 16, 100, 0, 0),
(241, 8, 20, 120, 0, 0),
(242, 8, 0, 0, 1, 15),
(243, 8, 0, 0, 2, 15),
(244, 8, 0, 0, 3, 15),
(245, 8, 0, 0, 4, 15),
(246, 8, 0, 0, 5, 15),
(247, 8, 0, 0, 6, 15),
(248, 8, 0, 0, 7, 15),
(249, 8, 0, 0, 8, 15),
(253, 9, 4, 35, 0, 0),
(257, 9, 8, 65, 0, 0),
(261, 9, 12, 80, 0, 0),
(265, 9, 16, 100, 0, 0),
(269, 9, 20, 120, 0, 0),
(270, 9, 0, 0, 1, 15),
(271, 9, 0, 0, 2, 15),
(272, 9, 0, 0, 3, 15),
(273, 9, 0, 0, 4, 15),
(274, 9, 0, 0, 5, 15),
(275, 9, 0, 0, 6, 15),
(276, 9, 0, 0, 7, 15),
(277, 9, 0, 0, 8, 15),
(281, 10, 4, 40, 0, 0),
(285, 10, 8, 80, 0, 0),
(289, 10, 12, 120, 0, 0),
(293, 10, 16, 160, 0, 0),
(297, 10, 0, 0, 4, 72),
(301, 11, 4, 45, 0, 0),
(305, 11, 8, 90, 0, 0),
(309, 11, 12, 120, 0, 0),
(313, 11, 0, 0, 4, 72),
(317, 12, 4, 50, 0, 0),
(321, 12, 8, 100, 0, 0),
(325, 12, 12, 150, 0, 0),
(329, 11, 0, 0, 4, 80);

-- --------------------------------------------------------

--
-- Estrutura da tabela `metodos_pagamento`
--

DROP TABLE IF EXISTS `metodos_pagamento`;
CREATE TABLE IF NOT EXISTS `metodos_pagamento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cod` varchar(50) NOT NULL,
  `metodo` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `metodos_pagamento`
--

INSERT INTO `metodos_pagamento` (`id`, `cod`, `metodo`) VALUES
(1, 'metodo_01', 'Dinheiro'),
(2, 'metodo_02', 'MBWay');

-- --------------------------------------------------------

--
-- Estrutura da tabela `modulos`
--

DROP TABLE IF EXISTS `modulos`;
CREATE TABLE IF NOT EXISTS `modulos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cod` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `module` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `modulos`
--

INSERT INTO `modulos` (`id`, `cod`, `module`) VALUES
(1, 'adm_001', 'Orçamentos'),
(3, 'adm_002', 'Fichas de Trabalho'),
(4, 'adm_003', 'Produtos'),
(5, 'adm_004', 'Clientes'),
(6, 'adm_005', 'Administradores'),
(8, 'adm_006', 'Logs'),
(10, 'adm_007', 'Ganhos Anuais');

-- --------------------------------------------------------

--
-- Estrutura da tabela `professores`
--

DROP TABLE IF EXISTS `professores`;
CREATE TABLE IF NOT EXISTS `professores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` text NOT NULL,
  `email` text NOT NULL,
  `contacto` int NOT NULL,
  `pass` text NOT NULL,
  `img` text NOT NULL,
  `ativo` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `professores`
--

INSERT INTO `professores` (`id`, `nome`, `email`, `contacto`, `pass`, `img`, `ativo`) VALUES
(1, 'Sandra Martins', 'sdm.sandra@gmail.com', 913665676, '$2y$10$xonXJyTLiD4nRWtKgg7FxuD1ql2Chq3Sx.P.gWnTaQrjvjalXhH9i', '', 1),
(2, 'Juliana Coelho', 'julianasfcoelho@gmail.com', 917755697, '$2y$10$iHE/lpsrEXFbxmx0MJivfufKyFMHJzJeNsu5HXaazG4RaYvqiZiAm', '', 1),
(3, 'Filipe Lima', 'filipe.lima2001@gmail.com', 911731593, '$2y$10$sBwc0Da93VLY7FVxESuSUen9b3YRlwzKD63K1q3iJyCOwoG2leZzK', '', 1),
(4, 'Patricia Silva', 'patriciarosariosilva1981@gmail.com', 918308602, '$2y$10$D7.cSllB.ilzCfkd6Pk9Ye2BiEL3vk5iJYbuJQEcPVWbDv.VIezXW', '', 1),
(5, 'Cristiana Neto', 'cristiananeto@sapo.pt', 919549960, '$2y$10$286pT6v26k0eTNpgSFv2/OxgqTpKwU0De9tbXcz7T/JlS6nVNXSse', '', 1),
(6, 'Paula Borralho', 'anapaula.borralho@sapo.pt', 917402807, '$2y$10$/hWJGFLBJvEJy5f5Sny8G..iOsjBute9HlBZXNXUlezQbeRxHG7LC', '', 1),
(7, 'Ana Paula Fonseca', 'anapaulaferreirafonseca92@gmail.com', 915403775, '$2y$10$Pz.juomtG4TiuNzt6l6pf.Jn6uSYFNLhZBScV3LRUz4.NLRRInsim', '', 1),
(8, 'Natália Luciano', 'natalialuci@gmail.com', 966539965, '$2y$10$S8ructuKPxPHWza0RRRUtuMqQOcYNJx7aCH3yxY4eQAQnWarP2EVW', './images/uploads/foto_6806ab92e89773.16181461.png', 1),
(9, 'Arcélio Sampaio', 'arceliosampaio@gmail.com', 912220109, '$2y$10$EW8dOJTRJAlbnVzKjdy3zeo6izfPpG8Bq517a2APJTuP2HUW/GJRm', '', 1),
(10, 'Margarida Oliveira', 'margaridaisabeloliveira6@gmail.com', 918118126, '$2y$10$kV0OnCsrVemrQM.lMbZNHuXhkoJFJr0K8qC7M/hRC42R/ivveNNlm', '', 1),
(11, 'Marta Santos', '', 964391685, '', '', 1),
(12, 'Manuel Azevedo', '', 938855068, '', '', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `professores_disciplinas`
--

DROP TABLE IF EXISTS `professores_disciplinas`;
CREATE TABLE IF NOT EXISTS `professores_disciplinas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idProfessor` int NOT NULL,
  `idDisciplina` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `professores_disciplinas`
--

INSERT INTO `professores_disciplinas` (`id`, `idProfessor`, `idDisciplina`) VALUES
(1, 1, 8),
(27, 1, 8),
(28, 2, 2),
(29, 2, 6),
(30, 2, 7),
(31, 3, 1),
(32, 4, 1),
(33, 5, 1),
(34, 6, 2),
(35, 6, 6),
(36, 6, 7),
(37, 6, 10),
(38, 7, 2),
(39, 7, 4),
(40, 7, 6),
(41, 8, 2),
(42, 8, 3),
(43, 8, 5),
(44, 9, 4),
(45, 9, 8),
(46, 9, 9),
(47, 10, 11);

-- --------------------------------------------------------

--
-- Estrutura da tabela `professores_disponibilidade`
--

DROP TABLE IF EXISTS `professores_disponibilidade`;
CREATE TABLE IF NOT EXISTS `professores_disponibilidade` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idProfessor` int NOT NULL,
  `dia` varchar(7) NOT NULL,
  `hora` time NOT NULL,
  `disponibilidade` tinyint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `professores_ensino`
--

DROP TABLE IF EXISTS `professores_ensino`;
CREATE TABLE IF NOT EXISTS `professores_ensino` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idProfessor` int NOT NULL,
  `idEnsino` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `professores_ensino`
--

INSERT INTO `professores_ensino` (`id`, `idProfessor`, `idEnsino`) VALUES
(1, 1, 2),
(2, 1, 3),
(3, 1, 4),
(4, 1, 5),
(5, 2, 2),
(6, 2, 3),
(7, 2, 4),
(8, 2, 5),
(9, 3, 2),
(10, 3, 3),
(11, 3, 4),
(12, 3, 5),
(13, 4, 2),
(14, 4, 3),
(15, 4, 4),
(16, 4, 5),
(17, 5, 2),
(18, 5, 3),
(19, 5, 4),
(20, 5, 5),
(21, 6, 2),
(22, 6, 3),
(23, 6, 4),
(24, 6, 5),
(25, 7, 1),
(26, 8, 2),
(27, 8, 3),
(28, 8, 4),
(29, 8, 5),
(30, 9, 2),
(31, 9, 3),
(32, 9, 4),
(33, 9, 5),
(34, 10, 4),
(35, 10, 5),
(36, 11, 4),
(37, 11, 5),
(38, 12, 3),
(39, 12, 4),
(40, 1, 3),
(41, 1, 4),
(42, 2, 2),
(43, 2, 3),
(44, 2, 4),
(45, 3, 2),
(46, 3, 3),
(47, 3, 4),
(48, 4, 2),
(49, 4, 3),
(50, 4, 4),
(51, 5, 2),
(52, 5, 3),
(53, 5, 4),
(54, 6, 2),
(55, 6, 3),
(56, 6, 4),
(57, 7, 1),
(58, 8, 2),
(59, 8, 3),
(60, 8, 4),
(61, 9, 2),
(62, 9, 3),
(63, 9, 4),
(64, 10, 4);

-- --------------------------------------------------------

--
-- Estrutura da tabela `professores_logs`
--

DROP TABLE IF EXISTS `professores_logs`;
CREATE TABLE IF NOT EXISTS `professores_logs` (
  `idProfessor` int NOT NULL,
  `dataLog` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `logFile` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  KEY `idAdministrator` (`idProfessor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `professores_logs`
--

INSERT INTO `professores_logs` (`idProfessor`, `dataLog`, `logFile`) VALUES
(1, '2025-04-18 15:59:22', 'Administrador Geral 4x1(1) Saiu.'),
(8, '2025-04-18 16:06:25', 'Administrador Natália Luciano(8) Saiu.'),
(8, '2025-04-21 16:09:52', 'O professor [8]Natália Luciano atualizou o seu perfil.'),
(8, '2025-04-21 16:09:57', 'O professor [8]Natália Luciano atualizou o seu perfil.'),
(8, '2025-04-21 16:10:34', 'O professor [8]Natália Luciano atualizou o seu perfil.'),
(8, '2025-04-21 16:11:15', 'O professor [8]Natália Luciano atualizou o seu perfil.'),
(8, '2025-04-21 16:12:12', 'O professor [8]Natália Luciano atualizou o seu perfil.'),
(8, '2025-04-21 16:12:14', 'O professor [8]Natália Luciano atualizou o seu perfil.'),
(8, '2025-04-21 16:44:18', 'O professor [8]Natália Luciano atualizou o seu perfil'),
(8, '2025-04-21 16:44:24', 'O professor [8]Natália Luciano atualizou o seu perfil'),
(8, '2025-04-21 16:44:30', 'O professor [8]Natália Luciano atualizou o seu perfil'),
(8, '2025-04-21 16:52:11', 'O professor [8]Natália Luciano atualizou o seu perfil'),
(8, '2025-04-21 16:52:15', 'O professor [8]Natália Luciano atualizou o seu perfil'),
(8, '2025-04-21 16:52:19', 'O professor [8]Natália Luciano atualizou o seu perfil'),
(8, '2025-04-21 16:56:27', 'O professor [8]Natália Luciano atualizou o seu perfil'),
(8, '2025-04-21 16:56:35', 'O professor [8]Natália Luciano atualizou o seu perfil'),
(8, '2025-04-21 16:58:15', 'O professor [8]Natália Luciano atualizou o seu perfil'),
(8, '2025-04-21 17:05:10', 'O professor [8]Natália Luciano atualizou o seu perfil'),
(8, '2025-04-21 17:06:03', 'O professor [8]Natália Luciano atualizou o seu perfil'),
(8, '2025-04-21 17:17:28', 'O professor [8]Natália Luciano atualizou o seu perfil'),
(8, '2025-04-21 17:17:42', 'O professor [8]Natália Luciano atualizou o seu perfil'),
(8, '2025-04-21 17:22:57', 'O professor [8]Natália Luciano atualizou o seu perfil'),
(8, '2025-04-21 20:33:22', 'O professor [8]Natália Luciano atualizou o seu perfil');

-- --------------------------------------------------------

--
-- Estrutura da tabela `professores_presenca`
--

DROP TABLE IF EXISTS `professores_presenca`;
CREATE TABLE IF NOT EXISTS `professores_presenca` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idProfessor` int NOT NULL,
  `idAluno` int NOT NULL,
  `idDisciplina` int NOT NULL,
  `individual` tinyint NOT NULL,
  `anoLetivo` varchar(25) NOT NULL,
  `duracao` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `dia` date NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `criado_por` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idProfessor` (`idProfessor`),
  KEY `idDisciplina` (`idDisciplina`),
  KEY `criado_por` (`criado_por`),
  KEY `idAluno` (`idAluno`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `professores_presenca`
--

INSERT INTO `professores_presenca` (`id`, `idProfessor`, `idAluno`, `idDisciplina`, `individual`, `anoLetivo`, `duracao`, `dia`, `criado_em`, `criado_por`) VALUES
(1, 1, 22, 4, 0, '2024/2025', '45', '2025-04-20', '2025-04-20 21:11:33', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `professores_recibo`
--

DROP TABLE IF EXISTS `professores_recibo`;
CREATE TABLE IF NOT EXISTS `professores_recibo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idProfessor` int NOT NULL,
  `horasDadas1Ciclo` int NOT NULL,
  `valorUnitario1Ciclo` int NOT NULL,
  `valorParcial1Ciclo` int NOT NULL,
  `horasDadas2Ciclo` int NOT NULL,
  `valorUnitario2Ciclo` int NOT NULL,
  `valorParcial2Ciclo` int NOT NULL,
  `horasDadas3Ciclo` int NOT NULL,
  `valorUnitario3Ciclo` int NOT NULL,
  `valorParcial3Ciclo` int NOT NULL,
  `horasDadasSecundario` int NOT NULL,
  `valorUnitarioSecundario` int NOT NULL,
  `valorParcialSecundario` int NOT NULL,
  `horasDadasUniversidade` int NOT NULL,
  `valorUnitarioUniversidade` int NOT NULL,
  `valorParcialUniversidade` int NOT NULL,
  `total` int NOT NULL,
  `ano` int NOT NULL,
  `mes` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idProfessor` (`idProfessor`)
) ENGINE=MyISAM AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `professores_recibo`
--

INSERT INTO `professores_recibo` (`id`, `idProfessor`, `horasDadas1Ciclo`, `valorUnitario1Ciclo`, `valorParcial1Ciclo`, `horasDadas2Ciclo`, `valorUnitario2Ciclo`, `valorParcial2Ciclo`, `horasDadas3Ciclo`, `valorUnitario3Ciclo`, `valorParcial3Ciclo`, `horasDadasSecundario`, `valorUnitarioSecundario`, `valorParcialSecundario`, `horasDadasUniversidade`, `valorUnitarioUniversidade`, `valorParcialUniversidade`, `total`, `ano`, `mes`) VALUES
(1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(2, 1, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(3, 2, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(4, 3, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(5, 88, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(6, 89, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(7, 890, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(8, 1, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(9, 2, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(10, 3, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(11, 88, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(12, 89, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(13, 890, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(14, 1, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(15, 2, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(16, 3, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(17, 88, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(18, 89, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(19, 890, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(20, 1, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(21, 2, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(22, 3, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(23, 88, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(24, 89, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(25, 1, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(26, 2, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(27, 3, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(28, 88, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(29, 89, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(30, 1, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(31, 2, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(32, 3, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(33, 88, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(34, 89, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(35, 1, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(36, 2, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(37, 3, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(38, 88, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(39, 89, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(40, 1, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(41, 2, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(42, 3, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(43, 88, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(44, 89, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(45, 1, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(46, 2, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(47, 3, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(48, 88, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(49, 89, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(50, 1, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(51, 2, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(52, 3, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(53, 88, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2),
(54, 89, 0, 2, 0, 0, 2, 0, 0, 3, 0, 17, 4, 34, 0, 6, 0, 34, 2025, 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `transacoes`
--

DROP TABLE IF EXISTS `transacoes`;
CREATE TABLE IF NOT EXISTS `transacoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idCategoria` int NOT NULL,
  `descricao` text NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `valores_pagamento`
--

DROP TABLE IF EXISTS `valores_pagamento`;
CREATE TABLE IF NOT EXISTS `valores_pagamento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idEnsino` int NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `valores_pagamento`
--

INSERT INTO `valores_pagamento` (`id`, `idEnsino`, `valor`) VALUES
(2, 1, 2.00),
(3, 2, 3.00),
(4, 3, 4.00),
(5, 4, 6.00),
(6, 5, 18.00),
(7, 7, 10.00),
(9, 9, 10.00);

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `administrador_modulos`
--
ALTER TABLE `administrador_modulos`
  ADD CONSTRAINT `administrador_modulos_ibfk_2` FOREIGN KEY (`idModule`) REFERENCES `modulos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;
