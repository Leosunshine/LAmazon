<?php

class ImageBed
{
	
	public static function upload($file){
		$url = 'http://image.baidu.com/pcdutu/a_upload?fr=html5&target=pcSearchImage&needJson=true';
		if(!file_exists($file)) return '';
		$post['file'] = new CURLFile(realpath($file));
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL , $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	    $output = curl_exec($ch);
	    curl_close($ch);

	    if($output == '') return '';
	    echo $output;
	    $output = json_decode($output, true);
	    if(isset($output['url']) && $output['url'] != '') {
	        return $output['url'];
	    }
	    return 'c';
	}
}