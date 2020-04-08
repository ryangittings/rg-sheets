<?php
    PerchScheduledTasks::register_task('rg_sheets', 'import_data', 15, 'rg_sheets_import');
    
    spl_autoload_register(function($class_name){
      if (strpos($class_name, 'RGSheets')===0) {
        include(__DIR__.'/'.$class_name.'.class.php');
        return true;
      }
      return false;
    });

    function rg_sheets_import($last_run_date) {
      $Importer = new RGSheets_Import();

      if ($Importer->import()) {
        return array(
          'result'=>'OK',
          'message'=>'Successfully imported.'
        );
      }else{
        return array( 
          'result'=>'FAILED',
          'message'=>'Sheet could not be fetched.'
        );
      }
    }