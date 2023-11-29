<?php

namespace App\Libraries;

use TCPDF;

/**
 * Description of Pdf Library
 *
 * @author https://roytuts.com
 */
class PdfLibrary extends TCPDF {
	
	function __construct() { 
		parent::__construct(); 
        
	}
	
	//Page header
    public function Header() {
        // Set font
        // $this->SetFont('helvetica', 'B', 20);
        // Title
        // $this->Cell(0, 15, 'Invoice', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
	
}