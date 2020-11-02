<?php

/* Template Name: Download Receipt */

require get_template_directory() . '/inc/export_pdf/tcpdf/tcpdf.php';

    ob_start();
    
    include_once 'sip_export_list_payments.php';
    
    $template = ob_get_contents();
    
    ob_end_clean();
    
    $pdf = new TCPDF('P', 'pt', 'A4', true, 'UTF-8', false);
            $pdf->SetCreator('');
            $pdf->SetAuthor('');
            $pdf->SetTitle('');
            $pdf->SetSubject('');
            $pdf->SetKeywords('');
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->SetDefaultMonospacedFont('');
            $pdf->SetMargins(10, 10, 10, true);
            $pdf->SetAutoPageBreak(TRUE, 10);
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
            $pdf->setLanguageArray($l);
            $pdf->SetDisplayMode('fullpage', 'SinglePage', 'UseNone');
            $pdf->SetTextColor(119, 119, 119);
            $pdf->SetDrawColor(119, 119, 119, false, '');
            $pdf->AddPage('P', 'A4');

    $pdf->writeHTML($template, true, true, true, false, ''); 
    $pdf->Output('Payments.pdf', 'D');

?>