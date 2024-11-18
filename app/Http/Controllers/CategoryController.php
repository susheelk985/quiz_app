<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
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
            } catch (RequestException $e) {
                // Handle API or internet failure
                return response()->json([
                    'error' => 'Failed to fetch categories. Please check your internet connection.',
                ], 500);
            }
        }

        return view('categories');
    }

    public function fetchCategories(Request $request)
    {
        try {
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
        } catch (RequestException $e) {
            // Handle API or internet failure
            // return response()->json([
            //     'error' => 'Unable to fetch categories at this time. Please try again later.',
            // ], 500);

            return redirect()->route('categories')->with('error', 'Unable to fetch categories at this time. Please try again later.');
        }
    }
}
