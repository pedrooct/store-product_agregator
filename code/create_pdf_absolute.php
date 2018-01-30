<?php

require_once "../vendor/autoload.php";

class pdf{

	public function pdf_send_product($product_id)
	{
		$data=file_get_contents("http://labpro.dev/obtainproduct/".$product_id);
		$data=json_decode($data);
		$loja=file_get_contents("http://labpro.dev/obtainstore/".$data[0]->store_id);
		$loja=json_decode($loja);

		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Paulo Bento & Pedro Costa');
		$pdf->SetTitle("Produto Pdf");
		$pdf->SetSubject('Produto pedido'.$data[0]->nome);
		//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
		// set default header data
		$pdf->SetHeaderData("UFP.jpg", PDF_HEADER_LOGO_WIDTH, "produto ".$data[0]->nome." da loja ".$loja[0]->nome); //IMPORTANTE
		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}
		// ---------------------------------------------------------
		// set font
		$pdf->SetFont('dejavusans', '', 10);

		// add a page
		$pdf->AddPage();

		$content='<div class="columns">
		<ul class="price">
		<li class="header">'.$data[0]->nome.'</li>
		<li class="grey"> '.$loja[0]->nome.'</li>
		<li>preco:'.$data[0]->preco.'</li>
		<li>portes:'.$data[0]->portes.'</li>
		<li><img src="data:image/'.$data[0]->imagetype.';base64,'.$data[0]->imagem.'" height="200" width="200" /></li>
		<li>especificao:'.$data[0]->especificacao.'</li>';

		//$content=$_SESSION['table'];
		$pdf->writeHTML($content, true, false, true, false, ''); //$pdf->writeHTML($content, true, 0, true, 0); //

		$pdf->lastPage();

		$fileName=$data[0]->nome.'.pdf';

		//$pdf->Output($dirname,'F');
		//$pdf->Output(_DIR_.'/register'.'sender.pdf', 'F');
		$pdf->Output(__DIR__ ."/pdf"."/".$fileName,'F');
		return __DIR__."/pdf"."/".$fileName;

	}
}
?>
