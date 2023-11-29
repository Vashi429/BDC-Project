<?php
// namespace App\Libraries;
/*
 *  ======================================= 
 *  Author     : Team Tech Arise 
 *  License    : Protected 
 *  Email      : info@techarise.com 
 * 
 *  ======================================= 
 */
require_once APPPATH . "ThirdParty/PHPExcel.php";
class Excel extends PHPExcel {
    public function __construct() {
        parent::__construct();
    }
}
?>