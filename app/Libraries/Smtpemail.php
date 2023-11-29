<?php
namespace App\Libraries;

class Smtpemail {

    public function __construct() {
    }

    /*---------------------------- Global crediantial -------------------------------*/
    public function globalEmail() {
        
        $config = array(
            'protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
            'SMTPHost' => 'mail.citechnology.in', 
            'SMTPPort' => 465,
            'SMTPUser' => 'test@citechnology.in',
            'SMTPPass' => 'Test@#148',
            'SMTPCrypto' => 'ssl', //can be 'ssl' or 'tls' for example
            'mailType' => 'html', //plaintext 'text' mails or 'html'
            'SMTPTimeout' => '10', //in seconds
            'charset' => 'iso-8859-1',
            'wordWrap' => TRUE,
			'validation'=>TRUE
        );



        // $config = array(
        //     'protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
        //     'SMTPHost' => 'n3plcpnl0016.prod.ams3.secureserver.net', 
        //     'SMTPPort' => 465,
        //     'SMTPUser' => 'info@easy-affordable.com',
        //     'SMTPPass' => '7bKoVKDl8tvV', 
        //     'SMTPCrypto' => 'ssl', //can be 'ssl' or 'tls' for example
        //     'mailType' => 'html', //plaintext 'text' mails or 'html'
        //     'SMTPTimeout' => '10', //in seconds
        //     'charset' => 'iso-8859-1',
        //     'wordWrap' => TRUE,
		// 	'validation'=>TRUE
        // );

        return $config;
    }
    
}