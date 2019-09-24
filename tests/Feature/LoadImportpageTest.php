<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoadImportpageTest extends TestCase
{
    /**
     * A test to load the page.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/import');

        $response->assertStatus(200);
        $response->assertSee('Homeowner Import');
        $response->assertSee('Select a CSV to upload');
    }
}
