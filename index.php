<?php 
include 'config.php';
require_once 'vendor/autoload.php';
$client = new Google_Client();
$client->setClientId(CLIENT_ID);
$client->setClientSecret(CLIENT_SECRET);
$client->setRedirectUri(REDIRECT_URI);
$client->setAccessType('offline');
$client->setPrompt('consent');
$client->setScopes(array(
	"https://www.googleapis.com/auth/userinfo.email",
	"https://www.googleapis.com/auth/userinfo.profile",
	"https://www.googleapis.com/auth/drive.file"
));
$oauth = $connect->query('SELECT * FROM oauth_access')->fetch_array(); //get credentials from database
$client->setAccessToken(json_decode($oauth['credentials'],true));
if($client->isAccessTokenExpired()){ //if expired get the new one with the refresh token
	$new_token = $client->refreshToken($oauth['refresh_token']);
}
$service = new Google_Service_Drive($client);
$content = $service->files->get(FILE_ID, array('alt' => 'media' ));//get the image from gdrive using file id
echo '<img src="data:image/jpeg;base64,'.base64_encode($content->getBody()->getContents()).'">';
?>