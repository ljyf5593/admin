<?php defined('SYSPATH') OR die('No direct access allowed.');

return array(

    'driver'       => 'orm',
    'hash_method'  => 'sha256',
    'hash_key'     => 'cf3a344170077d11bdb5fff31532f679a19',
    'lifetime'     => 1209600,
    'session_type' => Session::$default,
    'session_key'  => 'auth_user',
    
    // 46c2a14598fcc55d75827284e73782d65ec5d18aaceea02a750e55940ae484ed
    // Username/password combinations for the Auth File driver
    'users' => array(
        // 'admin' => 'b3154acf3a344170077d11bdb5fff31532f679a1919e716a02',
    ),

);
