<?php

namespace Tests\Unit;

use App\Services\CSVService;
use Tests\TestCase;

class CsvServiceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCsvServiceCanParseNamesTest()
    {
        $fullNameParser = new \FullNameParser();
        $csvService = new CSVService($fullNameParser);
        $fakeData = [
            'Mr John Smith',
            'Mrs Jane Smith',
            'Mister John Doe',
            'Mr Bob Lawblaw',
            'Mr and Mrs Smith',
            'Mr Craig Charles',
            'Mr M Mackie',
            'Mrs Jane McMaster',
            'Mr Tom Staff and Mr John Doe',
            'Dr P Gunn',
            'Dr & Mrs Joe Bloggs',
            'Ms Claire Robbo',
            'Prof Alex Brogan',
            'Mrs Faye Hughes-Eastwood',
            'Mr F. Fredrickson',
        ];

        $csvService->setCsvData($fakeData);
        $results = $csvService->getData();

        // Based on the fake data, this is the result set we would expect to see.
        $predictedResults = array (
            0 =>
                array (
                    'title' => 'Mr.',
                    'first_name' => 'John',
                    'initial' => NULL,
                    'last_name' => 'Smith',
                ),
            1 =>
                array (
                    'title' => 'Mrs.',
                    'first_name' => 'Jane',
                    'initial' => NULL,
                    'last_name' => 'Smith',
                ),
            2 =>
                array (
                    'title' => 'Mr.',
                    'first_name' => 'John',
                    'initial' => NULL,
                    'last_name' => 'Doe',
                ),
            3 =>
                array (
                    'title' => 'Mr.',
                    'first_name' => 'Bob',
                    'initial' => NULL,
                    'last_name' => 'Lawblaw',
                ),
            4 =>
                array (
                    'title' => 'Mr.',
                    'first_name' => NULL,
                    'initial' => NULL,
                    'last_name' => 'Smith',
                ),
            5 =>
                array (
                    'title' => 'Mrs.',
                    'first_name' => NULL,
                    'initial' => NULL,
                    'last_name' => 'Smith',
                ),
            6 =>
                array (
                    'title' => 'Mr.',
                    'first_name' => 'Craig',
                    'initial' => NULL,
                    'last_name' => 'Charles',
                ),
            7 =>
                array (
                    'title' => 'Mr.',
                    'first_name' => NULL,
                    'initial' => 'M',
                    'last_name' => 'Mackie',
                ),
            8 =>
                array (
                    'title' => 'Mrs.',
                    'first_name' => 'Jane',
                    'initial' => NULL,
                    'last_name' => 'McMaster',
                ),
            9 =>
                array (
                    'title' => 'Mr.',
                    'first_name' => 'Tom',
                    'initial' => NULL,
                    'last_name' => 'Staff',
                ),
            10 =>
                array (
                    'title' => 'Mr.',
                    'first_name' => 'John',
                    'initial' => NULL,
                    'last_name' => 'Doe',
                ),
            11 =>
                array (
                    'title' => 'Dr.',
                    'first_name' => NULL,
                    'initial' => 'P',
                    'last_name' => 'Gunn',
                ),
            12 =>
                array (
                    'title' => 'Dr.',
                    'first_name' => NULL,
                    'initial' => NULL,
                    'last_name' => 'Bloggs',
                ),
            13 =>
                array (
                    'title' => 'Mrs.',
                    'first_name' => 'Joe',
                    'initial' => NULL,
                    'last_name' => 'Bloggs',
                ),
            14 =>
                array (
                    'title' => 'Ms.',
                    'first_name' => 'Claire',
                    'initial' => NULL,
                    'last_name' => 'Robbo',
                ),
            15 =>
                array (
                    'title' => 'Prof.',
                    'first_name' => 'Alex',
                    'initial' => NULL,
                    'last_name' => 'Brogan',
                ),
            16 =>
                array (
                    'title' => 'Mrs.',
                    'first_name' => 'Faye',
                    'initial' => NULL,
                    'last_name' => 'Hughes-Eastwood',
                ),
            17 =>
                array (
                    'title' => 'Mr.',
                    'first_name' => NULL,
                    'initial' => 'F.',
                    'last_name' => 'Fredrickson',
                ),
        );

        $this->assertTrue($results === $predictedResults);
    }
}
