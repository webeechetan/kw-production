<?php

namespace App\Services;

use GuzzleHttp\Client;

class OpenAIService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . env('OPEN_AI_API_KEY'),
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function query(string $prompt): string
    {
        $response = $this->client->post('chat/completions', [
                    'json' => [
                        'model' => 'gpt-3.5-turbo', // Use 'gpt-4' if needed
                        'messages' => [
                            [
                                'role' => 'system',
                                'content' => 'You are an SQL query generator.',
                            ],
                            [
                                'role' => 'user',
                                'content' => $prompt,
                            ],
                        ],
                        'max_tokens' => 150,
                    ],
                ]);
        
        $response = json_decode($response->getBody(), true);
        return $response['choices'][0]['message']['content'];
    }

    /**
     * Generate a human-readable text response from query results.
     */
    public function generateHumanReadableResponse(string $userInput, array $results)
    {
        $prompt = "Convert the following JSON data into a human-readable format. Exclude ids and updated_at. The data you are converting is going to text to speech so give data accordingly. Make it concise and clear:\n\n" . json_encode($results, JSON_PRETTY_PRINT);
        $response = $this->client->post('chat/completions', [
            'json' => [
                'model' => 'gpt-3.5-turbo', // Use 'gpt-4' if needed
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a data interpreter.',
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'max_tokens' => 150,
            ],
        ]);

        $response = json_decode($response->getBody(), true);
        return $response['choices'][0]['message']['content'];


    }
}
