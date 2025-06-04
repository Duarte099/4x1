<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Listagem de Alunos</title>
</head>
<?php 
    $auxLogin = true;
    $cronjob = true;
    include('/home/xpt123/admin/db/conexao.php');

    require '/home/xpt123/vendor/autoload.php';

    use Dompdf\Dompdf;
    use Dompdf\Options;

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    if ($cronjobSeguro == 1) {
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
        require '/home/xpt123/scriptSeguroPdf.php';
        $html = ob_get_clean();

        $dompdf->loadHtml($html);

        /**
         * Create the PDF and set attributes
         */
        $dompdf->render();

        $dompdf->addInfo("Title", "Lista Alunos Seguro");

        /**
         * Save the PDF file locally
         */
        $output = $dompdf->output();
        file_put_contents("/home/xpt123/admin/uploads/seguro/listaAlunosSeguro_" . date("d-m-y") . ".pdf", $output);

        require '/home/xpt123/PHPMailer/src/Exception.php';
        require '/home/xpt123/PHPMailer/src/PHPMailer.php';
        require '/home/xpt123/PHPMailer/src/SMTP.php';

        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->SMTPDebug  = 2;                     // enables SMTP debug information 
        $mail->SMTPAuth   = true;                  // enable SMTP authentication
        $mail->Port       = 587;
        $mail->Host       = "mail.4x1.pt"; // sets the SMTP server                // set the SMTP port for the GMAIL server
        $mail->Username   = "geral@4x1.pt"; // SMTP account username
        $mail->Password   = "nTgY}w0_fBj}";        // SMTP account password     

        // $mail->addAddress('marco.rodrigues@segup.pt');
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

        $pdfPath = "/home/xpt123/admin/uploads/seguro/listaAlunosSeguro_" . date("d-m-y") . ".pdf";
        if (file_exists($pdfPath)) {
            $mail->addAttachment($pdfPath, "LISTA_ALUNOS_" . date("mdy") . "pdf");
        } else {
            throw new Exception("O ficheiro PDF não foi encontrado: $pdfPath");
        }

        $mail->send();
        //$mail->AddReplyTo("geral@4x1.pt","4x1.pt");
    }
?>