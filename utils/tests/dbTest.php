<?php
$basePath = realpath(dirname(__FILE__) . '../../../aplication');
$testPath = realpath(dirname(__FILE__) . '../../');

// Change dbFilePath to test file in $SETTINGS
$SETTINGS['dbFilePath'] = 'tests/dbFile.csv';

class TestApplication {
    public function getBasePath(){
        global $testPath;

        return $testPath;
    }
}
require $basePath . '/lib/Db.php';
$db = new Db(new TestApplication());

$testArray = array();

// Store row in database
$testArray[] = array(
    'fileName' => 'numeTest1',
    'email' => 'emailTest1',
    'status' => 'statusTest1',
);
 $db->writeRecords($testArray);
// Read rows form database
 $rows = $db->readRecords();
// output rows
var_dump($rows);