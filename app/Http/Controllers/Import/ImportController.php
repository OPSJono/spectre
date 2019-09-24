<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use App\Services\CSVService;

use Illuminate\Http\Request;

use \Illuminate\Contracts\View\Factory;
use \Illuminate\View\View;

class ImportController extends Controller
{
    /**
     * @var CSVService
     */
    protected $csvService;

    /**
     * ImportController constructor.
     * @param CSVService $csvService
     */
    public function __construct(CSVService $csvService)
    {
        $this->csvService = $csvService;
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    public function import(Request $request)
    {
        $results = [];

        if($request->isMethod('POST')) {
            $request->validate([
                'file' => 'required|mimes:csv,txt',
            ]);

            if($request->hasfile('file')) {
                $file = $request->file('file');
                $this->csvService->readFileToData($file);
                $results = $this->csvService->getData();

            }
        }

        return view('import')
            ->with('results', $results)
        ;
    }
}
