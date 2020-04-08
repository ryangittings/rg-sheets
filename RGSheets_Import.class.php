<?php

require __DIR__ . '/vendor/autoload.php';

define('STDIN',fopen("php://stdin","r"));

class RGSheets_Import {
  protected $collection = 'Students';
  protected $templatePath = 'content/student';
  protected $spreadsheetId = '1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms';
  protected $spreadsheetRange = 'Sheet1!A2:F';
  
  function import() {
    $client = $this->getClient();
    $service = new Google_Service_Sheets($client);

    // Prints the names and majors of students in a sample spreadsheet:
    // https://docs.google.com/spreadsheets/d/1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms/edit
    $response = $service->spreadsheets_values->get($this->spreadsheetId, $this->spreadsheetRange);
    $values = $response->getValues();

    if (empty($values)) {
      return false;
    } else {
      return $this->importData($values);
    }
  }

  private function importData($data) {
    $API      = new PerchAPI(1.0, 'rg_sheets');
    $Importer = $API->get('CollectionImporter');

    // Set collection
    $Importer->set_collection($this->collection);

    // Set template
    $Template = $API->get('Template');
    $Template->set($this->templatePath, 'content');
    $Importer->set_template($Template);

    // Empty collection for new dataa
    $Importer->empty_collection();

    foreach($data as $item) {
      try {
        $Importer->add_item([
          'name'    => $item[0],
        ]);    
      } catch (Exception $e) {
        return false;
      }
    }

    return true;
  }

  private function getClient() {
    $client = new Google_Client();
    $client->setApplicationName('Google Sheets API PHP Quickstart');
    $client->setScopes(Google_Service_Sheets::SPREADSHEETS_READONLY);
    $client->setAuthConfig(__DIR__.'/credentials.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = __DIR__ . '/token.json';
    if (file_exists($tokenPath)) {
      $accessToken = json_decode(file_get_contents($tokenPath), true);
      $client->setAccessToken($accessToken);
    } else {
      if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
          $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());

          if (!file_exists(dirname($tokenPath))) {
            mkdir(dirname($tokenPath), 0700, true);
          }

          file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }
      }
    }

    return $client;
  }
}