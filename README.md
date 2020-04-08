# RG Sheets

This app will allow you to import data from a Google sheet, into a specified Perch collection. The app uses Perch's scheduled tasks in order to import the data every so often. This can be configured within the app.

## Getting started

To get this app up and running, it requires a few steps:

- Install this app folder into `your_perch_folder/addons/apps/`
- Run `composer install`
- Generate a `credentials.json` by following the Step 1 of the instructions [here](https://developers.google.com/sheets/api/quickstart/php). Add the `credentials.json` file inside the newly created app folder, `rg_sheets`
- `cd` into `rg_sheets` folder in the terminal (or if you're in VS Code, right-click the folder and Open in Terminal)
- Run `php create_token.php` and follow the instructions in the terminal to generate a `token.json` file to allow authentication
- You should be good to go! 

### Options

The magic happens in `RGSheets_Import.class.php` file. In here, you can edit two main variables:

- `collection` - the collection you want to import to
- `templatePath` - the path to your collection's template relative to Perch's template folder
- `spreadsheetId` - the ID of the spreadsheet, e.g `https://docs.google.com/spreadsheets/d/1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms/edit#gid=0` would be `BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms`
- `spreadsheetRange` - this is the range of data you want to pull from the sheet. In the example, `Sheet1!A2:F`, Sheet1 is the sheet name, A2 is the column/row you want to start from, and F is the end column to get every single row in the sheet.
- To map the data to the collection, you can do the following to make the first column to `name`. Of course, you can add more, as per Perch's [import docs](https://docs.grabaperch.com/api/import/collections/):
```php
$Importer->add_item([
  'name'    => $item[0],
]); 
```
- By default, it's set to update the spreadsheet every 15 minutes (if your cron runs that often). You can adjust this by changing the `scheduled_tasks.php` file, replacing the `15` with the specified minutes. You can read more about Perch's scheduled tasks, [here](https://docs.grabaperch.com/perch/getting-started/installing/scheduled-tasks/).