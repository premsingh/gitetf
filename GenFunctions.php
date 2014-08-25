<?php 
function e($a)
{
	echo '===>Out Put<pre>';
	print_r($a);
}

function d($a)
{
	echo '====>Out Put<pre>';
	print_r($a);
	die;
}



function let_to_num($v){ 
//This function transforms the php.ini notation for numbers (like '2M') to an integer (2*1024*1024 in this case)
    $l = substr($v, -1);
    $ret = substr($v, 0, -1);
    switch(strtoupper($l)){
    case 'P':
        $ret *= 1024;
    case 'T':
        $ret *= 1024;
    case 'G':
        $ret *= 1024;
    case 'M':
        $ret *= 1024;
    case 'K':
        $ret *= 1024;
        break;
    }
    return $ret;
}








function allTrim($val)
{
    $val = trim($val, '"');
	$val = trim($val, "'");
	$val = trim($val);
	
	$val = addslashes($val);
	return $val;
}





function curPageURL()
{
	 $pageURL = 'http';
	if(isset($_SERVER["HTTPS"]))
	if($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	 $pageURL .= "://";
	
	 if ($_SERVER["SERVER_PORT"] != "80") {
	  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	 } else {
	  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	 }
	 return $pageURL;
}

//  logged member profiule type
function getProfileType($p_id = '')
{
	$profile_model = new Model_DbTable_Profile ();
	
	if( $p_id != '' && $p_id > 0 )
	{
		$row = $profile_model->getProfile($p_id);
		if(isset($row->type))		
			return $row->type;
		else
			return '';
	}
	else
	{		  
      $auth = Zend_Auth::getInstance();  
      if ($auth->hasIdentity())
	  {  	 
		  $row  = $profile_model->getProfile($auth->getIdentity()->member_id);		  		  
		  if(isset($row->type))		
			return $row->type;
		else
			return '';
      }
	  else
	  {
	     return '';
	  }
	}
}

function getDbInstance()
{			
	$db = Zend_Db::factory('Pdo_Mysql', array(
											'host'     => 'localhost',
											'username' => 'root',
											'password' => '',
											'dbname'   => 'werudev_live'
											));
	return $db;		
}

function getProfileId($member_id)
{
   $m_obj = new Model_DbTable_Profile();
   $row   = $m_obj->getProfile($member_id);   
   return $row['profile_id'];   
}

function getLoggedId()
{
	$auth    = Zend_Auth::getInstance();
	if($auth->hasIdentity())
	{
		$mem_id  = $auth->getIdentity()->member_id;
		$mem_id  = getProfileId($mem_id);
		return $mem_id;
	}
	else
	{
		return '';
	}
}

//------------------------------------------------------------------

function URL2URI($URL)
{
    if (empty($URL)) return NULL;
    $url_info = parse_url($URL);
    if (!isset($url_info['host']) || !isset($url_info['path'])) return NULL;
    return ($url_info['host'] === $_SERVER['HTTP_HOST']) ? ltrim($url_info['path'], '/') : NULL;
}


function getInfoFromURI($URI)
{
     if (empty($URI)) return NULL;    
     $routes = Route::all();
     foreach ($routes as $oneRoute)
     if ($match = $oneRoute->matches($URI))
          return $match;    
     return NULL;
}

//---------------------------

function delete_directory($dirname)
{
   if (is_dir($dirname))
      $dir_handle = opendir($dirname);
   if (!$dir_handle)
      return false;
   while($file = readdir($dir_handle)) {
      if ($file != "." && $file != "..") {
         if (!is_dir($dirname."/".$file))
            unlink($dirname."/".$file);
         else
            delete_directory($dirname.'/'.$file);    
      }
   }
   closedir($dir_handle);
   rmdir($dirname);
   return true;
}

//----------------------------------

function chmodr($path, $filemode) {
    if (!is_dir($path))
        return chmod($path, $filemode);

    $dh = opendir($path);
    while (($file = readdir($dh)) !== false) {
        if($file != '.' && $file != '..') {
            $fullpath = $path.'/'.$file;
            if(is_link($fullpath))
                return FALSE;
            elseif(!is_dir($fullpath) && !chmod($fullpath, $filemode))
                    return FALSE;
            elseif(!chmodr($fullpath, $filemode))
                return FALSE;
        }
    }

    closedir($dh);

    if(chmod($path, $filemode))
        return TRUE;
    else
        return FALSE;
}

//-------------------------------

function get_file_extension($file_name)
{
  return substr(strrchr($file_name,'.'),1);
}
//--------------------------------------


function get_new_name($file_name)
{
  if(trim($file_name)=='')
  {
  	return $file_name;
  }  
  $ext =  substr(strrchr($file_name,'.'),1);
  
  $new_name = time().'.'.$ext; 
  return $new_name;
}
//--------------------------------------











function between($string, $start, $end)
{
	$string = " ".$string;
	$ini = strpos($string,$start);
	if ($ini == 0) return "";
	$ini += strlen($start);
	$len = strpos($string,$end,$ini) - $ini;
	return substr($string,$ini,$len);
}

//-----------------------------------------

 function generatePassword ($length = 8)
 {   
    $password = "";
    $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";   
    $maxlength = strlen($possible);
    
    if ($length > $maxlength) {
      $length = $maxlength;
    }
	   
    $i = 0;
	    
    while ($i < $length)
	{      
      $char = substr($possible, mt_rand(0, $maxlength-1), 1); 
      if (!strstr($password, $char))
	  {         
        $password .= $char;       
        $i++;
      }
    }   
    return $password;
 }
 
 //-----------------------------------------
  
function cropString($string)
{
		if (strlen ( $string ) > 12) {
			return substr ( $string, 0, 12 ) . '...';
		} else {
			return $string;
		}
}

//-----------------------------------------------

function getDevice()
{
		$mobile_browser = '0';
		 
		if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
			$mobile_browser++;
		}
		 
		if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
			$mobile_browser++;
		}  
		 
		$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
		$mobile_agents = array(
			'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
			'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
			'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
			'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
			'newt','noki','oper','palm','htc_','pana','pant','phil','play','port','prox',
			'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
			'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
			'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
			'wapr','webc','winw','winw','xda ','xda-');
		 
		if (in_array($mobile_ua,$mobile_agents)) {
			$mobile_browser++;
		}		 
		if (strpos(strtolower($_SERVER['ALL_HTTP']),'OperaMini') > 0) {
			$mobile_browser++;
		}		 
		if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'windows') > 0) {
			$mobile_browser = 0;
		}
		if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'iphone') > 0) {
			$mobile_browser++;
		}		
		if (strpos($_SERVER['HTTP_USER_AGENT'],'ipad') > 0) {
		   $mobile_browser++;
		}		 
		if (strpos($_SERVER['HTTP_USER_AGENT'],'HTC_') > 0) {
			$mobile_browser++;
		}
		
		$device_type = '';
		
		if ($mobile_browser > 0) {
		  $device_type = 'mobile';
		}else{
		    $device_type = 'computer';
		}		
		return  $device_type;
}


//  quickemail(receiver_id,sender_id,title, body)

function quickemail($receiver_id,$sender_id,$title, $body)
{
		
		$profile_model = new Model_DbTable_Profile ();		
			
		$receiver_row  = $profile_model->fetchRow("profile_id = '$receiver_id' ");
			
		$mail = new Zend_Mail ();
		$mail->setBodyHtml ( getEmailHtml($body,$receiver_row->ego_email));
		$mail->setFrom ( 'TEst@test.com', 'testing' );
		$mail->addTo ( $receiver_row->ego_email );
		$mail->setSubject ( strip_tags ( $title ) );
				
		try
		{	
			$mail->send ();	
		} 
		catch (Exception $e)
		{	//------------
		}
}


function getNewsDate($date)
{
	return date("j F Y" , $date);	
}



function array_sort_func($a,$b=NULL)
{
   static $keys;
   if($b===NULL) return $keys=$a;
   foreach($keys as $k)
   {
      if(@$k[0]=='!')
	  {
         $k=substr($k,1);
         if(@$a[$k]!==@$b[$k])
		 {
            return strcmp(@$b[$k],@$a[$k]);
         }
      }
      else if(@$a[$k]!==@$b[$k])
	  {
         return strcmp(@$a[$k],@$b[$k]);
      }
   }
   return 0;
} 



function array_sort(&$array)
{
   if(!$array) return '';
   $keys=func_get_args();
   array_shift($keys);
   array_sort_func($keys);
   usort($array,"array_sort_func");       
} 


function customnewsdate($news_date, $add_date)
{
   $formatted_date = '';
    
   if(trim($news_date) != '')
   { 	
   		$date_arr = explode("-",$news_date);
   		$formatted_date = mktime(0, 0, 0, $date_arr[0]  ,$date_arr[1], $date_arr[2]);		
   }
   else
   {
   		$formatted_date = $add_date; 
   }
   return date("j F Y" , $formatted_date);

}



//  sand box details


	$API_UserName  = urlencode('prem.s_1337667975_biz_api1.dotsquares.com');
	$API_Password  = urlencode('1337668017');
	$API_Signature = urlencode('AlzRVwoRKLjoACmkNV-8jkEtv5CNA9R5bPJjopBALo0ZOhLyx5S.XxEa');
	$environment   = 'sandbox'; // or 'beta-sandbox' or 'live'
	




//  paypal pro function
function PPHttpPost($methodName_, $nvpStr_ )
{
	global $environment;
    global $API_UserName;
	global $API_Password ;
	global $API_Signature;
	
	// Set up your API credentials, PayPal end point, and API version.
	
	/*	
	API Username: 	prem.s_1337667975_biz_api1.dotsquares.com	
	API Password: 	1337668017	
	Signature: 	AlzRVwoRKLjoACmkNV-8jkEtv5CNA9R5bPJjopBALo0ZOhLyx5S.XxEa
	
	$API_UserName  = urlencode('prem.s_1337667975_biz_api1.dotsquares.com');
	$API_Password  = urlencode('1337668017');
	$API_Signature = urlencode('AlzRVwoRKLjoACmkNV-8jkEtv5CNA9R5bPJjopBALo0ZOhLyx5S.XxEa');
	*/	
	
	$API_Endpoint = "https://api-3t.paypal.com/nvp";
	
	if("sandbox" === $environment || "beta-sandbox" === $environment) {
		$API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
	}
	
	$version = urlencode('51.0');

	// Set the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	// Turn off the server and peer verification (TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);

	// Set the API operation, version, and API signature in the request.
	$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";

	// Set the request as a POST FIELD for curl.
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

	// Get response from the server.
	$httpResponse = curl_exec($ch);

	if(!$httpResponse) {
		exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
	}

	// Extract the response details.
	$httpResponseAr = explode("&", $httpResponse);

	$httpParsedResponseAr = array();
	foreach ($httpResponseAr as $i => $value) {
		$tmpAr = explode("=", $value);
		if(sizeof($tmpAr) > 1) {
			$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
		}
	}

	if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
		exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
	}

	return $httpParsedResponseAr;
}

