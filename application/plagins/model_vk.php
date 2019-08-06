<?php

class Model_Vk
{
	private $client_id = VK_CLIENT_ID; // ID приложения (vk.com)
	private $client_secret = VK_CLIENT_SECRET; // Защищённый ключ
	
	function __construct()
	{
		
	}
	
	public function get_url_autorize($redirect_uri) 
	{
		$url = 'http://oauth.vk.com/authorize';

		$params = array(
			'client_id'     => $this->client_id,
			'redirect_uri'  => $redirect_uri,
			'scope' => 'offline,email,wall',
			'response_type' => 'code',
			'display' => 'page',			
			'v' => '5.80',			
		);				 
		
		return $url.'?'.urldecode(http_build_query($params));
	}
	
	public function get_authVK ($redirect_uri)
	{
		if (isset($_GET['code'])) 
		{
			$params = array(
				'client_id' => $this->client_id,
				'client_secret' => $this->client_secret,
				'code' => $_GET['code'],
				'redirect_uri' => $redirect_uri
			);
			$token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);
			
			if (isset($token['access_token'])) {
				$params = array(
					'uids'         => $token['user_id'],
					'fields'       => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big',
					'access_token' => $token['access_token'],
					'v' => '5.80',	
				);
				
				$userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);
				$userInfo["response"][0]['email'] = $token['email'];
				
				return $userInfo;
			}
			else
			{
				return false;
			}			
		}
		else
		{
			return false;
		}
	}	

}
