<?php
/**
 * Processes queue of files to convert
 */



class Processor
{
    const STATUS_PENDING = 0;
    const STATUS_DONE = 1;
    const STATUS_ERROR = 2;

    public function __construct () {
        require 'Db.php';
        require 'Application.php';
    }

    public function run() {
        global $SETTINGS;
        //read from db
        $db = new Db($this->getBasePath());
        $resourceArray = $db->readRecords();


        //for each file that is pending
        foreach ($resourceArray as &$values) {

            //start ffmpeg process
            if ($values['status'] == self::STATUS_PENDING) {


		        // find unique file name
                $application = new Application();
                $outputDirPath = $application->getBasePath() . '/' . $SETTINGS['outputDirPath'];
                if (!file_exists($outputDirPath)) {
                    mkdir($outputDirPath, 0775, true);
                }

                $outputFileName = $values['fileName'] . '.mp4';
                if (file_exists($outputDirPath . '/' . $outputFileName)) {
                    $i = 0;
                    do {
                        $outputFileName = $values['fileName'] . $i . '.mp4';
                        $i++;
                    } while (file_exists($outputFileName));
                }

                $values['outputFileName'] = $outputFileName;

		        // run ffmpeg with unique file name
                $output = NULL;
                $return_var = NULL;

                $command = 'ffmpeg -i ' . $application->getBasePath() . '/files/' . $values['fileName'] . ' -c:v h264 -c:a mp3 -b:v 768k -b:a 128k -movflags +faststart ' . $outputDirPath . '/' . $outputFileName;
                echo "Executing: $command\n";

                //ffmpeg -i VTS_02_1.VOB -c:v h264 -c:a mp3 -b:v 768k -b:a 128k -movflags +faststart in_dialog.mp4
                exec ($command, $output, $return_var);

                if (!$return_var) {
                    $values['status'] = self::STATUS_DONE;
                    // delete input file
                    unlink ($application->getBasePath() . '/files/' . $values['fileName']);
                // if failed try to delete unique filename and change status to error in db
                } else {
                    $values['status'] = self::STATUS_ERROR;
                    unlink ($outputDirPath . '/' . $outputFileName);
                };
            }


            //on success send email with link to output file; maybe - hash file link, keep link-hash in db, serv hash as link to user
            $to = $values['email'];
            //TODO revise link to transcoded file
            $fileLink = '<a href="'. $SETTINGS['serverName'] . $SETTINGS['relativPath'] . '/' .  $SETTINGS['outputDirPath'] . '/' . $outputFileName . '">' . $outputFileName . '</a>';
            if ($values['status'] == self::STATUS_DONE) {
                $subject = $SETTINGS['subjectEmailSuccess'];
                $message = ''. $fileLink. '';
                $headers = $SETTINGS['emailHeaders'];

                mail($to, $subject, $message, $headers);

            } else {
                $subject = $SETTINGS['subjectEmailFail'];
                //TODO revise linking in case of faliure
                $message = ''. '<a href="' . $SETTINGS['serverName'] . /*($SETTINGS['relativPath'] . */'">Mai baga o fisa</a>' . '';
                $headers = $SETTINGS['emailHeaders'];

                mail($to, $subject, $message, $headers);
            }
        }
        unset($values);

        // update db
        $db->writeRecords($resourceArray);
    }

    public function getBasePath(){
        $basePath = realpath(dirname(__FILE__) . '/../');

        return $basePath;
    }
}
