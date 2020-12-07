Examples for: https://(www.)example.com/subFolder/myfile.php?var=blabla#555
```php
//=================================================== //
//========== self-defined SERVER variables ========== //
//=================================================== //
$_SERVER["DOCUMENT_ROOT"]  🡺 /home/user/public_html
$_SERVER["SERVER_ADDR"]    🡺 143.34.112.23
$_SERVER["SERVER_PORT"]    🡺 80(or 443 etc..)
$_SERVER["REQUEST_SCHEME"] 🡺 https                                         //similar: $_SERVER["SERVER_PROTOCOL"] 
$_SERVER['HTTP_HOST']      🡺         example.com (or with WWW)             //similar: $_SERVER["ERVER_NAME"]
$_SERVER["REQUEST_URI"]    🡺                       /subFolder/myfile.php?var=blabla
$_SERVER["QUERY_STRING"]   🡺                                             var=blabla
__FILE__                   🡺 /home/user/public_html/subFolder/myfile.php
__DIR__                    🡺 /home/user/public_html/subFolder              //same: dirname(__FILE__)
$_SERVER["REQUEST_URI"]    🡺                       /subFolder/myfile.php?var=blabla
parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH)🡺  /subFolder/myfile.php 
$_SERVER["PHP_SELF"]       🡺                       /subFolder/myfile.php

// ==================================================================//
//if "myfile.php" is included in "PARENTFILE.php" , and you visit  "PARENTFILE.PHP?abc":
$_SERVER["SCRIPT_FILENAME"]🡺 /home/user/public_html/parentfile.php
$_SERVER["PHP_SELF"]       🡺                       /parentfile.php
$_SERVER["REQUEST_URI"]    🡺                       /parentfile.php?var=blabla
__FILE__                   🡺 /home/user/public_html/subFolder/myfile.php

// =================================================== //
// ================= handy variables ================= //
// =================================================== //
//If site uses HTTPS:
$HTTP_or_HTTPS = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off') || $_SERVER['SERVER_PORT']==443) ? 'https://':'http://' );            //in some cases, you need to add this condition too: if ('https'==$_SERVER['HTTP_X_FORWARDED_PROTO'])  ...

//To trim values to filename, i.e. 
basename($url)             🡺 myfile.php

//excellent solution to find origin
$debug_files = debug_backtrace();       
$caller_file = count($debug_files) ? $debug_files[count($debug_files) - 1]['file'] : __FILE__;
``` 