<?php

require __DIR__ . '/vendor/autoload.php';

$client = new Google_Client();
$client->setApplicationName('Perch - RG Sheets');
$client->setScopes(Google_Service_Sheets::SPREADSHEETS_READONLY);
$client->setAuthConfig(__DIR__.'/credentials.json');
$client->setAccessType('offline');
$client->setPrompt('select_account consent');

// Request authorization from the user.
$authUrl = $client->createAuthUrl();
printf("Open the following link in your browser:\n%s\n", $authUrl);
print 'Enter verification code: ';
$authCode = trim(fgets(STDIN));

// Exchange authorization code for an access token.
$accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
$client->setAccessToken($accessToken);

// Check to see if there was an error.
if (array_key_exists('error', $accessToken)) {
    throw new Exception(join(', ', $accessToken));
}

// Save the token to a file.
if (!file_exists(dirname($tokenPath))) {
    mkdir(dirname($tokenPath), 0700, true);
}
file_put_contents($tokenPath, json_encode($client->getAccessToken()));