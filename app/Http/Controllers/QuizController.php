<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class QuizController extends Controller
{
    public function startQuiz($categoryName)
    {
        // Handle start quiz logic or load fallback
        $questions = []; // Replace with actual logic
        return view('quiz', compact('categoryName', 'questions'));
    }

    public function show($categoryId)
    {
        $client = new Client();
        try {
            // Fetch questions
            $response = $client->get('https://opentdb.com/api.php', [
                'query' => [
                    'amount' => 15,
                    'category' => $categoryId,
                    'type' => 'multiple',
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            $questions = $data['results'];

            // Fetch category name
            $categoriesResponse = $client->get('https://opentdb.com/api_category.php');
            $categories = json_decode($categoriesResponse->getBody(), true)['trivia_categories'];
            $categoryName = collect($categories)->firstWhere('id', $categoryId)['name'] ?? 'Unknown Category';

            return view('quiz', [
                'categoryName' => $categoryName,
                'questions' => $questions,
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error fetching quiz data: ' . $e->getMessage());

            // return redirect()->route('categories')->with('error', 'Unable to load the quiz. Please try again later.');
            // Return the quiz view with an error message and no questions
            return view('quiz', [
                'categoryName' => 'Unknown Category',
                'questions' => [],
                'error' => 'Failed to fetch quiz data. Please check your internet connection.',
            ]);
        }
    }


    public function showResult(Request $request)
    {
        $userAnswers = json_decode($request->input('userAnswers'), true);
        $questions = json_decode($request->input('questions'), true);

        $score = 0;

        foreach ($userAnswers as $index => $answer) {
            if ($answer['selected'] === $questions[$index]['correct_answer']) {
                $score++;
            }
        }

        $percentage = ($score / count($questions)) * 100;
        $status = $percentage > 60 ? 'Winner' : ($percentage >= 40 ? 'Better' : 'Failed');

        return view('result', compact('questions', 'userAnswers', 'score', 'percentage', 'status'));
    }
}
