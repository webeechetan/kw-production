<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class DatabaseAgent
{
    protected $aiService;
    protected $schemaService;

    public function __construct(OpenAIService $aiService, DatabaseSchemaService $schemaService)
    {
        $this->aiService = $aiService;
        $this->schemaService = $schemaService;
    }

    public function getSchema(): array
    {
        $tables = $this->schemaService->getTables();
        $schema = [];

        foreach ($tables as $table) {
            $schema[$table] = $this->schemaService->getColumns($table);
        }

        return $schema;
    }

    public function handleQuery(string $userInput): array
{
    $schema = $this->getSchema();
    $prompt = "Here is the database schema:\n" . json_encode($schema) .
              "\nTranslate this user request to SQL. Only provide the SQL query without any explanations, formatting, or additional text: '{$userInput}'";

    $sqlQuery = $this->aiService->query($prompt);
    // dd($sqlQuery);

    try {
        // Execute the SQL query
        $results = DB::select($sqlQuery);
        // Convert results to human-readable text
        $textResponse = $this->aiService->generateHumanReadableResponse($userInput,$results);
        return [
            'success' => true,
            'data' => $results,
            'text' => $textResponse,
        ];
    } catch (\Exception $e) {
        return [
            'success' => false,
            'error' => $e->getMessage(),
        ];
    }
}

    /**
     * Extract SQL query from the AI response.
     */
    private function extractSQLQuery(string $response): string
    {
        // Remove markdown and unnecessary text
        $response = preg_replace('/```(sql)?/', '', $response); // Remove code block markers
        $response = trim($response); // Remove extra whitespace

        // Optionally, remove any text before "SELECT" or other SQL keywords
        if (preg_match('/(SELECT|INSERT|UPDATE|DELETE|WITH)\s/i', $response, $matches, PREG_OFFSET_CAPTURE)) {
            $response = substr($response, $matches[0][1]);
        }

        return $response;
    }

}
