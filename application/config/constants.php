<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/*
 * Constantes con urls 
 */

define('URL_REDTRABAJA', 'https://www.redtrabaja.es/');
define('GOOGLE_GEOCODE', 'http://maps.google.com/maps/api/geocode/');


 /*
  * ESTRUCTURA DE DATOS 
  */

define('PATH_TEMP', '/tmp/');

/*
 * PARA EL TIPO DE DATOS EN LA BBDD 
 */

define('DATO_PARO', 1);
define('DATO_CONTRATOS', 2);
define('DATO_OFERTAS', 3);
define('DATO_OFERTAS_ESPANHA', 4);


/** Cada 7 días volvemos a consultar ofertas*/
define('MILISECONDS_REGENERATE_JOBS', 604800);
//define('MILISECONDS_REGENERATE_JOBS', 1800);





/* End of file constants.php */
/* Location: ./application/config/constants.php */