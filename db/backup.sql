-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 29-Abr-2025 às 22:02
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
(1, '2025-04-21 20:41:06', 'O administrador [1]Geral 4x1 eliminou a mensalidade [331].'),
(1, '2025-04-22 05:29:31', 'O administrador [1]Geral 4x1 criou o aluno [153]teste.'),
(1, '2025-04-22 05:32:16', 'O administrador [1]Geral 4x1 criou o aluno [154]teste.'),
(1, '2025-04-22 08:09:37', 'O administrador Geral 4x1 atualizou o estado do aluno [1]LEONARDO LOPES GOMES de inativo para ativo'),
(1, '2025-04-22 08:09:59', 'O administrador Geral 4x1 atualizou o estado do aluno [15]MATILDE GONÇALVES ARAÚJO de inativo para ativo'),
(1, '2025-04-22 08:10:35', 'O administrador [1]Geral 4x1 atualizou o estado do aluno [15]MATILDE GONÇALVES ARAÚJO de ativo para inativo'),
(1, '2025-04-22 08:11:01', 'O administrador [1]Geral 4x1 atualizou o estado do aluno [1]LEONARDO LOPES GOMES de ativo para inativo'),
(1, '2025-04-22 09:17:38', 'O administrador [1]Geral 4x1 atualizou a transação [1]Pagamento ao aluno duart.'),
(1, '2025-04-22 09:18:36', 'O administrador [1]Geral 4x1 atualizou a transação [1]Pagamento ao aluno duart.'),
(1, '2025-04-22 09:19:38', 'O administrador [1]Geral 4x1 atualizou a transação [1]Pagamento ao aluno duart.'),
(1, '2025-04-22 09:19:51', 'O administrador [1]Geral 4x1 atualizou a transação [1]Pagamento ao aluno duarte.'),
(1, '2025-04-22 09:19:58', 'O administrador [1]Geral 4x1 criou a transaçõ [2]Teste.'),
(1, '2025-04-22 09:25:35', 'O administrador [1]Geral 4x1 eliminou a transação [2].'),
(1, '2025-04-22 09:47:19', 'O administrador [1]Geral 4x1 criou a transaçõ [3]teste.'),
(1, '2025-04-22 09:47:23', 'O administrador [1]Geral 4x1 eliminou a transação [3].'),
(1, '2025-04-24 15:22:42', 'O administrador [1]Geral 4x1 criou o aluno [155]Maria Eduarda Pontes Dias.'),
(1, '2025-04-24 15:22:50', 'O administrador [1]Geral 4x1 atualizou o aluno [155]Maria Eduarda Pontes Dias.'),
(1, '2025-04-24 19:28:16', 'O administrador [1]Geral 4x1 atualizou o aluno [155]Maria Eduarda Pontes Dias.');

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
  `notHorario` tinyint NOT NULL DEFAULT '0',
  `ativo` tinyint NOT NULL DEFAULT '1',
  `dataInscricao` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=156 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `alunos`
--

INSERT INTO `alunos` (`id`, `pack`, `balancoGrupo`, `balancoIndividual`, `nome`, `morada`, `localidade`, `codigoPostal`, `nif`, `dataNascimento`, `email`, `contacto`, `escola`, `ano`, `curso`, `turma`, `horasGrupo`, `horasIndividual`, `transporte`, `idMensalidadeGrupo`, `idMensalidadeIndividual`, `nomeMae`, `tlmMae`, `nomePai`, `tlmPai`, `modalidade`, `notHorario`, `ativo`, `dataInscricao`) VALUES
(1, 0, 40.00, 0.00, 'LEONARDO LOPES GOMES', 'Rua de Valinhas, 272', 'Regilde', '4815-621', '0', '2006-07-07', '', '+351956575995', '', 12, '', '', 8, 0, 0, 321, NULL, 'Vera Marciana Teixeira Lopes', '+351938459221', '', '', '', 0, 1, NULL),
(4, 0, 80.00, 0.00, 'ANTÓNIO MONTEIRO COSTA', 'Calçada Jaime Gomes Guimarães, n111', 'Vila Nova do Campo', '4795-516', '0', '2015-04-11', '', '', '', 4, '', '', 16, 0, 0, 137, NULL, 'Daniela Monteiro', '+351918177233', '', '', '', 0, 1, NULL),
(5, 0, 80.00, 0.00, 'CARLA ISABEL SILVA RIBEIRO', 'Rua José Moreira Araújo', 'Vila das Aves', '4795-081', '0', '2016-12-05', '', '', 'Bom Nome', 3, '', '', 16, 0, 0, 97, NULL, 'Carla Cristina Abreu Silva', '+351912308573', '', '', '1 x por semana', 0, 1, NULL),
(6, 0, 80.00, 20.00, 'DIOGO MARTINS GONÇALVES', 'Rua Sra do Rosário, n24', 'São Tomé de Negrelos', '4795-701', '0', '2014-12-01', '', '', '', 4, '', '', 16, 4, 0, 137, NULL, 'Rui Miguel Santos Gonçalves', '+351914089305', '', '', '', 0, 1, NULL),
(7, 0, 80.00, 0.00, 'FRANCISCO GUIMARÃES MONTEIRO', 'Rua do Mourigo, n22 R/ch', 'Vila Nova do Campo', '4795-516', '0', '2015-05-29', '', '', '', 4, '', '', 16, 0, 0, 137, NULL, 'Ariana Monteiro', '+351919124722', '', '', '', 0, 1, NULL),
(8, 0, 80.00, 0.00, 'ÍRIS MARIA PIMENTA ABREU MACHADO', 'R. António Aberu Machado, n499', 'Vila das Aves', '4795-034', '0', '2016-01-09', '', '', 'Escola do Bom Nome', 3, '', '', 16, 0, 0, 97, NULL, 'Cassilda Isabel Pimenta Abreu', '+351916504199', 'Rui Jorge Ribeiro Machado', '+351917683550', '', 0, 1, NULL),
(9, 0, 80.00, 0.00, 'ÍRIS SANTOS FERREIRA', 'Rua St. André, n354', 'Vila das Aves', '4795-113', '288027680', '2016-01-08', '', '', 'Bom Nome', 3, '', 'E', 16, 0, 0, 97, NULL, 'Laura da Conceição Canellhas Santos', '+351965540489', '', '+351967828379', '4 x por semana', 0, 1, NULL),
(10, 0, 80.00, 0.00, 'LEONOR GUIMARÃES MONTEIRO', 'Rua do Mourigo, n22 R/ch', 'Vila Nova do Campo', '4795-516', '0', '2016-12-21', '', '', '', 2, '', '', 16, 0, 0, 57, NULL, 'Ariana Monteiro', '+351919124722', '', '', '', 0, 1, NULL),
(11, 0, 60.00, 0.00, 'LUNA FIGUEIRAS FREITAS', 'Estrada nacional 105, n728', 'Lordelo - GMR', '4785-025', '0', '2016-02-20', '', '', 'Carreiro', 3, '', '', 12, 0, 0, 93, NULL, 'Mónica Alexandra da Silva Figueiras', '+351968610806', '', '', '2 x por semana', 0, 1, NULL),
(12, 0, 80.00, 0.00, 'MARGARIDA MENDES DA COSTA', 'Praceta das Fontainhas, n3 3Dt', 'Vila das Aves', '4795-021', '0', '2016-08-07', '', '+351912860138', 'Escola do Bom Nome', 3, '', '', 16, 0, 0, 97, NULL, 'Sara Cristina Mendes Pedrosa', '+351916710987', '', '', '', 0, 1, NULL),
(13, 0, 0.00, 0.00, 'MARIA DA SILVA COSTA', 'Rua da Indústria n375', 'Rebordões', '4795-207', '0', '2017-03-16', '', '', 'Escola da Ponte', 2, '', '', 0, 0, 0, NULL, NULL, 'Patrícia do Rosário Fernandes da Silva', '+351918308602', '', '', '', 0, 0, NULL),
(14, 0, 40.00, 0.00, 'MARIANA GONÇALVES COSTA', 'Rua Antero de Quental n143', 'Vila das Aves', '4795-033', '0', '2016-11-08', '', '', 'Bairro', 3, '', 'G3A', 8, 0, 0, 89, NULL, 'Ana Gonçalves', '+351915764995', '', '', '2 x por semana', 0, 1, NULL),
(15, 0, 30.00, 0.00, 'MATILDE GONÇALVES ARAÚJO', 'Rua dos Aves n15', 'Vila das Aves', '4795-057', '0', '2018-01-04', '', '', '', 1, '', '', 6, 0, 0, 7, NULL, 'Sónia Sofia Martins Gonçalves', '+351918141603', '', '', '', 0, 1, NULL),
(16, 0, 40.00, 0.00, 'NAYRA CASTELO OLIVEIRA', 'Rua Dr. Joaquim Santos Simões, n3', 'Lordelo - GMR', '48155-74', '0', '2016-06-18', '', '', 'Escola Básica do Carreiro', 2, '', '', 8, 0, 0, 49, NULL, '', '', 'Miguel da Silva', '+351965039924', '', 0, 1, NULL),
(17, 0, 20.00, 20.00, 'SANTIAGO FREITAS MARTINS CARNEIRO', 'Rua do Brejo, n178', 'Santo Tirso', '4825-254', '0', '2017-08-16', '', '', 'Escla Básica de Quinchães', 1, '', '', 4, 4, 0, 5, NULL, 'Sylvie Freitas Fernandes', '+351932735468', '', '', '', 0, 1, NULL),
(18, 0, 40.00, 0.00, 'SANTIAGO LEITE MARQUES', 'Rua Monte de Cima 195 B', 'Guardizela - GMR', '4785-025', '0', '2018-10-22', '', '', 'Bom Nome', 1, '', 'B', 8, 0, 0, 9, NULL, 'Elisabete Castro Leite', '+351912491531', '', '', '', 0, 1, NULL),
(19, 0, 0.00, 0.00, 'TOMÁS LOPES COSTA', 'Praça do Bom Nome, Ent 6 - 3Esq', 'São Tomé Negrelos', '4795-662', '0', '2015-05-12', 'patriciaclopes23@gmail.com', '', 'Escola de Bairro', 4, '', 'GB', 0, 0, 0, NULL, NULL, 'Patrícia Carneiro Lopes', '+351910447006', '', '', '', 0, 0, NULL),
(20, 0, 60.00, 0.00, 'ALESSIA CHIARA CIFELLI', 'Travessa da Carreira, n133', 'Vila das Aves', '4795-', '0', '2014-05-06', '', '', 'Escola de Ave', 5, '', '', 12, 0, 0, 173, NULL, 'Vanessa Andreia Gomes Lemos', '+351913198140', '', '', '', 0, 1, NULL),
(21, 0, 60.00, 0.00, 'ANA FRANCISCA OLIVEIRA MENDES DA SILVA', 'Rua das Escolas, n4361', 'Guardizela - GMR', '4765-496', '0', '2013-10-21', '', '', '', 6, '', '', 12, 0, 1, 193, NULL, 'Maia Goreti Gonçalves Oliveira da Silva', '+351991550683', 'Pedro Silva', '', '3 x por semana', 0, 1, NULL),
(22, 0, 60.00, 0.00, 'DINIS MANUEL SOUSA PACHECO', 'Rua São José, n280 1andar', 'Vila das Aves', '4785-000', '0', '2013-09-22', '', '', 'EB 2, 3 de Vila das Aves', 6, '', 'A', 12, 0, 0, 193, NULL, '', '', 'Alexandre Manuel Ferreira Pacheco', '+351914576368', '', 0, 1, NULL),
(23, 0, 60.00, 0.00, 'FRANCISCO DUARTE PINTO GOMES', 'Rua José Pacheco n63', 'S. Tomé de Negrelos', '4795-641', '0', '2013-10-12', '', '', '', 6, '', '', 12, 0, 0, 193, NULL, 'Natália de Jesus Ferreira Pinto', '+351916093982', '', '', '', 0, 1, NULL),
(24, 0, 60.00, 0.00, 'ÍRIS FERREIRA COELHO', 'Rua Santa Clara n 138', 'Vila das Aves', '4795-112', '271666838', '2014-08-19', '', '', '', 5, '', '', 12, 0, 0, 173, NULL, 'Helena Isabel Pereira Gomes Ferreira', '+351939330876', '', '', '', 0, 1, NULL),
(25, 0, 60.00, 0.00, 'LUÍS JÚNIOR MACHADO FERREIRA', 'Rua 25 de Abril, 2 Dt', 'Vila das Aves', '4795-023', '0', '2014-02-14', '', '+351913294202', 'Escola Básica do Ave', 5, '', 'C', 12, 0, 0, 173, NULL, 'Joana Machado', '+351918419650', '', '', '3 x por semana', 0, 1, NULL),
(74, 0, 40.00, 0.00, 'JOSÉ PEDRO SIMÕES ALVES', 'Rua Alto Sobrado, n203', 'Vila das Aves', '4795-031', '0', '2010-12-09', '', '+351963873310', '', 9, '', '', 8, 0, 0, 257, NULL, 'Filipa Alves', '+351961399848', '', '', '2 x por semana', 0, 1, NULL),
(73, 0, 0.00, 0.00, 'JOÃO CARLOS DA SILVA COSTA', 'Rua da Indústria n375', 'Rebordões', '4795-207', '0', '2009-06-27', '', '+351930681390', 'D. Dinis', 10, 'Curso Desporto', '', 0, 0, 0, NULL, NULL, 'Patrícia do Rosário Fernandes da Silva', '+351918308602', '', '', '', 0, 1, NULL),
(72, 0, 60.00, 0.00, 'IRIS FONSECA CALÇADA', 'Largo Francisco M. Guimarães, Ent 80 2 E T', 'Vila das Aves', '4795-016', '0', '2012-02-18', '', '', '', 7, '', '', 12, 0, 0, 205, NULL, 'Carla Cristina Carneiro Fonseca', '+351919083515', '', '', '', 0, 1, NULL),
(71, 0, 60.00, 0.00, 'HENRIQUE CARDOSO VIEIRA', 'Estrada Nacional 105, n2', 'Lordelo- GMR', '4815-135', '0', '2012-01-02', '', '+351927293387', '', 7, '', '', 12, 0, 0, 205, NULL, 'Sandra Filipa Gomes Cardoso', '+351918980555', '', '', '', 0, 1, NULL),
(70, 0, 40.00, 0.00, 'AFONSO RODRIGUES SALGADO', 'Av. Monte dos Saltos, n45', 'Sequeirô - St. Tirso', '4780-641', '275127109', '2008-02-25', '', '', 'Escola Báica de Ave', 11, '', 'F', 8, 0, 0, 305, NULL, 'Paula Francisca Couto Rodrigues', '+351932902925', '', '', '1 x por semana', 0, 1, NULL),
(69, 0, 20.00, 0.00, 'GONÇALO MARTINS GUIMARÃES', 'Al. Eng. João Mallen Junior, n15 1Dt.', 'Vila das Aves', '4795-910', '0', '2011-06-02', '', '+351937155288', 'EB de São Martinho', 8, '', '', 4, 0, 0, 225, NULL, 'Florbela Martins', '+351916525051', '', '', '', 0, 1, NULL),
(68, 0, 60.00, 0.00, 'FRANCISCA DE CAMPOS MACHADO', 'Travessa Silva Araújo, n49 1 Esq.', 'Vila das Aves', '4795-168', '0', '2011-11-29', '', '', 'Escola Básica das Aves', 8, '', '', 12, 0, 0, 233, NULL, 'Sandra Sofia da Silva Campos', '+351912951039', 'Jorge', '+351912337751', '', 0, 1, NULL),
(67, 0, 40.00, 0.00, 'DUARTE ROCHA AZEVEDO', 'Rua Parque de Jogos n50', 'Carreira - VNF', '4765-070', '0', '2010-08-31', '', '', 'ARTAVE', 9, '', '', 8, 0, 0, 257, NULL, 'Lucia de Jesus Rocha Lopes', '+351912942692', '', '', '', 0, 1, NULL),
(66, 0, 20.00, 0.00, 'CAROLINA CARDOSO DE MOURA BRANDÃO FERREIRA', 'Rua Municipal de Minhava n418', 'Vila Real', '', '0', '2010-06-27', '', '+351912835629', 'Escola Básica do Ave', 9, '', '', 4, 0, 0, 253, NULL, 'Paula Cristina Cardoso Brandão', '+351912321242', '', '', '', 0, 1, NULL),
(65, 0, 40.00, 0.00, 'CAMILA SILVA DIAS', 'R. D. Américo Bispo de Lamego, n980', 'Vila das Aves', '4795-842', '274760002', '2011-03-29', '', '', '', 8, '', '', 8, 0, 0, 229, NULL, 'Carla Silva', '+351914756128', '', '', '', 0, 1, NULL),
(64, 0, 0.00, 40.00, 'BRUNA MARIA NICOLAU ALMEIDA', 'Urb. Vila Verde Lote 11', 'Bairro - VNF', '4765-065', '0', '2010-11-02', '', '+351932575153', '', 9, '', 'C', 0, 8, 0, NULL, 277, 'Elsa Maria Almeida paiva', '+351932608094', '', '', '', 0, 1, NULL),
(62, 0, 60.00, 0.00, 'ANTHONY COSTA PINHEIRO', 'Rua Salgado 108', 'Oliveira S. Mateus - VNF', '4765-757', '0', '2010-07-16', '', '', '', 9, '', '', 12, 0, 0, 261, NULL, 'Sofia da Costa', '+351961819591', '', '', '1 x por semana', 0, 1, NULL),
(61, 0, 40.00, 0.00, 'ANA RITA SILVA COSTA', 'Rua Pe. Luís Maria Ol. Nascimento n220', 'Bente VNF', '4770-060', '0', '2010-08-14', '', '+351912049298', 'Escola Padre Benjamim Salgado', 9, '', '', 8, 0, 0, 257, NULL, 'Cecília Silva Cruz', '+351918910007', '', '', '', 0, 1, NULL),
(60, 0, 40.00, 0.00, 'ANA RITA DA SILVA BARROS', 'Av. Das Lameiras', 'Delães', '4765-618', '0', '2010-01-16', '', '+351912821286', 'Escola Básica 2/3 das Aves', 9, '', '', 8, 0, 0, 257, NULL, 'Isabel Rodrigues da Silva', '+351914736567', '', '', '2 x por semana', 0, 1, NULL),
(52, 0, 60.00, 0.00, 'ANA INÊS FERREIRA COUTO', 'Rua da Granja, n56', 'Carreira - VNF', '4765-075', '276812301', '2010-12-18', '', '', 'Básica Vila das Aves', 9, '', '', 12, 0, 0, 261, NULL, 'Bernadete Ferreira', '+351919872717', '', '', '', 0, 1, NULL),
(63, 0, 60.00, 0.00, 'BEATRIZ GONÇALVES SOUSA', 'Trav. José Dias Oliveira, n27', 'Mogege - VNF', '4770-350', '0', '2004-12-27', '', '+351912860138', 'Secundária Pe. Benjamim Salgado', 9, '', '', 12, 0, 0, 261, NULL, 'Elisabete Carvalho', '+351915897387', 'Miguel Sousa', '+351919167714', '', 0, 1, NULL),
(50, 0, 40.00, 0.00, 'ALICE BARBOSA BAPTISTA', 'Rua do regalo, Bloco B 2D', 'Bairro - VNF', '4765-068', '0', '2010-05-25', '', '+351913664920', 'Escola Básica da Ponte', 9, '', '', 8, 0, 0, 257, NULL, '', '', 'Ricardo da Silva Baptista', '+351919730329', '', 0, 1, NULL),
(53, 0, 60.00, 0.00, 'AFONSO OLIVEIRA TEIXEIRA', 'Rua de São Pedro, n12', 'Lordelo - GMR', '4815-176', '0', '2001-09-20', '', '+351912860138', 'Escola do Carreiro', 3, '', '', 12, 0, 0, 93, NULL, 'Anabela Araújo Oliveira', '+351918971811', 'Marco', '', '', 0, 1, NULL),
(49, 0, 60.00, 0.00, 'AFONSO RODRIGUES SILVA', 'Estrada Nacional 204-5, n 2011 2Esq', 'Landim - VNF', '4770-336', '280040563', '2012-08-30', '', '+351913197182', 'Escala Básica de Ave', 7, '', '', 12, 0, 0, 205, NULL, 'Alice Manuel Bezerra', '+351916834978', '', '', '', 0, 1, NULL),
(75, 0, 40.00, 0.00, 'JOSÉ PEDRO FRANCISCO CARNEIRO', 'R. de S. Tiago n15', 'Lordelo - GMR', '', '0', '2010-08-26', '', '', '', 9, '', '', 8, 0, 0, 257, NULL, 'Luísa da Conceição da Cunha Pereira de Lima Francisco', '+351919190805', '', '', '', 0, 1, NULL),
(76, 0, 40.00, 0.00, 'LARA SOFIA FERREIRA COELHO', 'Rua Santa Clara n 138', 'Vila das Aves', '4795-112', '271666838', '2011-03-26', '', '', '', 8, '', '', 8, 0, 0, 229, NULL, 'Helena Isabel Pereira Gomes Ferreira', '+351939330876', '', '', '', 0, 1, NULL),
(77, 0, 40.00, 0.00, 'LAURA DA SILVA MARTINS', 'Rua Gil Vicente n 1', 'Vila das Aves', '4795-299', '0', '2010-02-15', '', '+351912837629', 'Agrupamento de Escolas de São Martinho', 9, '', '', 8, 0, 0, 257, NULL, 'Aurora Manuela Martins da Silva', '+351916569283', '', '', '', 0, 1, NULL),
(78, 0, 60.00, 0.00, 'LEONOR GOUVEIA DE ARAÚJO', 'Rua Pedro Dioga, n 15', 'Vila das Aves', '4795-', '0', '2010-12-16', '', '+351912031139', 'Escala Básica de Ave', 9, '', 'A', 12, 0, 0, 261, NULL, 'Maria Armanda Gouveia Sousa Reis', '+351913996001', 'D. Alice - 910 556 587', '+351910556587', '12h/mês', 0, 1, NULL),
(79, 0, 40.00, 0.00, 'LEONOR RIBEIRO SANTOS', 'Estrada Nacional 204-5, n1257', 'Carreira - VNF', '4765-074', '0', '2011-07-13', 'leonor.santos.5611@aeterrsave.net', '+351934633617', '', 8, '', '', 8, 0, 0, 229, NULL, 'Liliana Maria Marques Ribeiro', '+351915460505', '', '', '2 x por semana', 0, 1, NULL),
(80, 0, 20.00, 0.00, 'MARIANA BARBOSA DA COSTA', 'Rua Nova n80, 1 Esq. Trás', 'St Maria Oliveira - VNF', '4765-334', '0', '2010-04-03', '', '+351926863804', '', 9, '', 'A', 4, 0, 0, 253, NULL, 'Carla Andreia Castro Barbosa', '+351936080586', '', '', '', 0, 1, NULL),
(81, 0, 60.00, 20.00, 'MATILDE LOPES SILVA', 'Travessa da Aves, Lote 2', 'Vila das Aves', '4785-025', '0', '2011-10-29', '', '+351912076290', 'Didáxis', 8, '', '', 12, 4, 0, 233, NULL, 'Anabela Carneiro Lopes', '+351911053161', '', '', '', 0, 1, NULL),
(82, 0, 60.00, 0.00, 'SANTIAGO DA CUNHA SILVA', 'Rua General Humberto Delgado', 'Vila das Aves', '4795-072', '0', '2011-08-24', '', '+351912520371', 'Escola do Ave', 8, '', '', 12, 0, 0, 233, NULL, 'Cidália Manuela da Cunha Oliveira', '+351915495472', '', '', '', 0, 1, NULL),
(83, 0, 0.00, 0.00, 'TIAGO CAMPOS FERNANDES', 'Rua do Agrelo, n60F 1ª Esq', 'S. Matinho do Campo', '4795-452', '0', '2010-02-03', '', '+351934636657', 'Escola Secundária D. DINIS', 9, '', '', 0, 0, 0, NULL, NULL, 'Cláudia Goreti Pereira Campos', '+351916300169', '', '', '2h/semana', 0, 1, NULL),
(150, 0, 0.00, 0.00, 'FILIPE MANUEL ALVES PACHECO', 'Urb. Crapts&Crapts, Casa 4', 'Bairro - VNF', '4765-680', '0', '2005-03-02', 'a16051@aedah.pt', '+351927542405', '', 0, '', '', 0, 0, 0, NULL, NULL, 'Emilia Alves - emilia.cristina@sapo.pt', '+351914411513', '', '', '', 0, 1, NULL),
(85, 0, 40.00, 0.00, 'ANA LUÍSA RIBEIRO FERREIRA', 'Rua Aldeia Nova n211', 'Carreira VNF', '4765-071', '0', '2009-12-29', '', '+351960387958', '', 10, '', '', 8, 0, 0, 285, NULL, 'Elisa Ângela Morais Ribeiro', '+351916967138', '', '', '', 0, 1, NULL),
(86, 0, 0.00, 0.00, 'BRUNA FRANCISCA PINTO RIBEIRO', 'Rua General Humberto Delgado, n244', 'Oliveira S. Mateus -VNF', '4795-072', '0', '2008-12-02', '', '+351919904860', 'Escola Secundária D. Afonso Henriques', 11, '', 'H2', 0, 0, 0, NULL, NULL, 'Maria do Céu Moreira Pinto', '+351917997477', '', '', '', 0, 0, NULL),
(87, 0, 20.00, 0.00, 'BRUNA SOFIA PACHECO QUEIRÓS', 'Rua da Bela Vista, n23', 'Vila das Aves', '4795-039', '0', '2009-02-15', '', '+351961039238', 'Agrupamento de Escolas Virgínia Moura', 10, '', 'D', 4, 0, 0, 281, NULL, 'Carla Sofia de Almeda Pacheco', '+351966264259', '938 446 729', '+351938446729', '', 0, 1, NULL),
(88, 0, 40.00, 20.00, 'CAUÃ SANTANA BAHIA ONOFRE', 'Rua Louvazim, 409 2 Dt Frente', 'Vila das Aves', '4795-081', '0', '2006-09-07', '', '', 'D. H. A.', 12, 'Ciências e Tecnologias', '', 8, 4, 0, 321, NULL, 'Andreia Onofre', '+351914246393', '914 246 404', '+351914246404', '', 0, 1, NULL),
(89, 0, 0.00, 0.00, 'DIANA BARRA E ANTUNES', 'Av. D. João IV, Edifício Lei Fu 15, 9G', 'Macau', '4785-025', '0', '2008-09-15', 'joana15barra@gmail.com (mãe)', '+853 62758413', 'Escola Portuguesa de Macau', 11, '', '', 0, 0, 0, NULL, NULL, 'Joana Gabriela Ferreira Barra', '+853 62358449', '', '', '', 0, 0, NULL),
(90, 0, 20.00, 0.00, 'DIEGO CIFELLI', 'Travessa da Carreira, n133', 'Vila das Aves', '4795-', '0', '2008-03-12', '', '32', '', 11, '', '', 4, 0, 0, 301, NULL, 'Vanessa Andreia Gomes Lemos', '+351913198140', '', '', '', 0, 1, NULL),
(91, 0, 40.00, 0.00, 'ELISABETE FILIPA PINTO', 'Rua do Cardal, n179 R/chão', 'Bente - VNF', '4770-060', '0', '2009-04-25', '', '', '', 10, '', '', 8, 0, 0, 285, NULL, 'Ana Rita Moreira Barbosa Pinto Martins', '+351910011016', '', '', '', 0, 1, NULL),
(93, 0, 20.00, 0.00, 'FRANCISCA BARROSO PEIXOTO', 'Rua Zeca da Costa, n119', 'Roriz - S. Tirso', '4795-378', '0', '2004-12-27', '', '+351912451499', 'D. A. H.', 11, 'Ciências e Tecnologias', '', 4, 0, 0, 301, NULL, '', '', '961 040 772', '+351961040772', '', 0, 1, NULL),
(94, 0, 20.00, 0.00, 'FRANCISCA PIMENTA COELHO', 'Rua Armindo Coielho Cardoso, n67', 'St Tirso - Negrelos', '4795-574', '273607588', '2009-02-20', '', '+351938155323', 'Colégio da Trofa', 10, 'C. T.', '', 4, 0, 0, 281, NULL, 'Alcina Pimenta', '+351965475445', '', '', '1 x por semana', 0, 1, NULL),
(95, 0, 20.00, 20.00, 'GUILHERME MONTEIRO COSTA', 'Calçada Jaime Gomes Guimarães, n111', 'Vila Nova do Campo', '4795-516', '0', '2008-12-29', '', '+351961161546', '', 10, '', '', 4, 4, 0, 281, NULL, 'Daniela Monteiro', '+351918177233', '', '', '', 0, 1, NULL),
(96, 0, 20.00, 0.00, 'GUILHERME PIMENTA NEVES', 'Rua António Maria Gomes n580', 'Roriz', '4795-268', '0', '2008-01-09', '', '+351965034514', '', 11, '', '', 4, 0, 0, 301, NULL, 'Sónia Paula Coelho Pimenta', '+351912860246', '', '', '', 0, 1, NULL),
(97, 0, 20.00, 0.00, 'HELENA ISABEL GOMES RIBEIRO', 'Rua da Ribes, n175', 'Sta. Maria Oliveira - VNF', '4765-332', '0', '2009-03-04', '', '+351913203409', 'Escola Secundária D. Afonso Henriques', 10, '', 'A', 4, 0, 0, 281, NULL, 'Cassilda Maria Antunes Gomes', '+351914148453', '', '', '', 0, 1, NULL),
(98, 0, 20.00, 0.00, 'INÊS CASTRO OLIVEIRA', 'Rua Luís de Camões, 89', 'Vila das Aves', '4795-079', '0', '2005-03-16', '', '+351961574038', 'D.Afonso Henriques', 12, '', '', 4, 0, 0, 317, NULL, 'Carminda Castro', '+351968519494', '', '', '', 0, 1, NULL),
(99, 0, 40.00, 0.00, 'INÊS FRANCISCA MACHADO GUEDES', 'Rua Antero de Quental, n93', 'Vila das Aves', '4795-033', '0', '2008-07-09', '', '+351932044120', 'D. Afonso Henriques', 11, '', 'A', 8, 0, 0, 305, NULL, '', '', '965 791 778', '+351965791778', '', 0, 1, NULL),
(100, 0, 20.00, 0.00, 'JOANA MIGUEL FERREIRA SOARES', 'Lr. Francisco M Guimarães, n80 R/C Dt Tr', 'Vila das Aves', '4795-016', '272153648', '2009-01-27', '', '+351912092189', '', 10, '', '', 4, 0, 0, 281, NULL, 'Oriana Manuel Cunha Ferreira', '+351933190540', '', '', '', 0, 1, NULL),
(101, 0, 20.00, 0.00, 'JOANA RODRIGUES FERREIRA', 'Rua José Moreira Araújo, n74 R/CH Dto.', 'Vila das Aves', '4795-081', '265335159', '2006-11-20', '', '+351937257844', 'D. A. H.', 11, 'C. T.', 'C', 4, 0, 0, 301, NULL, 'Alexandrina Manuela Coelho Rodrigues', '+351962266036', '', '', '1 x por semana', 0, 1, NULL),
(102, 0, 0.00, 0.00, 'JOAQUIM RAFAEL DE SOUSA NOGUEIRA', 'Rua Pedro Alves Cabral n20', 'Rebodões', '4795-222', '0', '1988-06-06', '', '+351919705824', '', 21, '', '', 0, 0, 0, NULL, NULL, '', '', '', '', '', 0, 0, NULL),
(103, 0, 20.00, 0.00, 'LARA BEATRIZ PEREIRA DE SOUSA', 'Rua de  Carrezedo, n210', 'Delães - VNF', '4765-605', '0', '2009-04-06', '', '+351915525173', 'Escola D. Afonso Henriques', 10, 'Ciências Socioeconómicas', 'SE', 4, 0, 0, 281, NULL, 'Ângela Cristiana Machado Cunha Pereira', '+351914712379', '', '', '1 x por semana', 0, 1, NULL),
(147, 0, 40.00, 0.00, 'MARIA INÊS LEAL MAGALHÃES', 'Trav. Bernardino Gomes Ferreira', 'Vila das Aves', '4795-055', '0', '2008-10-08', '', '+351918524967', '', 10, '', '', 8, 0, 0, 285, NULL, 'Paula Cristina da Costa Leal Magalhães', '+351917878947', '', '', '', 0, 1, NULL),
(105, 0, 20.00, 20.00, 'LEONOR LOPES SILVA', 'Travessa da Aves, Lote 2', 'Vila das Aves', '4785-025', '0', '2008-07-16', '', '+351919952545', 'Didáxis', 11, 'Ciências e Tecnologias', '', 4, 4, 0, 301, NULL, 'Anabela Carneiro Lopes', '+351911053161', '', '', '', 0, 1, NULL),
(106, 0, 20.00, 0.00, 'LEONOR MAGALHÃES GONÇALVES', 'Lrg. Dr. Braga da Cruz 135, 1 Esq', 'Vila das Aves', '4795-015', '0', '2008-05-13', '', '+351935484335', 'D. Dinis', 11, 'Humanidades', 'D', 4, 0, 0, 301, NULL, 'Leonardo Gonçalves', '+351922123442', '', '', '', 0, 1, NULL),
(107, 0, 40.00, 0.00, 'LÍLIA OLIVEIRA FERREIRA', 'Rua St. Clara, 383 R/Ch', 'Vila das Aves', '4795-112', '0', '2009-03-21', '', '+351961046691', '', 10, '', 'C', 8, 0, 0, 285, NULL, 'Margarida da Conceição Ferreira Oliveira', '+351938275438', '', '', '', 0, 1, NULL),
(108, 0, 20.00, 0.00, 'LUÍSA ISABEL MARTINS MOURA', 'Av. De Poldrães 231', 'Vila das Aves', '4795-006', '0', '2009-04-01', '', '+351938073260', '', 10, '', 'AV', 4, 0, 0, 281, NULL, 'Isabel Moura', '+351932942448', '', '', '', 0, 1, NULL),
(109, 0, 20.00, 0.00, 'MARGARIDA FILIPA FRAGA MONTEIRO', 'Rua Camilo Castelo Branco, n825', 'Vila das Aves', '4793-045', '0', '2009-11-24', '', '+351915248075', 'D. Afonso Henriques', 10, '', 'Artes', 4, 0, 0, 281, NULL, 'Cátia Fraga', '+351916841152', '', '', '1 x por semana', 0, 1, NULL),
(110, 0, 20.00, 0.00, 'MARIA CLARA FERREIRA DE FARIA', 'Rua Professor Jeónimo de Castro, n26', 'Vila das Aves', '4785-000', '0', '2009-01-20', '', '+351912291806', 'D. Afonso Henriques', 10, '', 'SE', 4, 0, 0, 281, NULL, 'Luisa Ferreira', '+351913385760', '', '', '', 0, 1, NULL),
(111, 0, 20.00, 0.00, 'MARIA HERDEIRO CARDOSO CARVALHO GUEDES', 'Largo Dr Braga da Cruz, 48 3 Esq', 'Vila das Aves', '4795-015', '0', '2008-11-08', '', '+351911530537', 'Escola Secundário Afonso Henriques', 10, 'Ciências e Tecnologias', '', 4, 0, 0, 281, NULL, 'Patrícia Cardoso Guedes', '+351936052285', '', '', '', 0, 1, NULL),
(112, 0, 0.00, 0.00, 'MARIA LUÍS MACHADO FERREIRA', 'Rua 25 de Abril, 2 Dt', 'Vila das Aves', '4795-023', '0', '2009-05-22', '', '+351912193746', 'Escola D. Afonso Henriques', 10, 'Humanidades', 'E', 0, 0, 0, NULL, NULL, 'Joana Machado', '+351918419650', '', '', '2 x por semana', 0, 0, NULL),
(113, 0, 40.00, 0.00, 'MARIANA LOPES SANTOS', 'Rua Parque de Jogos, n42', 'Carreira VNF', '4765-071', '0', '2009-06-28', '', '', 'EB de Pedome', 10, '', '', 8, 0, 0, 285, NULL, 'Silvia Maria Lopes Rocha', '+351916686511', '', '', '', 0, 1, NULL),
(115, 0, 40.00, 0.00, 'PEDRO LUÍS FERREIRA MENDES', 'Rua do Enxudres, n79', 'Lordelo - GMR', '4815-165', '0', '2008-09-01', '', '+351916686511', 'D. A. H.', 11, 'Ciências e Tecnologias', '', 8, 0, 0, 305, NULL, 'Carla Susana Ferreira da Cunha', '+351938508097', '', '', '', 0, 1, NULL),
(116, 0, 40.00, 0.00, 'RAFAEL MACHADO JOÃO LIMA', 'Rua de Santosinho, 127', 'Rebordões', '4795-231', '0', '2007-03-02', '', '+351930555374', '', 11, '', '', 8, 0, 0, 305, NULL, 'Andreia Presa Ferreira João', '+351912023336', '', '', '', 0, 1, NULL),
(117, 0, 20.00, 0.00, 'RODRIGO CARDOSO VIEIRA', 'Estrada Nacional 105, n2', 'Lordelo- GMR', '4815-135', '0', '2008-09-15', '', '+351962044446', 'Escola Secundária Vila das Aves', 11, '', 'SE', 4, 0, 0, 301, NULL, 'Sandra Filipa Gomes Cardoso', '+351918980555', '', '', '', 0, 1, NULL),
(118, 0, 40.00, 0.00, 'RODRIGO SÁ PIMENTA', 'Rua Associação do Outeiro, n 263', 'Carreira - VNF', '4765-078', '0', '2009-09-27', '', '+351938565659', '', 10, '', 'B', 8, 0, 0, 285, NULL, 'Rosário de Fátima Sá Ribeiro', '+351918951934', '916 049 948', '+351916049948', '', 0, 1, NULL),
(119, 0, 40.00, 0.00, 'SANTIAGO FERREIRA DE SOUSA', 'Rua Igreja Sanfins, 49', 'Bairro - VNF', '4765-040', '0', '2009-12-06', '', '+351919893815', 'Escola Secundária Vila das Aves', 10, '', 'B', 8, 0, 0, 285, NULL, 'Laurentina de Jesus da Silva Ferreira', '+351914675562', '', '', '', 0, 1, NULL),
(120, 0, 40.00, 0.00, 'SARA DA COSTA ESTEVES', 'Rua São Bento 153', 'Lordelo - GMR', '4815-207', '0', '2007-06-29', '', '+351933248355', 'Secundária Dom Dinis', 12, '', '', 8, 0, 0, 321, NULL, 'Lucilia Raquel Pereira da Costa', '+351936772231', '', '', '', 0, 1, NULL),
(121, 0, 0.00, 20.00, 'SORAIA LEAL FONSECA', 'Ru Quinta da Costa 10', 'Roriz - St. Tirso', '4795-327', '0', '2008-07-01', '', '+351911163391', 'Escola Profissional Oficina', 11, 'Desenho digital 3D', '', 0, 4, 0, NULL, 313, 'Ana Bela Leal', '+351967855631', '', '', '', 0, 1, NULL),
(122, 0, 40.00, 0.00, 'SORAIA MOUTINHO OLIVEIRA', 'Rua S. André 202', 'Vila das Aves', '4795-113', '0', '2008-06-21', '', '+351926693330', '', 11, '', '', 8, 0, 0, 305, NULL, 'Soraia Oliveira', '+351936073904', '', '', '', 0, 1, NULL),
(123, 0, 20.00, 0.00, 'TIAGO ANTÓNIO MACHADO MARTINS', 'Av. Conde Vizela, n36', 'Vila das Aves', '4795-004', '0', '2007-05-17', '', '+351960071196', 'Escola D. Afonso Henriques', 12, 'C. T.', 'B', 4, 0, 0, 317, NULL, 'Cidália Machado', '+351968491840', '', '', '1 x por semana', 0, 1, NULL),
(124, 0, 60.00, 0.00, 'TOMÁS EDUARDO BESSA SOUSA', 'Rua dos Louros n10', 'Lordelo', '4815-195', '0', '2009-04-28', '', '+351961156936', 'S. Tomé de Negrelos', 10, '', '', 12, 0, 0, 289, NULL, 'Luísa Maria Caneiro Bessa', '+351965663963', '', '', '', 0, 1, NULL),
(125, 0, 20.00, 0.00, 'VASCO MONTEIRO MARTINS', 'Rua da Indústria, n 145', 'Rebordões - St. Tirso', '4795-207', '0', '2004-12-27', '', '+351966462578', 'Escola Secundária D. Afonso Henriques', 10, '', 'A', 4, 0, 0, 281, NULL, 'Andreia Carla Dias Monteiro', '+351913418845', '914 099 595', '+351914099595', '', 0, 1, NULL),
(126, 0, 40.00, 0.00, 'VITÓRIA DE MACEDO CAMPOS', 'Rua Monsenhor José Ferreira, n95', 'Vila das Aves', '4795-088', '260282170', '2007-04-12', '', '+351930527597', 'Secundária D. Afonso Henriques', 12, 'Ciências e Tecnologias', '', 8, 0, 0, 321, NULL, 'Fernanda Liliana Silva Macedo', '+351964895017', '', '', '', 0, 1, NULL),
(127, 0, 0.00, 40.00, 'VICENTE FERREIRA DA SILVA', 'Lar Dr. Braga da Cruz, n94, 4Dt', 'Vila das Aves', '4795-015', '274051346', '2014-06-17', '', '', '', 5, '', '', 0, 8, 0, NULL, 181, 'Mónica Maria Ferreira   (Avó Joaquina - 913 822 703)', '+351938186968', '', '+351938186967', '', 0, 1, NULL),
(128, 0, 40.00, 0.00, 'ANA CAROLINA SOARES PACHECO', 'Rua de Sobrado, 186', 'Vila das Aves', '4795-121', '279765002', '2012-10-25', '', '', '', 7, '', '', 8, 0, 0, 201, NULL, '', '+351252872652', 'Carlos Rafael Alves Pacheco', '', '', 0, 1, NULL),
(129, 0, 40.00, 20.00, 'DUARTE TEIXEIRA RIBEIRO', 'Av. Comendador Ab. F. Oliv. N 511 Dto Norte', 'São Martinho do Campo', '4795-443', '0', '2009-10-12', '', '', '', 9, '', '', 8, 4, 0, 257, NULL, 'Daniela Marina Martins Teixeira', '+351913592354', '', '', '', 0, 1, NULL),
(130, 0, 60.00, 0.00, 'ERIC PINHEIRO RIBEIRO', 'Alameda João Paulo II, n 74', 'Vila das Aves', '4795-155', '277714486', '2011-11-16', '', '', '', 8, '', '', 12, 0, 0, 233, NULL, 'Marisa Pinheiro', '+351917924427', '', '', '', 0, 1, NULL),
(131, 0, 40.00, 0.00, 'LEONOR TEIXEIRA RIBEIRO', 'Av. Comendador Ab. F. Oliv. N 511 Dto Norte', 'São Martinho do Campo', '4795-443', '0', '2011-12-12', '', '', '', 8, '', '', 8, 0, 0, 229, NULL, 'Daniela Marina Martins Teixeira', '+351913592354', '', '', '', 0, 1, NULL),
(132, 0, 60.00, 0.00, 'LIA PINHEIRO RIBEIRO', 'Alameda João Paulo II, n 74', 'Vila das Aves', '4795-155', '277714486', '2011-11-16', '', '', '', 8, '', '', 12, 0, 0, 233, NULL, 'Marisa Pinheiro', '+351917924427', '', '', '', 0, 1, NULL),
(133, 0, 40.00, 0.00, 'MARIA BEATRIZ DA COSTA BARROSO', 'Rua da Aldeia Nova, 351', 'Roriz- STS', '4765-044', '0', '2010-08-04', '', '+351910825865', 'S. MARTINHO DO CAMPO', 9, '', 'C', 8, 0, 0, 257, NULL, 'Sandra Marina Ferreira da Costa', '+351913184657', 'Pedro', '', '2h /semana', 0, 1, NULL),
(134, 0, 60.00, 0.00, 'MARTIM MIGUEL CRUZ OLIVEIRA', 'Rua do Fojo, n 148', 'Carreira - VNF', '4765-076', '280511728', '2012-02-09', 'martimmiguel2001@gmail.com', '+351912929716', 'EB Vila das Aves', 7, '', '', 12, 0, 0, 205, NULL, 'Marta da Conceição Coutinho Cruz', '+351910124180', '', '', '', 0, 1, NULL),
(135, 0, 40.00, 0.00, 'RODRIGO MIGUEL SILVA MATOS', 'Alameda Arnauldo Gama n121, 3 Esq', 'Vila das Aves', '4795-001', '271666838', '2010-01-21', '', '', 'EB Vila das Aves', 9, '', '', 8, 0, 0, 257, NULL, 'Paula Marina Torres Silva', '+351918394159', 'Ricardo', '+351919346871', '', 0, 1, NULL),
(136, 0, 60.00, 0.00, 'RÚBEN FILIPE SILVA MONTEIRO', 'Rua do Casino, n 301', 'Bairro - VNF', '4765-063', '0', '2010-12-07', '', '', '', 9, '', '', 12, 0, 0, 261, NULL, 'Silvia', '+351918169689', 'Hélio Filipe Nogueira Monteiro', '+351916919586', '', 0, 1, NULL),
(137, 0, 40.00, 0.00, 'TIAGO GABRIEL CASTRO DA SILVA', 'Rua das Lages, n23', 'Bairro - VNF', '4765-044', '0', '2010-03-15', '', '', '', 9, '', '', 8, 0, 0, 257, NULL, 'Marisa Isabel Barbosa Castro Silva', '+351916030029', 'Pedro', '+351917103375', '', 0, 1, NULL),
(138, 0, 80.00, 0.00, 'AFONSO AZEVEDO FERREIRA', 'Rua de St. Rita n 85', 'Cense - Vila das Aves', '', '0', '2009-02-26', '', '', '', 10, '', '', 16, 0, 0, 293, NULL, 'Ângela Cristina Azevedo Pereira', '+351936545223', '', '', '', 0, 1, NULL),
(139, 0, 40.00, 0.00, 'FRANCISCO MARTINS PIMENTA DA SILVA PEREIRA', 'Praça do Bom Nome Ent.2, 1 Esq.', 'Vila das Aves', '4795-025', '0', '2007-10-02', '', '+351937790784', 'Secundária Afonso Henriques', 12, '', '', 8, 0, 0, 321, NULL, '', '', 'Manuel Adérito da Silva Pereira', '+351965057633', '', 0, 1, NULL),
(140, 0, 20.00, 0.00, 'GONÇALO DINIS FERREIRA FREITAS', 'Rua Quinta da Vila n95, Roriz', 'Santo Tirso', '4795-503', '272153648', '2009-01-27', '', '', '', 10, '', '', 4, 0, 0, 281, NULL, 'Adriana Correia Ferreira Freitas', '+351916646649', '', '', '', 0, 1, NULL),
(141, 0, 20.00, 20.00, 'JOSÉ ANTÓNIO DIAS RASO', 'Rua Cônsul Aristides de Sousa Mendes, n 22', 'Lordelo - GMR', '4815-116', '0', '2008-08-19', 'zeraso3232@gmail.com', '+351936757374', 'Tomás Pelayo', 10, '', '', 4, 4, 0, 281, NULL, 'Darcília Isabel Dias Gomes', '+351962580494', '', '', '', 0, 1, NULL),
(142, 0, 40.00, 0.00, 'LEONOR FERREIRA DA SILVA', 'Lar Dr. Braga da Cruz, n94, 4Dt', 'Vila das Aves', '4795-015', '274051346', '2008-03-01', '', '+351938167704', 'EB 2,3 Bom Nome', 11, '', '', 8, 0, 0, 305, NULL, 'Mónica Maria Ferreira', '+351938186968', '', '+351938186967', '', 0, 1, NULL),
(143, 0, 20.00, 0.00, 'RODRIGO SANTOS SILVA', 'Rua das Ínsuas, n457', 'Vilarinho -Santo Tirso', '4795-787', '0', '2007-12-27', '', '+351961468207', 'D. Afonso Heniques', 12, '', '', 4, 0, 0, 317, NULL, 'Leonor Silva', '+351933337728', '', '', '', 0, 1, NULL),
(151, 0, 20.00, 0.00, 'GUSTAVO RODRIGUES SALGADO', 'Av. Monte dos Saltos, nº45', 'Sequeirô - St. Tirso', '4780-641', '275127109', '2008-02-25', '', '+351969608175', 'Escola Báica de Ave', 9, '', 'F', 4, 0, 0, 253, NULL, 'Paula Francisca Couto Rodrigues', '+351932902925', '', '', '', 0, 1, NULL),
(152, 0, 20.00, 0.00, 'PEDRO DINIS ALVES PACHECO', 'Urb. Crapts&Crapts, Casa 4', 'Bairro - VNF', '4765-680', '0', '2008-10-21', '', '+351964804787', '', 11, '', '', 4, 0, 0, 301, NULL, 'Emilia Alves - emilia.cristina@sapo.pt', '+351914411513', '', '', '', 0, 1, NULL),
(155, 0, 0.00, 0.00, 'Maria Eduarda Pontes Dias', 'Rua Luís de Camões, 206', '', '4765-255', '0', '2010-05-03', '', '+351913567702', 'EB Aves ', 9, '', 'C', 8, 0, 0, 257, NULL, 'Carolina Pontes', '+351914940758', '', '', '', 0, 1, '0000-00-00');

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
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `alunos_pagamentos`
--

INSERT INTO `alunos_pagamentos` (`id`, `idAluno`, `mensalidade`, `idMetodo`, `observacao`, `idProfessor`, `estado`, `created`, `pagoEm`) VALUES
(1, 1, 100, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(2, 4, 60, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(3, 5, 60, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(4, 6, 60, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(5, 7, 60, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(6, 8, 60, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(7, 9, 60, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(8, 10, 60, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(9, 11, 50, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(10, 12, 60, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(11, 14, 40, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(12, 15, 0, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(13, 16, 40, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(14, 17, 20, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(15, 18, 40, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(16, 20, 60, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(17, 21, 75, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(18, 22, 65, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(19, 23, 65, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(20, 24, 60, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(21, 25, 60, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(22, 74, 65, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(23, 73, 0, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(24, 72, 80, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(25, 71, 80, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(26, 70, 90, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(27, 69, 35, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(28, 68, 80, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(29, 67, 65, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(30, 66, 35, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(31, 65, 65, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(32, 64, 15, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(33, 62, 80, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(34, 61, 65, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(35, 60, 65, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(36, 52, 80, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(37, 63, 80, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(38, 50, 65, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(39, 53, 50, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(40, 49, 80, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(41, 75, 65, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(42, 76, 65, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(43, 77, 65, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(44, 78, 80, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(45, 79, 65, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(46, 80, 35, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(47, 81, 80, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(48, 82, 80, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(49, 83, 0, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(50, 150, 0, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(51, 85, 80, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(52, 87, 40, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(53, 88, 100, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(54, 90, 45, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(55, 91, 80, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(56, 93, 45, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(57, 94, 40, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(58, 95, 40, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(59, 96, 45, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(60, 97, 40, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(61, 98, 50, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(62, 99, 90, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(63, 100, 40, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(64, 101, 45, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(65, 103, 40, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(66, 147, 80, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(67, 105, 45, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(68, 106, 45, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(69, 107, 80, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(70, 108, 40, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(71, 109, 40, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(72, 110, 40, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(73, 111, 40, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(74, 113, 80, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(75, 115, 90, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(76, 116, 90, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(77, 117, 45, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(78, 118, 80, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(79, 119, 80, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(80, 120, 100, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(81, 121, 72, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(82, 122, 90, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(83, 123, 50, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(84, 124, 120, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(85, 125, 40, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(86, 126, 100, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(87, 127, 15, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(88, 128, 65, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(89, 129, 65, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(90, 130, 80, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(91, 131, 65, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(92, 132, 80, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(93, 133, 65, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(94, 134, 80, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(95, 135, 65, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(96, 136, 80, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(97, 137, 65, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(98, 138, 160, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(99, 139, 100, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(100, 140, 40, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(101, 141, 40, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(102, 142, 90, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(103, 143, 50, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(104, 151, 35, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(105, 152, 45, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00'),
(106, 154, 50, 0, '', 0, 'Em atraso', '2025-03-01 04:42:10', '0000-00-00 00:00:00');

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
) ENGINE=MyISAM AUTO_INCREMENT=107 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `alunos_recibo`
--

INSERT INTO `alunos_recibo` (`id`, `idAluno`, `anoAluno`, `packGrupo`, `horasRealizadasGrupo`, `horasBalancoGrupo`, `packIndividual`, `horasRealizadasIndividual`, `horasBalancoIndividual`, `mensalidade`, `ano`, `mes`) VALUES
(1, 1, 12, 8, 0, 40, 0, 0, 0, 100, 2025, 3),
(2, 4, 4, 16, 0, 80, 0, 0, 0, 60, 2025, 3),
(3, 5, 3, 16, 0, 80, 0, 0, 0, 60, 2025, 3),
(4, 6, 4, 16, 0, 80, 4, 0, 20, 60, 2025, 3),
(5, 7, 4, 16, 0, 80, 0, 0, 0, 60, 2025, 3),
(6, 8, 3, 16, 0, 80, 0, 0, 0, 60, 2025, 3),
(7, 9, 3, 16, 0, 80, 0, 0, 0, 60, 2025, 3),
(8, 10, 2, 16, 0, 80, 0, 0, 0, 60, 2025, 3),
(9, 11, 3, 12, 0, 60, 0, 0, 0, 50, 2025, 3),
(10, 12, 3, 16, 0, 80, 0, 0, 0, 60, 2025, 3),
(11, 14, 3, 8, 0, 40, 0, 0, 0, 40, 2025, 3),
(12, 15, 1, 6, 0, 30, 0, 0, 0, 0, 2025, 3),
(13, 16, 2, 8, 0, 40, 0, 0, 0, 40, 2025, 3),
(14, 17, 1, 4, 0, 20, 4, 0, 20, 20, 2025, 3),
(15, 18, 1, 8, 0, 40, 0, 0, 0, 40, 2025, 3),
(16, 20, 5, 12, 0, 60, 0, 0, 0, 60, 2025, 3),
(17, 21, 6, 12, 0, 60, 0, 0, 0, 75, 2025, 3),
(18, 22, 6, 12, 0, 60, 0, 0, 0, 65, 2025, 3),
(19, 23, 6, 12, 0, 60, 0, 0, 0, 65, 2025, 3),
(20, 24, 5, 12, 0, 60, 0, 0, 0, 60, 2025, 3),
(21, 25, 5, 12, 0, 60, 0, 0, 0, 60, 2025, 3),
(22, 74, 9, 8, 0, 40, 0, 0, 0, 65, 2025, 3),
(23, 73, 10, 0, 0, 0, 0, 0, 0, 0, 2025, 3),
(24, 72, 7, 12, 0, 60, 0, 0, 0, 80, 2025, 3),
(25, 71, 7, 12, 0, 60, 0, 0, 0, 80, 2025, 3),
(26, 70, 11, 8, 0, 40, 0, 0, 0, 90, 2025, 3),
(27, 69, 8, 4, 0, 20, 0, 0, 0, 35, 2025, 3),
(28, 68, 8, 12, 0, 60, 0, 0, 0, 80, 2025, 3),
(29, 67, 9, 8, 0, 40, 0, 0, 0, 65, 2025, 3),
(30, 66, 9, 4, 0, 20, 0, 0, 0, 35, 2025, 3),
(31, 65, 8, 8, 0, 40, 0, 0, 0, 65, 2025, 3),
(32, 64, 9, 0, 0, 0, 8, 0, 40, 15, 2025, 3),
(33, 62, 9, 12, 0, 60, 0, 0, 0, 80, 2025, 3),
(34, 61, 9, 8, 0, 40, 0, 0, 0, 65, 2025, 3),
(35, 60, 9, 8, 0, 40, 0, 0, 0, 65, 2025, 3),
(36, 52, 9, 12, 0, 60, 0, 0, 0, 80, 2025, 3),
(37, 63, 9, 12, 0, 60, 0, 0, 0, 80, 2025, 3),
(38, 50, 9, 8, 0, 40, 0, 0, 0, 65, 2025, 3),
(39, 53, 3, 12, 0, 60, 0, 0, 0, 50, 2025, 3),
(40, 49, 7, 12, 0, 60, 0, 0, 0, 80, 2025, 3),
(41, 75, 9, 8, 0, 40, 0, 0, 0, 65, 2025, 3),
(42, 76, 8, 8, 0, 40, 0, 0, 0, 65, 2025, 3),
(43, 77, 9, 8, 0, 40, 0, 0, 0, 65, 2025, 3),
(44, 78, 9, 12, 0, 60, 0, 0, 0, 80, 2025, 3),
(45, 79, 8, 8, 0, 40, 0, 0, 0, 65, 2025, 3),
(46, 80, 9, 4, 0, 20, 0, 0, 0, 35, 2025, 3),
(47, 81, 8, 12, 0, 60, 4, 0, 20, 80, 2025, 3),
(48, 82, 8, 12, 0, 60, 0, 0, 0, 80, 2025, 3),
(49, 83, 9, 0, 0, 0, 0, 0, 0, 0, 2025, 3),
(50, 150, 0, 0, 0, 0, 0, 0, 0, 0, 2025, 3),
(51, 85, 10, 8, 0, 40, 0, 0, 0, 80, 2025, 3),
(52, 87, 10, 4, 0, 20, 0, 0, 0, 40, 2025, 3),
(53, 88, 12, 8, 0, 40, 4, 0, 20, 100, 2025, 3),
(54, 90, 11, 4, 0, 20, 0, 0, 0, 45, 2025, 3),
(55, 91, 10, 8, 0, 40, 0, 0, 0, 80, 2025, 3),
(56, 93, 11, 4, 0, 20, 0, 0, 0, 45, 2025, 3),
(57, 94, 10, 4, 0, 20, 0, 0, 0, 40, 2025, 3),
(58, 95, 10, 4, 0, 20, 4, 0, 20, 40, 2025, 3),
(59, 96, 11, 4, 0, 20, 0, 0, 0, 45, 2025, 3),
(60, 97, 10, 4, 0, 20, 0, 0, 0, 40, 2025, 3),
(61, 98, 12, 4, 0, 20, 0, 0, 0, 50, 2025, 3),
(62, 99, 11, 8, 0, 40, 0, 0, 0, 90, 2025, 3),
(63, 100, 10, 4, 0, 20, 0, 0, 0, 40, 2025, 3),
(64, 101, 11, 4, 0, 20, 0, 0, 0, 45, 2025, 3),
(65, 103, 10, 4, 0, 20, 0, 0, 0, 40, 2025, 3),
(66, 147, 10, 8, 0, 40, 0, 0, 0, 80, 2025, 3),
(67, 105, 11, 4, 0, 20, 4, 0, 20, 45, 2025, 3),
(68, 106, 11, 4, 0, 20, 0, 0, 0, 45, 2025, 3),
(69, 107, 10, 8, 0, 40, 0, 0, 0, 80, 2025, 3),
(70, 108, 10, 4, 0, 20, 0, 0, 0, 40, 2025, 3),
(71, 109, 10, 4, 0, 20, 0, 0, 0, 40, 2025, 3),
(72, 110, 10, 4, 0, 20, 0, 0, 0, 40, 2025, 3),
(73, 111, 10, 4, 0, 20, 0, 0, 0, 40, 2025, 3),
(74, 113, 10, 8, 0, 40, 0, 0, 0, 80, 2025, 3),
(75, 115, 11, 8, 0, 40, 0, 0, 0, 90, 2025, 3),
(76, 116, 11, 8, 0, 40, 0, 0, 0, 90, 2025, 3),
(77, 117, 11, 4, 0, 20, 0, 0, 0, 45, 2025, 3),
(78, 118, 10, 8, 0, 40, 0, 0, 0, 80, 2025, 3),
(79, 119, 10, 8, 0, 40, 0, 0, 0, 80, 2025, 3),
(80, 120, 12, 8, 0, 40, 0, 0, 0, 100, 2025, 3),
(81, 121, 11, 0, 0, 0, 4, 0, 20, 72, 2025, 3),
(82, 122, 11, 8, 0, 40, 0, 0, 0, 90, 2025, 3),
(83, 123, 12, 4, 0, 20, 0, 0, 0, 50, 2025, 3),
(84, 124, 10, 12, 0, 60, 0, 0, 0, 120, 2025, 3),
(85, 125, 10, 4, 0, 20, 0, 0, 0, 40, 2025, 3),
(86, 126, 12, 8, 0, 40, 0, 0, 0, 100, 2025, 3),
(87, 127, 5, 0, 0, 0, 8, 0, 40, 15, 2025, 3),
(88, 128, 7, 8, 0, 40, 0, 0, 0, 65, 2025, 3),
(89, 129, 9, 8, 0, 40, 4, 0, 20, 65, 2025, 3),
(90, 130, 8, 12, 0, 60, 0, 0, 0, 80, 2025, 3),
(91, 131, 8, 8, 0, 40, 0, 0, 0, 65, 2025, 3),
(92, 132, 8, 12, 0, 60, 0, 0, 0, 80, 2025, 3),
(93, 133, 9, 8, 0, 40, 0, 0, 0, 65, 2025, 3),
(94, 134, 7, 12, 0, 60, 0, 0, 0, 80, 2025, 3),
(95, 135, 9, 8, 0, 40, 0, 0, 0, 65, 2025, 3),
(96, 136, 9, 12, 0, 60, 0, 0, 0, 80, 2025, 3),
(97, 137, 9, 8, 0, 40, 0, 0, 0, 65, 2025, 3),
(98, 138, 10, 16, 0, 80, 0, 0, 0, 160, 2025, 3),
(99, 139, 12, 8, 0, 40, 0, 0, 0, 100, 2025, 3),
(100, 140, 10, 4, 0, 20, 0, 0, 0, 40, 2025, 3),
(101, 141, 10, 4, 0, 20, 4, 0, 20, 40, 2025, 3),
(102, 142, 11, 8, 0, 40, 0, 0, 0, 90, 2025, 3),
(103, 143, 12, 4, 0, 20, 0, 0, 0, 50, 2025, 3),
(104, 151, 9, 4, 0, 20, 0, 0, 0, 35, 2025, 3),
(105, 152, 11, 4, 0, 20, 0, 0, 0, 45, 2025, 3),
(106, 154, 1, 8, 0, 40, 0, 0, 0, 60, 2025, 3);

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `categorias`
--

INSERT INTO `categorias` (`id`, `nome`, `tipo`) VALUES
(1, 'Pagamento Alunos', 'despesa');

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `despesas`
--

INSERT INTO `despesas` (`id`, `despesa`, `valor`) VALUES
(1, 'Renda', 310.00);

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
-- Estrutura da tabela `horario`
--

DROP TABLE IF EXISTS `horario`;
CREATE TABLE IF NOT EXISTS `horario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idProfessor` int NOT NULL,
  `dia` varchar(25) NOT NULL,
  `sala` varchar(25) NOT NULL,
  `hora` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `horario`
--

INSERT INTO `horario` (`id`, `idProfessor`, `dia`, `sala`, `hora`) VALUES
(1, 1, 'segunda', 'azul', '14:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `horario_alunos`
--

DROP TABLE IF EXISTS `horario_alunos`;
CREATE TABLE IF NOT EXISTS `horario_alunos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idHorario` int NOT NULL,
  `alunoIndex` int NOT NULL,
  `idAluno` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `horario_alunos`
--

INSERT INTO `horario_alunos` (`id`, `idHorario`, `alunoIndex`, `idAluno`) VALUES
(1, 1, 1, 1);

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
  `notHorario` tinyint NOT NULL DEFAULT '0',
  `ativo` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `professores`
--

INSERT INTO `professores` (`id`, `nome`, `email`, `contacto`, `pass`, `img`, `notHorario`, `ativo`) VALUES
(1, 'Sandra Martins', 'sdm.sandra@gmail.com', 913665676, '$2y$10$xonXJyTLiD4nRWtKgg7FxuD1ql2Chq3Sx.P.gWnTaQrjvjalXhH9i', '', 1, 1),
(2, 'Juliana Coelho', 'julianasfcoelho@gmail.com', 917755697, '$2y$10$iHE/lpsrEXFbxmx0MJivfufKyFMHJzJeNsu5HXaazG4RaYvqiZiAm', '', 0, 1),
(3, 'Filipe Lima', 'filipe.lima2001@gmail.com', 911731593, '$2y$10$sBwc0Da93VLY7FVxESuSUen9b3YRlwzKD63K1q3iJyCOwoG2leZzK', '', 0, 1),
(4, 'Patricia Silva', 'patriciarosariosilva1981@gmail.com', 918308602, '$2y$10$D7.cSllB.ilzCfkd6Pk9Ye2BiEL3vk5iJYbuJQEcPVWbDv.VIezXW', '', 0, 1),
(5, 'Cristiana Neto', 'cristiananeto@sapo.pt', 919549960, '$2y$10$286pT6v26k0eTNpgSFv2/OxgqTpKwU0De9tbXcz7T/JlS6nVNXSse', '', 0, 1),
(6, 'Paula Borralho', 'anapaula.borralho@sapo.pt', 917402807, '$2y$10$/hWJGFLBJvEJy5f5Sny8G..iOsjBute9HlBZXNXUlezQbeRxHG7LC', '', 0, 1),
(7, 'Ana Paula Fonseca', 'anapaulaferreirafonseca92@gmail.com', 915403775, '$2y$10$Pz.juomtG4TiuNzt6l6pf.Jn6uSYFNLhZBScV3LRUz4.NLRRInsim', '', 0, 1),
(8, 'Natália Luciano', 'natalialuci@gmail.com', 966539965, '$2y$10$S8ructuKPxPHWza0RRRUtuMqQOcYNJx7aCH3yxY4eQAQnWarP2EVW', './images/uploads/foto_6806ab92e89773.16181461.png', 0, 1),
(9, 'Arcélio Sampaio', 'arceliosampaio@gmail.com', 912220109, '$2y$10$EW8dOJTRJAlbnVzKjdy3zeo6izfPpG8Bq517a2APJTuP2HUW/GJRm', '', 0, 1),
(10, 'Margarida Oliveira', 'margaridaisabeloliveira6@gmail.com', 918118126, '$2y$10$kV0OnCsrVemrQM.lMbZNHuXhkoJFJr0K8qC7M/hRC42R/ivveNNlm', '', 0, 1),
(11, 'Marta Santos', '', 964391685, '', '', 0, 1),
(12, 'Manuel Azevedo', '', 938855068, '', '', 0, 1);

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
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `professores_recibo`
--

INSERT INTO `professores_recibo` (`id`, `idProfessor`, `horasDadas1Ciclo`, `valorUnitario1Ciclo`, `valorParcial1Ciclo`, `horasDadas2Ciclo`, `valorUnitario2Ciclo`, `valorParcial2Ciclo`, `horasDadas3Ciclo`, `valorUnitario3Ciclo`, `valorParcial3Ciclo`, `horasDadasSecundario`, `valorUnitarioSecundario`, `valorParcialSecundario`, `horasDadasUniversidade`, `valorUnitarioUniversidade`, `valorParcialUniversidade`, `total`, `ano`, `mes`) VALUES
(1, 1, 0, 2, 0, 0, 3, 0, 0, 4, 0, 0, 6, 0, 0, 18, 0, 0, 2025, 3),
(2, 2, 0, 2, 0, 0, 3, 0, 0, 4, 0, 0, 6, 0, 0, 18, 0, 0, 2025, 3),
(3, 3, 0, 2, 0, 0, 3, 0, 0, 4, 0, 0, 6, 0, 0, 18, 0, 0, 2025, 3),
(4, 4, 0, 2, 0, 0, 3, 0, 0, 4, 0, 0, 6, 0, 0, 18, 0, 0, 2025, 3),
(5, 5, 0, 2, 0, 0, 3, 0, 0, 4, 0, 0, 6, 0, 0, 18, 0, 0, 2025, 3),
(6, 6, 0, 2, 0, 0, 3, 0, 0, 4, 0, 0, 6, 0, 0, 18, 0, 0, 2025, 3),
(7, 7, 0, 2, 0, 0, 3, 0, 0, 4, 0, 0, 6, 0, 0, 18, 0, 0, 2025, 3),
(8, 8, 0, 2, 0, 0, 3, 0, 0, 4, 0, 0, 6, 0, 0, 18, 0, 0, 2025, 3),
(9, 9, 0, 2, 0, 0, 3, 0, 0, 4, 0, 0, 6, 0, 0, 18, 0, 0, 2025, 3),
(10, 10, 0, 2, 0, 0, 3, 0, 0, 4, 0, 0, 6, 0, 0, 18, 0, 0, 2025, 3),
(11, 11, 0, 2, 0, 0, 3, 0, 0, 4, 0, 0, 6, 0, 0, 18, 0, 0, 2025, 3),
(12, 12, 0, 2, 0, 0, 3, 0, 0, 4, 0, 0, 6, 0, 0, 18, 0, 0, 2025, 3);

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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `transacoes`
--

INSERT INTO `transacoes` (`id`, `idCategoria`, `descricao`, `valor`, `data`) VALUES
(1, 1, 'Pagamento ao aluno duarte', 60.00, '2025-04-22 07:56:42');

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
