<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'application/libraries/excel_reader2.php';


class ParserXlsParadosContratos {
	protected $ci;

	protected $links_xls = array();

	private $www_months = "https://www.redtrabaja.es/es/redtrabaja/static/Redirect.do?page=statsMunicipios";


	/**
	 * __construct
	 *
	 * @return void
	 * @author 
	 **/
	public function __construct() {
		$this->ci =& get_instance();
		
		$this->ci->load->library('Curl');
		
	}
	
	public function normalize ($string){ 
		$replac = array(
    '�'=>'S', '�'=>'s', '�'=>'Dj','�'=>'Z', '�'=>'z', '�'=>'A', '�'=>'A', '�'=>'A', '�'=>'A', '�'=>'A', 
    '�'=>'A', '�'=>'A', '�'=>'C', '�'=>'E', '�'=>'E', '�'=>'E', '�'=>'E', '�'=>'I', '�'=>'I', '�'=>'I', 
    '�'=>'I', '�'=>'N', '�'=>'O', '�'=>'O', '�'=>'O', '�'=>'O', '�'=>'O', '�'=>'O', '�'=>'U', '�'=>'U', 
    '�'=>'U', '�'=>'U', '�'=>'Y', '�'=>'B', '�'=>'Ss','�'=>'a', '�'=>'a', '�'=>'a', '�'=>'a', '�'=>'a', 
    '�'=>'a', '�'=>'a', '�'=>'c', '�'=>'e', '�'=>'e', '�'=>'e', '�'=>'e', '�'=>'i', '�'=>'i', '�'=>'i', 
    '�'=>'i', '�'=>'o', '�'=>'n', '�'=>'o', '�'=>'o', '�'=>'o', '�'=>'o', '�'=>'o', '�'=>'o', '�'=>'u', 
    '�'=>'u', '�'=>'u', '�'=>'y', '�'=>'y', '�'=>'b', '�'=>'y', '�'=>'f', '.'=>'' , ','=>'',  '('=>'',
	')'=>'',  '"'=>'', "'"=>'');  
		
		//$string = mb_convert_encoding($string,'UTF-8','ISO-8859-1');		
		return strtolower(strtr($string, $replac));
	} 
	 
	public function parser() {

		$curl = new Curl();
		$this->get_links_xls();		

		if(count($this->links_xls)>0) {

			foreach ($this->links_xls as $link) {

				$file_name = $curl->open_https_url_file($link);
				$month = substr($link, -8,2);
				$year = substr($link, -6,2);
				
				$data = new Spreadsheet_Excel_Reader($file_name); 
				for ($i=9;$i < $data->rowcount()-3;$i++){
					$localidad = $this->normalize($data->val($i,'B'));
					$contratos = $this->normalize($data->val($i,'C'));	
					$paro = $this->normalize($data->val($i,'C',1));
					
					if (empty($localidad) || empty($contratos)) continue;
					$this->ci->dato->insert_dato_auto($localidad, $month, '20'.$year, $contratos, DATO_CONTRATOS);						
					$this->ci->dato->insert_dato_auto($localidad, $month, '20'.$year, $paro, DATO_PARO);
				}		
				unset($data);		
			}
		}
	}

	private function get_links_xls() {

		$curl = new Curl();
		$regexp_excell = "<a\s[^>]*href=([\"\']??)([^\" >]*xls)\\1[^>]*>(.*)<\/a>"; 
		$links_months = $this->_get_links_months();


		foreach ($links_months as $link) {

			$html = $curl->open_https_url($link);

			if(preg_match_all("/$regexp_excell/siU", $html, $links, PREG_SET_ORDER)) {
				foreach($links as $link) {

					if(substr($link[2],0,4)!='http') {
						$link[2] = URL_REDTRABAJA.$link[2];
					}

					$this->links_xls[] = $link[2];
				}
			}
		}
	}

	private function _get_links_months() {

		$retval;

		$curl = new Curl();
		$html = $curl->open_https_url($this->www_months);

		$regexp = "<a\s[^>]*href=([\"\']??)([^\" >]*(2009|201[0-9].html|201[0-9]))\\1[^>]*>(.*)<\/a>"; 

		if(preg_match_all("/$regexp/siU", $html, $links, PREG_SET_ORDER)) { 
			foreach($links as $link) { 
		
				if(substr($link[2],0,4)!='http') {
					$link[2] = URL_REDTRABAJA.$link[2];
				}
				$retval[] = $link[2];
			}
		}

		return $retval;
	}
}