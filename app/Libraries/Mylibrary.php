<?php
namespace App\Libraries;
class Mylibrary {

    var $notValidForAdminArry = array();  // for admin

    public function __construct() {
    }

    public function decryptPass($pass) {
        $key = "FILEZILLA1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $pos = (strlen($pass) / 3) % strlen($key);
        $decrypt = '';
        $t = 0;
        for ($i = 0; $i < strlen($pass) / 3; $i++) {
            $num = substr($pass, $i * 3, 3);
            if (substr($num, 0, 1) == 0) {
                $num = substr($num, 1, 2);
            }

            $t = $num ^ ord($key[($i + $pos) % strlen($pass)]);

            $decrypt .= chr($t);
        }
        return $decrypt;
    }
    
    public function cryptPass($string) {
        $key = "FILEZILLA1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $pos = strlen($string) % strlen($key);
        $tmp = "";
        $x = "";

        for ($i = 0; $i < strlen($string); $i++) {
            $x = sprintf("%s", $string[$i] ^ $key[($i + $pos) % strlen($key)]);

            if (preg_match("/[^a-z]/", $string[$i])) {
                $min = 3 - strlen(ord($x));
                $z = str_repeat('0', $min) . ord($x);
            } else {
                $z = "0" . ord($x);
            }
            $tmp .= $z;
        }
        return $tmp;
    }

    //////////////////////////////// date format /////////////////////////////
    public function change_date($user_date)
    {
       $return_date = date("d-m-Y", strtotime($user_date));
       return $return_date;
    }


    /*----------------------------- generate random password ---------------------*/
    public function generateOTP($length = 4) 
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
}