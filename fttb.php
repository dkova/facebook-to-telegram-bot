<?
include 'config.php';
$fbarray = json_decode(file_get_contents('https://graph.facebook.com/'.$facebookPageId.'/posts?&access_token='.$facebookAppId.'|'.$facebookAppSecret.'&limit=1'),true);

$last_post_id = trim(file_get_contents("./last_post_id.txt"));
$fb_post_id = $fbarray['data']['0']['id'];
$attach = json_decode(file_get_contents("https://graph.facebook.com/v2.2/".$fb_post_id."?fields=attachments&fields=attachments&access_token=".$facebookAppId.'|'.$facebookAppSecret), true);
$image = $attach['attachments']['data']['0']['media']['image']['src'];
if(strpos($image, "safe_image") !== false){
	$image =  null;
}

if($last_post_id!=$fb_post_id){
if(isset($fbarray['data']['0']['story'])){
	$fbstory = $fbarray['data']['0']['story'];
	if(strpos($fbstory, "shared")!==false){
		
	if(strpos($fbstory, "photo")!==false){
		$message = mb_convert_encoding($fbarray['data']['0']['message'], 'utf-8', mb_detect_encoding($fbarray['data']['0']['message']));
$vurl = urldecode($attach['attachments']['data']['0']['media']['image']['src']);
file_get_contents("https://api.telegram.org/".$tgApiKey."/sendMessage?chat_id=".$chatID."&text=".urlencode($message." ".$vurl));
file_put_contents("./last_post_id.txt", $fb_post_id);
}
	if(strpos($fbstory, "post")!==false){
		$message = mb_convert_encoding($fbarray['data']['0']['message'], 'utf-8', mb_detect_encoding($fbarray['data']['0']['message']));
$vurl = urldecode($attach['attachments']['data']['0']['media']['image']['src']);
file_get_contents("https://api.telegram.org/".$tgApiKey."/sendMessage?chat_id=".$chatID."&text=".urlencode($message." ".$vurl));
file_put_contents("./last_post_id.txt", $fb_post_id);
}
	if(strpos($fbstory, "link")!==false){
$vurl = urldecode(substr($attach['attachments']['data']['0']['target']['url'], 31));
file_get_contents("https://api.telegram.org/".$tgApiKey."/sendMessage?chat_id=".$chatID."&text=".$vurl);
file_put_contents("./last_post_id.txt", $fb_post_id);
}
	if(strpos($fbstory, "album")!==false){
		$album_url = array_slice( $attach['attachments']['data']['0'], -1, 1, TRUE );

		$message = mb_convert_encoding($fbarray['data']['0']['message'], 'utf-8', mb_detect_encoding($fbarray['data']['0']['message']));
$vurl = urldecode($attach['attachments']['data']['0']['media']['image']['src']);
file_get_contents("https://api.telegram.org/".$tgApiKey."/sendMessage?chat_id=".$chatID."&text=".urlencode($message."\nСсылка на альбом: " .$album_url['url']."\n\n".$vurl));
file_put_contents("./last_post_id.txt", $fb_post_id);
}
	if(strpos($fbstory, "event")!==false){
		$message = mb_convert_encoding($fbarray['data']['0']['message'], 'utf-8', mb_detect_encoding($fbarray['data']['0']['message']));
$vurl = urldecode($attach['attachments']['data']['0']['media']['image']['src']);
file_get_contents("https://api.telegram.org/".$tgApiKey."/sendMessage?chat_id=".$chatID."&text=".urlencode($message." ".$vurl));

file_put_contents("./last_post_id.txt", $fb_post_id);
}
}
exit();
} else {
$text = $fbarray['data']['0']['message'];
$tgmess = mb_convert_encoding($text, 'utf-8', mb_detect_encoding($text));

file_get_contents("https://api.telegram.org/".$tgApiKey."/sendMessage?chat_id=".$chatID."&text=".urlencode($text).urlencode(' '.$image));
}
file_put_contents("./last_post_id.txt", $fb_post_id);
}
?>