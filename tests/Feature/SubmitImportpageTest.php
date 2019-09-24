<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SubmitImportpageTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFileRequiredValidationTest()
    {
        $response = $this->post('/import');

        // When validation fails, the request is redirected back to the original form.
        $response->assertStatus(302);

        // Make sure that errors have been put in the session
        $response->assertSessionHas('errors');

        // Make sure the correct field is in the set of errors.
        $response->assertSessionHasErrors('file');

    }

    public function testValidCsvUploadTest()
    {
        Storage::fake('uploads/');

        // This is a generic file upload test. Not sure how to fake a CSV file here.
        $response = $this->post('/import', [
            'file' => UploadedFile::fake()->create('homeowners.csv', 1024)
        ]);

        $response->assertDontSee('The file field is required');
    }
}
