<?php
require 'vendor/autoload.php';
include('./db/conexao.php'); 
use PhpOffice\PhpSpreadsheet\IOFactory;

// Função que remove o prefixo antes de ":"
function limparPrefixo($texto) {
    if (strpos($texto, ':') !== false) {
        $partes = explode(':', $texto, 2);
        $texto = $partes[1];
    }
    return trim(str_replace('º', '', $texto));
}

// Verifica se foram enviados arquivos
if (!isset($_FILES['ficha_excel']) || $_FILES['ficha_excel']['error'][0] !== UPLOAD_ERR_OK) {
    die('Erro ao carregar os ficheiros.');
}

// Loop para processar todos os arquivos
foreach ($_FILES['ficha_excel']['tmp_name'] as $index => $tmp_name) {
    // Guarda o ficheiro temporariamente
    $caminho = './images/uploads/' . basename($_FILES['ficha_excel']['name'][$index]);
    move_uploaded_file($tmp_name, $caminho);

    // Lê o Excel
    $spreadsheet = IOFactory::load($caminho);
    $sheet = $spreadsheet->getActiveSheet();

    //Extrair e limpar os dados
    $nome = limparPrefixo($sheet->getCell('A8')->getValue());
    $morada = limparPrefixo($sheet->getCell('A9')->getValue());
    $localidade = limparPrefixo($sheet->getCell('G9')->getValue());
    $codigo_postal = limparPrefixo($sheet->getCell('A10')->getValue());
    $data_nascimento = limparPrefixo($sheet->getCell('G10')->getFormattedValue());
    $nif = limparPrefixo($sheet->getCell('D10')->getFormattedValue());
    $email = limparPrefixo($sheet->getCell('A11')->getFormattedValue());
    $contato = limparPrefixo($sheet->getCell('G11')->getFormattedValue());
    $escola = limparPrefixo($sheet->getCell('A12')->getValue());
    $ano = limparPrefixo($sheet->getCell('I12')->getValue());
    $curso = limparPrefixo($sheet->getCell('A13')->getValue());
    $turma = limparPrefixo($sheet->getCell('I13')->getValue());
    $nome_mae = limparPrefixo($sheet->getCell('A17')->getValue());
    $telemovel_mae = limparPrefixo($sheet->getCell('H17')->getValue());
    $nome_pai = limparPrefixo($sheet->getCell('H18')->getValue());
    $telemovel_pai = limparPrefixo($sheet->getCell('A18')->getValue());
    $modalidade = limparPrefixo($sheet->getCell('A21')->getValue());

    // $nome = limparPrefixo($sheet->getCell('A7')->getValue());
    // $morada = limparPrefixo($sheet->getCell('A8')->getValue());
    // $localidade = limparPrefixo($sheet->getCell('G8')->getValue());
    // $codigo_postal = limparPrefixo($sheet->getCell('A9')->getValue());
    // $data_nascimento = limparPrefixo($sheet->getCell('G9')->getFormattedValue());
    // $nif = limparPrefixo($sheet->getCell('D9')->getFormattedValue());
    // $email = limparPrefixo($sheet->getCell('A10')->getFormattedValue());
    // $contato = limparPrefixo($sheet->getCell('G10')->getFormattedValue());
    // $escola = limparPrefixo($sheet->getCell('A11')->getValue());
    // $ano = limparPrefixo($sheet->getCell('I11')->getValue());
    // $curso = limparPrefixo($sheet->getCell('A12')->getValue());
    // $turma = limparPrefixo($sheet->getCell('I12')->getValue());
    // $nome_mae = limparPrefixo($sheet->getCell('A16')->getValue());
    // $telemovel_mae = limparPrefixo($sheet->getCell('H16')->getValue());
    // $nome_pai = limparPrefixo($sheet->getCell('A17')->getValue());
    // $telemovel_pai = limparPrefixo($sheet->getCell('H17')->getValue());
    // $modalidade = limparPrefixo($sheet->getCell('A20')->getValue());

    // Inserir na base de dados
    $stmt = $con->prepare("
        INSERT INTO alunos (
            nome, morada, localidade, codigoPostal, nif,
            dataNascimento, email, contacto, escola, ano, curso, turma,
            nomeMae, tlmMae, nomePai, tlmPai, modalidade, ativo
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )
    ");
    $ativo = 1;

    $stmt->bind_param("ssssissisisssisisi",
        $nome,
        $morada,
        $localidade,
        $codigo_postal,
        $nif,
        $data_nascimento,
        $email,
        $contato,
        $escola,
        $ano,
        $curso,
        $turma,
        $nome_mae,
        $telemovel_mae,
        $nome_pai,
        $telemovel_pai,
        $modalidade,
        $ativo
    );

    if ($stmt->execute()) {
        echo "✅ Ficha do arquivo " . basename($_FILES['ficha_excel']['name'][$index]) . " importada com sucesso!<br>";
    } else {
        echo "❌ Erro ao inserir o arquivo " . basename($_FILES['ficha_excel']['name'][$index]) . ": " . $stmt->error . "<br>";
    }
}