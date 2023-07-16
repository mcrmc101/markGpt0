<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use OpenAI;

class ChatController extends Controller
{
    public function seekWisdom(Request $request)
    {
        $query = $request->input('query');

        $yourApiKey = env('OPENAI_API_KEY');
        $client = OpenAI::client($yourApiKey);

        $response = $client->completions()->create([
            'model' => 'gpt-3.5-turbo',
            'prompt' => 'Say this is a test',
            'max_tokens' => 6,
            'temperature' => 0
        ]);


        //$response = $client->models()->list();

        $result = $response->toArray();


        Log::debug($result);


        return response()->json(['message' => $result]);
    }
}
