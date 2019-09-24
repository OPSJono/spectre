<?php

namespace App\Services;

use \FullNameParser;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class CSVService
 * @package App\Services
 */
class CSVService
{
    /**
     * @var string
     */
    protected $savedFile = '';

    /**
     * @var array
     */
    protected $csvData = [];

    /**
     * @var FullNameParser
     */
    protected $parser;

    /**
     * CSVService constructor.
     *
     * @param FullNameParser $parser
     */
    public function __construct(FullNameParser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Parse a CSV file and save the data to the protected property on this class.
     *
     * @param $file
     *
     * @void
     */
    public function readFileToData($file)
    {
        $this->saveUploadedFileToDisk($file);

        $this->csvData = [];
        $row = 0;

        // Added so that we can use fgets() on files that contain \r line endings.
        ini_set('auto_detect_line_endings', true);

        if(file_exists($this->savedFile)) {
            if (($handle = fopen($this->savedFile, 'r')) !== false) {
                while (($data = fgetcsv($handle, 20000, ',')) !== false) {
                    $row++;
                    // Skip over the first row as that is the column header.
                    if ($row === 1) {
                        continue;
                    } else {
                        // For these CSVs we are only interested in the data in the first column.
                        $this->csvData[] = reset($data);
                    }
                }
                fclose($handle);
            }

        } else {
            Log::error('File not found: '.$this->savedFile);
        }
    }

    /**
     * Get the data from the CSV and return it in a correctly formatted array for display
     *
     * @return array
     */
    public function getData()
    {
        $results = [];

        foreach($this->csvData as $key => $value) {
            $beUpdatedInNextLoop = false;

            // convert the two edge cases into the same edge case.
            $name = str_replace(' and ', ' & ', $value);

            $multiples = explode(' & ', $name);
            $nameCount = count($multiples);

            foreach($multiples as $person) {
                $parser = new FullNameParser();
                $name = $parser->parse($person);

                // If there is no valid surname, look to take the forename as the surname.
                if(empty($name['lname'])) {

                    if(!empty($name['fname'])) {
                        $name['lname'] = $name['fname'];
                        $name['fname'] = null;
                    }
                }

                // If there still isn't a surname present, try to take the surname from the previous user.
                // This assumes the usage of "and" in the name.
                if($nameCount > 1) {
                    if($beUpdatedInNextLoop == true) {
                        $resultsSoFar = count($results);

                        if(isset($results[$resultsSoFar - 1])) {
                            // set previous person last name from this record.;
                            $results[$resultsSoFar - 1]['lname'] = $name['lname'];
                        }
                    }

                    if(empty($name['lname'])) {
                        $beUpdatedInNextLoop = true;
                    }

                }

                // Save the record.
                $results[] = $name;
            }
        }

        // Clean up after the upload, as we're now retrieving the data, we no longer need the original CSV.
        if(file_exists($this->savedFile)) {
            unlink($this->savedFile);
        }


        return $results;
    }

    /**
     * Saves the original file upload on disk for us to access.
     *
     * @param $file UploadedFile
     * @param $name string
     * @param $ext string
     *
     * @void
     */
    private function saveUploadedFileToDisk($file, $name = null, $ext = '.csv')
    {
        $folder = storage_path('uploads/');
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        if(is_null($name)) {
            $name = 'homeowners-'.date('YmdHis');
        }

        $file->move($folder, $name.$ext);
        $this->savedFile = $folder.$name.$ext;
    }


}
