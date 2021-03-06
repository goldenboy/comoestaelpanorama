<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'application/libraries/excel_reader2.php';

class ParserXLSpadron {
	protected $ci;
	private $www_ine = "http://www.ine.es/pob_xls/pobmun";
	
	public function __construct() {
		$this->ci =& get_instance();		
		$this->ci->load->library('Curl');		
	}
	
	public function get_last_url(){
		return $this->www_ine.date('y',mktime(0, 0, 0,1,1,date("Y")-1)).".xls";
	}
	
	public function normalize ($string){ 
		$replac = array(
    'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 
    'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 
    'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 
    'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 
    'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 
    'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 
    'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f', '.'=>'' , ','=>'',  '('=>'',
	')'=>'',  '"'=>'', "'"=>'');  
		
		$string = mb_convert_encoding($string,'UTF-8','CP1252');		
		return strtolower(strtr($string, $replac));
	} 
	
	public function parser() {

		$curl = new Curl();					
		$file_name = $curl->open_https_url_file($this->get_last_url());
		$data = new Spreadsheet_Excel_Reader($file_name);		
		
		$padron = '';
		
		for ($i=3;$i < $data->rowcount();$i++){
			$provincia = $this->normalize($data->val($i,'A'));
			$localidad = $this->normalize($data->val($i,'D'));
			$valor = $this->normalize($data->val($i,'E'));
			
			/*require_once 'application/libraries/JSON.php';
			$json = new Services_JSON;
			*/
			//log_message('DEBUG',"Reading $provincia $localidad $valor");
			/*
			$location = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=".urlencode($localidad).",ES&sensor=false");			
			$location_decode = $json->decode($location);
			*/
			$lat = '';
			$lng = '';	
			/*
			if (isset($location_decode->results[0])){	
				$lat = $location_decode->results[0]->geometry->location->lat;
				$lng = $location_decode->results[0]->geometry->location->lng;
			} else {
				log_message('error','Error parsing json for '.$localidad);
			}
			*/
			$this->ci->localidad->insert_localidad($localidad, 
													$lat, 
													$lng, 
													array('id'=>'provincia_id','value'=>$provincia), 
													$valor);
		}	
			//if ($provincia && $localidad && $valor) $padron[$provincia][$localidad] = $valor;

			
		//}
		
		/*require_once 'application/libraries/JSON.php';
		$json = new Services_JSON;
		*/
		/*
		foreach ($padron as $provincia=>$localidad){
			$poblacion = array_keys($localidad);
			$poblacion = $poblacion[0];
										
			$location = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$poblacion,ES&sensor=false");			
			$location_decode = $json->decode($location);
			$lat = '';
			$lng = '';	
			if (isset($location_decode->results[0])){	
				$lat = $location_decode->results[0]->geometry->location->lat;
				$lng = $location_decode->results[0]->geometry->location->lng;
			} else {
				log_message('error','Error parsing json for '.$poblacion);
			}
			
			log_message('DEBUG',"Inserting $poblacion ...");
			
			$this->ci->localidad->insert_localidad($poblacion, 
													$lat, 
													$lng, 
													array('id'=>'provincia_id','value'=>$provincia), 
													$localidad[$poblacion]);			
		}*/
		
		//echo "ejemplo... Obtenido ".$data->val(4,'B').'/'.$data->val(4,'J').'\r\n';
		
		
	}		
}