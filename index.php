<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
;
// Ensure library/ is on include_path
/*
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));
*/

set_include_path(implode(PATH_SEPARATOR, array(
    dirname(dirname(dirname(__FILE__))),
    get_include_path(),
)));

//ìîè ïåğåìåííûå
  $sec_ar = array();
  $sec_ar['books'] = array("key"=>"books","name"=>"Êíèãè","issel"=>false,"id"=>3,"color"=>"#8B2323");
  $sec_ar['educate'] = array("key"=>"educate","name"=>"Ó÷åáíàÿ ëèòåğàòóğà","issel"=>false,"id"=>2665,"color"=>"#CD5555");
  $sec_ar['programms'] = array("key"=>"programms","name"=>"Ïğîãğàììû","issel"=>false,"id"=>4,"color"=>"#104E8B");
  $sec_ar['cancelar'] = array("key"=>"cancelar","name"=>"Êàíöòîâàğû","issel"=>false,"id"=>6,"color"=>"#EE30A7");
//  $sec_ar['games'] = array("key"=>"games","name"=>"Èãğû. Èãğóøêè","issel"=>false,"id"=>5);
//  $sec_ar['music'] = array("key"=>"music","name"=>"Ìóçûêà","issel"=>false,"id"=>1);
//  $sec_ar['video'] = array("key"=>"video","name"=>"Âèäåî","issel"=>false,"id"=>2);
$banner_ar = array(array("cat"=>14023,"subcat"=>15632),array("cat"=>12895,"subcat"=>14019),array("cat"=>14011,"subcat"=>0),array("cat"=>14063,"subcat"=>0),array("cat"=>14020,"subcat"=>0),array("cat"=>12928,"subcat"=>0));

$domain_path_s="ycheb.ru";

if (substr($_SERVER['HTTP_HOST'],0,4) == 'www.')
        define('DOMAIN_PATH','http://www.'.$domain_path_s);
else
        define('DOMAIN_PATH','http://'.$domain_path_s);

define('MYCAT',6);
define('PERPAGE',18);
define('DESC_LEN',500);
define('SIMILAR_PROD_NUM',15);
define('WATCHED_PROD_NUM',300);
define('SEARCH_PROD_NUM',300);
define('MIN_OR_STR_LEN',3);
define('TIME_DIFFER',36000);
define('DESC','Â èíòåğíåò-ìàãàçèíå Ó÷åáíèêè.ğó âû ìîæåòå êóïèòü ó÷åáíèêè, êàíöåëÿğñêèå òîâàğû è äğóãèå ó÷åáíûå ïğèíàäëåæíîñòè.');
define('PARTNER','3741_3');
define('HOLDS_DESC_LEN','500');
define('GENERAL_PARTNER',3741);

set_include_path("C:\ZendFramework-1.11.11\library");
//set_include_path("library");
/*
books - color:#8B2323;
programms - color:#104E8B;
educate - color:#CD5555;
cancelar - color:#EE30A7;
*/
/** Zend_Application */
require_once 'Zend/Application.php';
require_once APPLICATION_PATH.'/utils/glob.php';
include 'Zend/Loader.php';
include 'Zend/Controller/Front.php';
include APPLICATION_PATH.'/plugins/mystartup.php';

//echo "Íà ñàéòå èäóò ïğîôèëàêòè÷åñêèå ğàáîòû. Ïğèíîñèì ñâîè èçâèíåíèÿ çà íåóäîáñòâà.";
//die;

if (isset($_GET['act']))
{
 if ($_GET['act']=='details')
 {
  $pid = $_GET['pid'];
  $url = DOMAIN_PATH.'/products/getdetails/item/'.$pid;
  header('Location:'.$url);
 }
}

$front = Zend_Controller_Front::getInstance();
$front->registerPlugin(new mystartup_Plugin());

// Create application, bootstrap, and run

$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();


