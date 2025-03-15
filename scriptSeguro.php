<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Listagem de Alunos</title>
</head>
<?php 
    include('./db/conexao.php'); 

    require __DIR__ . "/vendor/autoload.php";

    use Dompdf\Dompdf;
    use Dompdf\Options;

    /**
     * Set the Dompdf options
     */
    $options = new Options;
    $options->setChroot(__DIR__);
    $options->setIsRemoteEnabled(true);

    $dompdf = new Dompdf($options);

    /**
     * Set the paper size and orientation
     */
    $dompdf->setPaper("A4", "landscape");

    /**
     * Render the PHP template before passing it to Dompdf
     */
    ob_start();
    include "template.php";
    $html = ob_get_clean();

    $dompdf->loadHtml($html);

    /**
     * Create the PDF and set attributes
     */
    $dompdf->render();

    $dompdf->addInfo("Title", "An Example PDF");

    /**
     * Save the PDF file locally
     */
    $output = $dompdf->output();
    file_put_contents(__DIR__ . "/images/uploads/listaAlunosSeguro_" . date("m-d-y") . ".pdf", $output);

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';

    $mail = new PHPMailer(true);

    $mail->SMTPDebug  = 2;                     // enables SMTP debug information 
    $mail->SMTPAuth   = true;                  // enable SMTP authentication
    $mail->Port       = 587;
    $mail->Host       = "mail.4x1.pt"; // sets the SMTP server                // set the SMTP port for the GMAIL server
    $mail->Username   = "geral@4x1.pt"; // SMTP account username
    $mail->Password   = "nTgY}w0_fBj}";        // SMTP account password     

    $mail->addAddress('duarte102007marques@gmail.com');
    $mail->SetFrom('geral@4x1.pt', '4x1 | CENTRO DE ESTUDO');
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = 'LISTA DOS ALUNOS';

    $mail->Body = "
            Boa Noite,
            <p>Envio lista atualizada dos alunos que frequentam o Centro de Estudo 4x1.
            <p>
            <p>
            CENTRO DE ESTUDO 4x1 | Alameda Arnaldo Gama nº 161, 4765-001 Vila das Aves | email: geral@4x1.pt
            </body>";

    $pdfPath = __DIR__ . "/images/uploads/listaAlunosSeguro_" . date("m-d-y") . ".pdf";
    if (file_exists($pdfPath)) {
        $mail->addAttachment($pdfPath, "LISTA_ALUNOS_" . date("mdy") . "pdf");
    } else {
        throw new Exception("O ficheiro PDF não foi encontrado: $pdfPath");
    }

    $mail->send();
    //$mail->AddReplyTo("geral@4x1.pt","4x1.pt");
?>
</html>