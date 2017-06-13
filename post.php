<?php

$data = curl_get('http://ourdesigngroup.com/photos/new');
preg_match("/<input type=\"hidden\" name=\"authenticity_token\" value=\"(.*)\" \/>/U", $data, $token);
preg_match_all("/<meta name=\"(.*)\" content=\"(.*)\" \/>/U", $data, $meta);
$head = [];
foreach ($meta[1] as $key => $value) {
  $head[] = "{$value}: {$meta[2][$key]}";
}
$filename = realpath('upload_data.png');
echo $filename;
$file = getCurlValue($filename,'image/png','upload_data.png');

$post = [
   'utf8' => '&#x2713;',
   'authenticity_token' => $token[1],
   'photo[title]'=> 'Why not me?',
   'photo[image]'=>$file,
   'commit' => 'Upload'
];
echo curl_post("http://ourdesigngroup.com/photos",$post,$head);


function curl_post($url,$data,$head=array())
{
  $ch = curl_init();
  $head[] = "Connection: keep-alive";
  $head[] = "Keep-Alive: 300";
  $head[] = "Accept-Charset: ISO-8859-1,UTF-8;q=0.7,*;q=0.7";
  $head[] = "Accept-Language: en-us,en;q=0.5";
  $head[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg';
  $head[] = 'Connection: Keep-Alive';
  $head[] = 'Content-type: text/html;charset=UTF-8';
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
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($ch, CURLOPT_TIMEOUT, 60);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
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
