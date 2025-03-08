PACKS: 
4 horas/mes
8 horas/mes
12 horas/mes
18 horas/mes

Tabela de alunos atualmente serve para o seguro, a tabela de alunos deve ser diferente

colocar notificações de edit apagar criar etc

Obter horas de um professor:
SELECT COUNT(DISTINCT hora) as horas FROM `professores_presenca` WHERE MONTH(dia) = 2

1 CICLO
SELECT
    SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(
        SUBSTRING_INDEX(p.hora, ' - ', -1), 
        SUBSTRING_INDEX(p.hora, ' - ', 1)
    )))) AS total_horas
FROM professores_presenca AS p
INNER JOIN alunos AS a ON a.id = p.idAluno
WHERE MONTH(p.dia) = 2 AND YEAR(p.dia) = 2025 AND a.ano >= 1 AND a.ano <= 4

2 CICLO
SELECT
    SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(
        SUBSTRING_INDEX(p.hora, ' - ', -1), 
        SUBSTRING_INDEX(p.hora, ' - ', 1)
    )))) AS total_horas
FROM professores_presenca AS p
INNER JOIN alunos AS a ON a.id = p.idAluno
WHERE MONTH(p.dia) = 2 AND YEAR(p.dia) = 2025 AND a.ano > 4 AND a.ano < 7

3 CICLO
SELECT
    SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(
        SUBSTRING_INDEX(p.hora, ' - ', -1), 
        SUBSTRING_INDEX(p.hora, ' - ', 1)
    )))) AS total_horas
FROM professores_presenca AS p
INNER JOIN alunos AS a ON a.id = p.idAluno
WHERE MONTH(p.dia) = 2 AND YEAR(p.dia) = 2025 AND a.ano > 6 AND a.ano <= 9

SECUNDARIO
SELECT
    SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(
        SUBSTRING_INDEX(p.hora, ' - ', -1),
        SUBSTRING_INDEX(p.hora, ' - ', 1)
    )))) AS total_horas
FROM professores_presenca AS p
INNER JOIN alunos AS a ON a.id = p.idAluno
WHERE MONTH(p.dia) = 2 AND YEAR(p.dia) = 2025 AND a.ano > 9

UNIVERSIDADE
SELECT
    SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(
        SUBSTRING_INDEX(p.hora, ' - ', -1),
        SUBSTRING_INDEX(p.hora, ' - ', 1)
    )))) AS total_horas
FROM professores_presenca AS p
INNER JOIN alunos AS a ON a.id = p.idAluno
WHERE MONTH(p.dia) = 2 AND YEAR(p.dia) = 2025 AND a.ano = 0