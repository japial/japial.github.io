<?php

protected function authenticated(Request $request, $user)
{
	$ip = NULL; $deep_detect = TRUE;

	if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE)
	{
		$ip = $_SERVER["REMOTE_ADDR"];
		if ($deep_detect)
		{
			if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
				$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
	}
	$xml = simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=".$ip);

	$country =  $xml->geoplugin_countryName ;
	$city = $xml->geoplugin_city;
	$area = $xml->geoplugin_areaCode;
	$code = $xml->geoplugin_countryCode;

	$user_agent     =   $_SERVER['HTTP_USER_AGENT'];
	$os_platform    =   "Unknown OS Platform";
	$os_array       =   array(
		'/windows nt 10/i'     =>  'Windows 10',
		'/windows nt 6.3/i'     =>  'Windows 8.1',
		'/windows nt 6.2/i'     =>  'Windows 8',
		'/windows nt 6.1/i'     =>  'Windows 7',
		'/windows nt 6.0/i'     =>  'Windows Vista',
		'/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
		'/windows nt 5.1/i'     =>  'Windows XP',
		'/windows xp/i'         =>  'Windows XP',
		'/windows nt 5.0/i'     =>  'Windows 2000',
		'/windows me/i'         =>  'Windows ME',
		'/macintosh|mac os x/i' =>  'Mac OS X',
		'/mac_powerpc/i'        =>  'Mac OS 9',
		'/linux/i'              =>  'Linux',
		'/ubuntu/i'             =>  'Ubuntu',
		'/iphone/i'             =>  'iPhone',
		'/ipod/i'               =>  'iPod',
		'/ipad/i'               =>  'iPad',
		'/android/i'            =>  'Android',
		'/blackberry/i'         =>  'BlackBerry',
		'/webos/i'              =>  'Mobile'
	);
	foreach ($os_array as $regex => $value)
	{
		if (preg_match($regex, $user_agent))
		{
			$os_platform    =   $value;
		}
	}
	$browser        =   "Unknown Browser";
	$browser_array  =   array(
		'/msie/i'       =>  'Internet Explorer',
		'/firefox/i'    =>  'Firefox',
		'/safari/i'     =>  'Safari',
		'/chrome/i'     =>  'Chrome',
		'/edge/i'       =>  'Edge',
		'/opera/i'      =>  'Opera',
		'/netscape/i'   =>  'Netscape',
		'/maxthon/i'    =>  'Maxthon',
		'/konqueror/i'  =>  'Konqueror',
		'/mobile/i'     =>  'Handheld Browser'
	);
	foreach ($browser_array as $regex => $value)
	{
		if (preg_match($regex, $user_agent))
		{
			$browser    =   $value;
		}
	}
	$ul['user_id'] = $user->id;
	$ul['ip'] =  request()->ip();
	$ul['location'] = $city.(", ").$country .("($code)");
	$ul['browser'] = $browser.' on '.$os_platform;
	Iplog::create($ul);
}
