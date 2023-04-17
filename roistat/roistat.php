<?php 
require_once "{$_SERVER['DOCUMENT_ROOT']}/roistat/config.php";

class Roistat {
	
	public function setPhone($phone)
	{
		if(!$phone) return;
		
	    $phone=preg_replace('![^0-9]+!', '', $phone);
	    $phone = preg_replace('/(^8)/', '7', $phone);
	    //$phone = '+' . $phone;
	    return $phone;
	}

	public function setCall($caller,$callee, $timeStartCall,$status, $marker, $visit_id, $duration, $answerDuration,$country = 'RU'){
	
		if(!$caller || !$callee) return;
			
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => ($country == "RU") ? "https://cloud.roistat.com/api/v1/project/phone-call?key=".KEY_PROJECT."&project=".PROJECT : "https://cloud.roistat.com/api/v1/project/phone-call?key=".KEY_PROJECT_KZ."&project=".PROJECT_KZ,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => json_encode(array(
				'callee' => preg_replace('/\D/', '', $callee),
				'caller' => preg_replace('/\D/', '', $caller),
				'date' => $timeStartCall,
				'save_to_crm' => 0,
				'status' => $status,
				'duration' => $duration,
				'answer_duration' => $answerDuration,
				'marker' => $marker,
				'visit_id' => $visit_id
			)),
			CURLOPT_HTTPHEADER => array(
				"content-type: application/json"
			)
		));
		$data = json_decode(curl_exec($curl), true);
		curl_close($curl);
		return $data;
	}
	
	public function updateCall($dataCall){
		
			$curl = curl_init();
			curl_setopt_array($curl, array(
			CURLOPT_URL => "https://cloud.roistat.com/api/v1/project/calltracking/call/update?key=".KEY_PROJECT."&project=".PROJECT,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => json_encode($dataCall),
			CURLOPT_HTTPHEADER => array(
				"content-type: application/json"
					)
			));
			$data = json_decode(curl_exec($curl), true);
			curl_close($curl);
			return $data;
		}

	public function cUrl($url, array $post = array(), $methodHttp = 'GET', $json = false) {
	
		$curl = curl_init();
		$data = $json ? json_encode($post) : http_build_query($post);
		if ($methodHttp === 'POST') {
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
	
		if ($methodHttp === 'GET') {
			$url .= '&' . http_build_query($post);
		}
	
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt_array($curl, array(
		CURLOPT_HEADER => 0,
		CURLOPT_RETURNTRANSFER => 1,
		));
	
		$result = curl_exec($curl);
		curl_close($curl);
	
		return json_decode($result, 1);
	}
	
}

