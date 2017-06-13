<?php

$data = curl_get('http://ourdesigngroup.com/photos/new');
preg_match("/<input type=\"hidden\" name=\"authenticity_token\" value=\"(.*)\" \/>/U", $data, $token);
preg_match_all("/<meta name=\"(.*)\" content=\"(.*)\" \/>/U", $data, $meta);
preg_match_all('~_portfolio_website_session=(.*?);~s', $data, $data1);
$session =$data1[0][0]??'MmZvV0hOem5WZFRteFExS25iY052Rmg0cUtQSE1WdGVsM2M4Qi9BWjlESURaY01KVUVRSFVFV2ovODJ0RTliekZXTTl1WnZyVlZQ
cm44NVQ4QWFaQTJJdEVTbVJjd1dKNzl1K3luU1NHVEhvM3JDZHRXcWl1N3BWWXAxVlpvMFBQL0N3aElqeFlzOVhzUk1LTm0zS2tt
MVB2dTFlRU1oS2xaQTV0Rld2WWp4U0JvRm5MeTc3M1IzZUpLM1l5OWp1ZlBwZlJJUSsrMkZkL0hHVTdGeHVjamJ1blhlbmRIV1cw
eERKUi81aXNLVzcrdzh1SHJtQWtFTC9WQVZ6MlZMZi0tNXF1QUdjYVR3K1ZSY3ovMEREalpQdz09--80cf9e2c1d4a631e65fade
467f7bec967a27d09d';
$head = [];
$_ga = '_ga';
$_gid = $_COOKIE['_gid']??'GA1.2.1716967353.1497245197';
$head[] = "Cookie: $session _ga=GA1.2.702086220.1496937347; _gid=GA1.2.1716967353.1497245197";
// print_r($head);
// exit();
foreach ($meta[1] as $key => $value) {
  $head[] = "{$value}: {$meta[2][$key]}";
}
$filename = realpath('upload_data.png');
$file = getCurlValue($filename,'image/png','upload_data.png');

$post = [
   'utf8' => '✓',
   'authenticity_token' => $token[1],
   'photo[title]'=> 'Why not me?',
   'photo[image]'=>$file,
   'commit' => 'Upload'
];
echo curl_post("http://ourdesigngroup.com/photos",$post,$head);


function curl_post($url,$data,$head=array())
{
  $ch = curl_init();
  $head[] = "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,/;q=0.8";
  $head[] = "Accept-Encoding:gzip, deflate";
  $head[] = "Accept-Language:ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4";
  $head[] = "Cache-Control:max-age=0";
  $head[] = "Connection:keep-alive";
  $head[] = "Content-Type:multipart/form-data";
  $head[] = "Host:ourdesigngroup.com";
  $head[] = "Origin:http://ourdesigngroup.com";
  $head[] = "Referer:$url";
  $head[] = "Upgrade-Insecure-Requests:1";
  $head[] = "User-Agent:Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)";
  $user_agent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)';
  $options = array(CURLOPT_URL => $url,
               CURLOPT_USERAGENT=>$user_agent,
               CURLOPT_HTTPHEADER=>$head,
               CURLOPT_RETURNTRANSFER => true,
               CURLINFO_HEADER_OUT => true, //Request header
               CURLOPT_HEADER => true, //Return header
               CURLOPT_SSL_VERIFYPEER => false, //Don't veryify server certificate
               CURLOPT_POST => true,
               CURLOPT_POSTFIELDS => $data
              );

  curl_setopt_array($ch, $options);
  $result = curl_exec($ch);
  return $result;
}


function curl_get($url){
  $ch = @curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  $head[] = "Connection: keep-alive";
  $head[] = "Keep-Alive: 300";
  $head[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
  $head[] = "Accept-Language: en-us,en;q=0.5";
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36');
  curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
  curl_setopt($ch, CURLOPT_FAILONERROR, true);
  curl_setopt($ch, CURLOPT_HEADER, true);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($ch, CURLOPT_TIMEOUT, 60);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
  // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  // curl_setopt($ch, CURLOPT_FAILONERROR, true);
  // curl_setopt($ch, CURLOPT_HEADER, true);
  // curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36");
  // curl_setopt($ch, CURLOPT_URL, $url);
  $page = curl_exec($ch);
  curl_close($ch);
  return $page;
}

function getCurlValue($filename, $contentType, $postname)
{
    if (function_exists('curl_file_create')) {
        return curl_file_create($filename, $contentType, $postname);
    }
    $value = "@{$filename};filename=" . $postname;
    if ($contentType) {
        $value .= ';type=' . $contentType;
    }
    return $value;
}
?>
