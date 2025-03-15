<?php

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
 * Send the PDF to the browser
 */
$dompdf->stream("invoice.pdf", ["Attachment" => 0]);

/**
 * Save the PDF file locally
 */
$output = $dompdf->output();
file_put_contents(__DIR__ . "/images/uploads/listaAlunosSeguro_" . date("m-d-y") . ".pdf", $output);