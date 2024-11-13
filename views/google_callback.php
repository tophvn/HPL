<?php
session_start();
require_once '../google-api/vendor/autoload.php';

$clientID = '614640831923-vulhph6ovaq4rbhfb1l4nd2iu5q611go.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://localhost:8080/ShopThoiTrang/views/login.php';

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token['access_token']);

    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();

    $_SESSION['user'] = [
        'user_id' => $google_account_info->id,
        'username' => $google_account_info->email,
        'name' => $google_account_info->name
    ];

    header("Location: ../index.php");
    exit();
} else {
    header("Location: login.php");
    exit();
}
?>
