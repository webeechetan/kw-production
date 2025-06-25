<?php

namespace App\Http\Controllers;

use App\Services\DatabaseAgent;
use Illuminate\Http\Request;

class AIController extends Controller
{
    protected $databaseAgent;

    public function __construct(DatabaseAgent $databaseAgent)
    {
        $this->databaseAgent = $databaseAgent;
    }

    public function query(Request $request)
    {
        $userInput = $request->input('query');

        $response = $this->databaseAgent->handleQuery($userInput);

        return response()->json($response);
    }

    public function getSchema()
    {
        return response()->json($this->databaseAgent->getSchema());
    }
}
