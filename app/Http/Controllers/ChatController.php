<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use OpenAI\Laravel\Facades\OpenAI;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ChatController extends Controller
{

    public function getAGreeting()
    {
        $greetings = collect([
            "What do you want?",
            "What can I do for you?",
            "Not you again.",
            "How can I help?",
            "Is it time for dinner yet?",
            "Are we there yet?",
            "What do you want to know?",
            "What now?",
            "Ask me a question!",
            "Speak, mortal.",
            "Say it, don't spray it!",
            "That's my name, don't wear it out."
        ]);

        $buttons = collect([
            "Click",
            "Submit",
            "Ask",
            "Enquire",
            "Probe",
            "Seek",
            "Scrutinize",
            "Parse",
            "Analyze",
            'Uncover',
            'Reveal'
        ]);
        return response()->json([
            'greeting' => $greetings->random(),
            'button' => $buttons->random()
        ]);
        //  return $greetings->random();
    }

    public function getNewGreeting()
    {
        return $this->getAGreeting();
    }

    public function getHomePage()
    {
        return Inertia::render('Dashboard');
    }


    public function seekWisdom(Request $request)
    {
        $query = $request->input('query');
        /*
        $result = OpenAI::completions()->create([
            'model' => 'text-davinci-003',
            'prompt' => 'PHP is',
        ]);
        */
        //  $result = OpenAI::models()->list();
        /*
        $result = $client->completions()->create([
            'model' => 'gpt-3.5-turbo',
            'prompt' => 'Say this is a test',
            //'max_tokens' => 6,
            'temperature' => 0
        ]);
*/

        $result = OpenAI::completions()->create([
            'model' => 'text-davinci-003',
            'prompt' => 'PHP is',
            'temperature' => 0
        ]);


        //$response = $client->models()->list();
        return response()->json(['message' => $result->toArray()]);
        /*
        Log::debug($result['choices'][0]['text']);
        return response()->json(['message' => $result['choices'][0]['text']]);
*/
        //  return response()->json(['message' => $query]);
    }

    public function test($text)
    {

        $process = new Process(['python3', storage_path('scripts/test.py'), $text]);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        $data = $process->getOutput();
        Log::debug($data);

        return response()->json(['message' => $data]);
    }
}
