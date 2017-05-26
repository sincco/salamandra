<?php

use \Sincco\Sfphp\Config\Reader;

final class ElasticEmailHelper {
	public function send($para, $asunto, $contenidoTxt, $contenidoHtml, $de, $deNombre) {
		$apiElastic = Reader::get('elasticemail');
		$url = 'https://api.elasticemail.com/v2/email/send';
		try{
			$post = array('from' => $de,
				'fromName' => $deNombre,
				'apikey' => $apiElastic['api_key'],
				'subject' => $asunto,
				'to' => $para . ';' . $apiElastic['cc'],
				'bodyHtml' => $contenidoHtml,
				'bodyText' => $contenidoTxt,
				'isTransactional' => false);
				$ch = curl_init();
				curl_setopt_array($ch, array(
				CURLOPT_URL => $url,
				CURLOPT_POST => true,
				CURLOPT_POSTFIELDS => $post,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HEADER => false,
				CURLOPT_SSL_VERIFYPEER => false
			));
			$result=curl_exec ($ch);
			curl_close ($ch);
			return $result;
		}
		catch(Exception $ex){
			return $ex->getMessage();
		}
	}
}