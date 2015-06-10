<?php
/**
 * Data base class, deals with reading and writing csv file
 */
class Db {

    private $_dbFilePath = '';

    public function __construct($application) {
        global $SETTINGS;

        $this->_dbFilePath = $application->getBasePath() . '/' . $SETTINGS['dbFilePath'];
    }

    //this function reads csv file
    public function readRecords() {
        //check if file exists, open file

        if (file_exists($this->_dbFilePath) &&
            ($handle = fopen($this->_dbFilePath, 'r')) !== false) {
            $resourceArray = array();

            //read file and add records to array
            while (($line = fgetcsv($handle)) !== FALSE) {
                $resourceArray[] = array(
                    'fileName' => $line[0],
                    'email' => $line[1],
                    'status' => $line[2],
                    );
            }
            fclose($handle);

            //return array
            return $resourceArray;
        }

        throw new Exception('Unable to read records');
    }

    //this function rewrites whole csv file
    public function writeRecords($resourceArray) {
        //check if file exists, open file
        if (file_exists($this->_dbFilePath) &&
            ($handle = fopen($this->_dbFilePath, 'w')) !== false) {
            //write records from array to file
            foreach ($resourceArray as $lines) {
                fputcsv($handle, $lines);
            }
            fclose($handle);
        }
    }
}
