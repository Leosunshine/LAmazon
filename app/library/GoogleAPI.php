<?php
class GoogleAPI 
{
	public static function translate($query,$from,$to){
		$args = array(
	        'q' => $query,
	        'appid' => "20190601000303979",
	        'salt' => rand(10000,99999),
	        'from' => $from,
	        'to' => $to,

	    );
	    $args['sign'] = GoogleAPI::buildSign($query, "20190601000303979", $args['salt'], "Z8ieZCCUYaZGp3SedfW0");
	    $ret = GoogleAPI::call("http://api.fanyi.baidu.com/api/trans/vip/translate", $args);
	    $ret = json_decode($ret, true);
	    return $ret; 
	}

	private static function buildSign($query,$appID,$salt,$secKey){
		$str = $appID . $query . $salt . $secKey;
	    $ret = md5($str);
	    return $ret;
	}

	private static function call($url, $args=null, $method="post", $testflag = 0, $timeout = 10, $headers=array())
	{
	    $ret = false;
	    $i = 0; 
	    while($ret === false) 
	    {
	        if($i > 1)
	            break;
	        if($i > 0) 
	        {
	            sleep(1);
	        }
	        $ret = GoogleAPI::callOnce($url, $args, $method, false, $timeout, $headers);
	        $i++;
	    }
	    return $ret;
	}

	private static function callOnce($url, $args=null, $method="post", $withCookie = false, $timeout = 10, $headers=array())
	{
	    $ch = curl_init();
	    if($method == "post") 
	    {
	        $data = GoogleAPI::convert($args);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	        curl_setopt($ch, CURLOPT_POST, 1);
	    }else 
	    {
	        $data = convert($args);
	        if($data) 
	        {
	            if(stripos($url, "?") > 0) 
	            {
	                $url .= "&$data";
	            }
	            else 
	            {
	                $url .= "?$data";
	            }
	        }
	    }
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    if(!empty($headers)) 
	    {
	        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	    }
	    if($withCookie)
	    {
	        curl_setopt($ch, CURLOPT_COOKIEJAR, $_COOKIE);
	    }
	    $r = curl_exec($ch);
	    curl_close($ch);
	    return $r;
	}

	private static function convert(&$args)
	{
	    $data = '';
	    if (is_array($args))
	    {
	        foreach ($args as $key=>$val)
	        {
	            if (is_array($val))
	            {
	                foreach ($val as $k=>$v)
	                {
	                    $data .= $key.'['.$k.']='.rawurlencode($v).'&';
	                }
	            }
	            else
	            {
	                $data .="$key=".rawurlencode($val)."&";
	            }
	        }
	        return trim($data, "&");
	    }
	    return $args;
	}

	private static function getTKK(){
		$ch = curl_init("https://www.baidu.com/");
		$cookie = "";
		$backHeader = 0;
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HEADER,$backHeader);
		//curl_setopt($ch, CURLOPT_COOKIE, $cookie);
		
		$output = curl_exec($ch);

		if($output === FALSE){
			echo "failed";
		}else{
			echo $output;
			// preg_match("/token: '\S+'/", $output,$token);
			// preg_match("/window.gtk = '\S+'/",$output,$gtk);
			// print_r($token);
			// print_r($gtk);
			// $token = substr($token[0], 8, -1);
			// $gtk = substr($gtk[0], 14,-1);
			// echo $token."<br/>";
			// echo $gtk."<br/>";
			echo "<pre/>";
			print_r($backHeader);
			print_r($cookie);
		}
		curl_close($ch);
	}
}