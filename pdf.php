<?php $url = "http://magasinducoin.fr/";?>

<?php //$url = "http://localhost:90/Magasinducoin009/";?>

<?php	

	$css = '<head>';

	if(isset($_REQUEST['id'])){

	require_once("dompdf2/dompdf_config.inc.php");	

	set_time_limit(0);	

	ini_set("memory_limit", "-1"); 

	ini_set('max_execution_time', '120');

	$dompdf = new DOMPDF();

	

	$html = file_get_contents($url.'invoice_pdf.php?id='.$_REQUEST['id'].'&user='.$_REQUEST['user']);

	$content = explode("<head>",$html);

	$FullContent = $content[0] . $css . $content[1];

	

	$dompdf->load_html($FullContent);  

  	$dompdf->set_paper("a4", "portrait");

  	$dompdf->render();

  	$dompdf->stream("invoice.pdf", array("Attachment" => false));

  	exit(0);

	}

?>









