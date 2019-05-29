<?php
class LogRecoder{
	protected $filename;
	protected $file_content;
	function __construct($filename){
		$this->filename = $filename;
		if(!file_exists($filename)){
			file_put_contents($filename, "");
		}
		$this->file_content = file_get_contents($filename);
	}

	public function add($recode){
		date_default_timezone_set("Asia/Shanghai");
		$time = date(DATE_ISO8601);

		$this->file_content.="\n";
		$this->file_content.=$time;
		$this->file_content.="   $recode";
		file_put_contents($this->filename, $this->file_content);
	}

	public function append($content){
		$this->file_content.=$content;
		file_put_contents($this->filename, $this->file_content);
	}
}