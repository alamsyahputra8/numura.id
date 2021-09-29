<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use Dompdf\Dompdf;
class pdfGenerator { 
	public function generate($html,$filename,$paper,$layout)
	{
		//define('DOMPDF_ENABLE_AUTOLOAD', false);
		//require_once(BASEPATH."./vendor/dompdf/dompdf/dompdf_config.inc.php");
		//require_once("./vendor/dompdf/dompdf/dompdf_config.inc.php");
		//require_once(dirname(__FILE__) . '/dompdf/dompdf_config.inc.php');
		//require_once './vendor/dompdf/dompdf/dompdf_config.inc.php';
		require_once 'dompdf/autoload.inc.php';
		// instantiate and use the dompdf class
		$dompdf = new Dompdf();
		$dompdf->loadHtml($html);
		$dompdf->setPaper($paper, $layout);
		$dompdf->render();
		$dompdf->stream($filename.'.pdf',array("Attachment"=>true));
	}
}


/* End of file Pdf.php */
/* Location: ./application/libraries/Pdf.php */