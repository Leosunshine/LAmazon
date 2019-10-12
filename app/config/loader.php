<?php

$loader = new \Phalcon\Loader();
/**
 * We're a registering a set of directories taken from the configuration file
 */
function loadDirs($dirs,$isRecursively = false){
	foreach ($dirs as $index => $dir) {
		$dir = str_replace("/", DIRECTORY_SEPARATOR, $dir);
		if(is_dir($dir)){
			loadDir($dir,$isRecursively);
		}
	}
}

function loadDir($dir, $isRecursively){
	if(is_dir($dir)){
		$files = scandir($dir);
		foreach ($files as $key => $file) {
			if($file === "." || $file === "..") continue;
			$sep = "";
			if(substr($dir, -1) !== DIRECTORY_SEPARATOR){
				$sep = DIRECTORY_SEPARATOR;
			}
			if(is_dir($dir.$sep.$file)){
				if($isRecursively){
					loadDir($dir.$sep.$file,$isRecursively);
				}else{
					continue;
				}
			}else{
				if(substr($file, -4) === ".php"){
					include_once $dir.$sep.$file;
				}
			}
		}
	}
}
set_include_path(get_include_path().PATH_SEPARATOR.APP_PATH."/app/library/");
loadDirs($config->libraryDirs,false);
$loader->registerDirs(
    array(
        $config->application->controllersDir,
        $config->application->modelsDir,
        $config->application->classDir
    )
)->register();
 