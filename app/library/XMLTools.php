<?php
class XMLTools
{
	public static function Json2Xml($arr,$isFormated = false){
		$xml = '<?xml version="1.0" encoding="utf-8"?>';
		if($isFormated){
			$xml .= XMLTools::BuildXml($arr,1);
		}else{
			$xml .= XMLTools::BuildXml($arr);
		}
		
		return $xml;
	}

	public static function BuildXml($source,$order = 0){
		$string = "";
		if(!(is_array($source)||is_object($source))){
			return $source;
		}
		foreach ($source as $key => $value) {
			if($key === "__properties") continue;
			if (strlen(filter_var($key, FILTER_VALIDATE_INT))) {
				if($order == 0){
					$string .= XMLTools::BuildXml($value);
				}else{
					$string .= XMLTools::BuildXml($value,$order);
				}
			}else{
				$string .= "\n".str_repeat("	", $order)."<".$key;
				if(isset($value['__properties'])){
					foreach ($value['__properties'] as $field => $property) {
						if(!is_string($property)) continue;
						$string.=" $field=\"$property\"";
					}
				}
				$string.=">";
				if($order == 0){
					$string .= XMLTools::BuildXml($value);
					$string .= "</".$key.">";
				}else{
					if(!(is_array($value) || is_object($value))){
						$string .= $value."</".$key.">\n";
					}else{
						$string .= XMLTools::BuildXml($value,$order + 1);
						$string .= str_repeat("	", $order)."</".$key.">\n";
					}
				}
			}
		}

		return str_replace("\n\n", "\n", $string);
	}

	public static function xmlToArray($xml, $options = array()) {
	    $defaults = array(
		    'namespaceRecursive' => false,  //setting to true will get xml doc namespaces recursively
		    'removeNamespace' => false,     //set to true if you want to remove the namespace from resulting keys (recommend setting namespaceSeparator = '' when this is set to true)
	        'namespaceSeparator' => ':',    //you may want this to be something other than a colon
	        'attributePrefix' => '@',       //to distinguish between attributes and nodes with the same name
	        'alwaysArray' => array(),       //array of xml tag names which should always become arrays
	        'autoArray' => true,            //only create arrays for tags which appear more than once
	        'textContent' => '$',           //key used for the text content of elements
	        'autoText' => true,             //skip textContent key if node has no attributes or child nodes
	        'keySearch' => false,           //optional search and replace on tag and attribute names
	        'keyReplace' => false           //replace values for above search values (as passed to str_replace())
	    );
	    $options = array_merge($defaults, $options);
	    $namespaces = $xml->getDocNamespaces($options['namespaceRecursive']);
	    $namespaces[''] = null; //add base (empty) namespace
	 
	    //get attributes from all namespaces
	    $attributesArray = array();
	    foreach ($namespaces as $prefix => $namespace) {
	        if ($options['removeNamespace']) $prefix = "";
	        foreach ($xml->attributes($namespace) as $attributeName => $attribute) {
	            //replace characters in attribute name
	            if ($options['keySearch']) $attributeName =
	                    str_replace($options['keySearch'], $options['keyReplace'], $attributeName);
	            $attributeKey = $options['attributePrefix']
	                    . ($prefix ? $prefix . $options['namespaceSeparator'] : '')
	                    . $attributeName;
	            $attributesArray[$attributeKey] = (string)$attribute;
	        }
	    }
	 
	    //get child nodes from all namespaces
	    $tagsArray = array();
	    foreach ($namespaces as $prefix => $namespace) {
	        if ($options['removeNamespace']) $prefix = "";
	        foreach ($xml->children($namespace) as $childXml) {
	            //recurse into child nodes
	            $childArray = XMLTools::xmlToArray($childXml, $options);
	            list($childTagName, $childProperties) = each($childArray);
	 
	            //replace characters in tag name
	            if ($options['keySearch']) $childTagName =
	                    str_replace($options['keySearch'], $options['keyReplace'], $childTagName);
	            //add namespace prefix, if any
	            if ($prefix) $childTagName = $prefix . $options['namespaceSeparator'] . $childTagName;
	 
	            if (!isset($tagsArray[$childTagName])) {
	                //only entry with this key
	                //test if tags of this type should always be arrays, no matter the element count
	                $tagsArray[$childTagName] =
	                        in_array($childTagName, $options['alwaysArray']) || !$options['autoArray']
	                        ? array($childProperties) : $childProperties;
	            } elseif (
	                is_array($tagsArray[$childTagName]) && array_keys($tagsArray[$childTagName])
	                === range(0, count($tagsArray[$childTagName]) - 1)
	            ) {
	                //key already exists and is integer indexed array
	                $tagsArray[$childTagName][] = $childProperties;
	            } else {
	                //key exists so convert to integer indexed array with previous value in position 0
	                $tagsArray[$childTagName] = array($tagsArray[$childTagName], $childProperties);
	            }
	        }
	    }
	 
	    //get text content of node
	    $textContentArray = array();
	    $plainText = trim((string)$xml);
	    if ($plainText !== '') $textContentArray[$options['textContent']] = $plainText;
	 
	    //stick it all together
	    $propertiesArray = !$options['autoText'] || $attributesArray || $tagsArray || ($plainText === '')
	            ? array_merge($attributesArray, $tagsArray, $textContentArray) : $plainText;
	 
	    //return node as array
	    return array(
	        $xml->getName() => $propertiesArray
	    );
	}
}