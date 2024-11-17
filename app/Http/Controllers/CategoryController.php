<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class CategoryController extends Controller
{

    public function index(Request $request)
    {
        // Check if the request is AJAX
        if ($request->ajax()) {
            $client = new Client();
            $response = $client->get('https://opentdb.com/api_category.php');
            $categories = json_decode($response->getBody(), true)['trivia_categories'];

            $perPage = 6;
            $page = $request->input('page', 1);
            $total = count($categories);
            $pagedData = array_slice($categories, ($page - 1) * $perPage, $perPage);

            return response()->json([
                'categories' => $pagedData,
                'totalPages' => ceil($total / $perPage),
                'currentPage' => $page,
            ]);
        }

        // Otherwise, load the main categories page
        return view('categories');
    }

    public function fetchCategories(Request $request)
    {
        $client = new Client();
        $response = $client->get('https://opentdb.com/api_category.php');
        $categories = json_decode($response->getBody(), true)['trivia_categories'];

        $perPage = 6; // Number of categories per page
        $page = $request->input('page', 1);
        $total = count($categories);
        $pagedData = array_slice($categories, ($page - 1) * $perPage, $perPage);

        return response()->json([
            'categories' => $pagedData,
            'totalPages' => ceil($total / $perPage),
            'currentPage' => $page,
        ]);
    }
}
