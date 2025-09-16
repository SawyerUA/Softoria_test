<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SearchController extends Controller
{
    public function search(Request $request){

        $keyword  = $request->input('searched_word');
        $website_name = $request->input('website_name');
        $location = $request->input('location', 'Ukraine');
        $language = $request->input('language', 'en');

        $post_array = [
            [
                "keyword" => $keyword,
                "language_code" => $language,
                "location_name" => $location,
                "search_engine" => "google.com",
            ]
        ];

        $api_url = 'https://api.dataforseo.com';
        $login = env('DATAFORSEO_LOGIN');
        $pass = env('DATAFORSEO_PASSWORD');

        try {

//            $response = Http::withBasicAuth($login, $pass)->post("$api_url/v3/serp/google/organic/live/advanced", $post_array);
            $response = Http::withOptions(['verify' => false])
                ->withBasicAuth($login, $pass)
                ->post("$api_url/v3/serp/google/organic/live/advanced", $post_array);

            $json = $response->json();
            $results = $json['tasks'][0]['result'][0]['items'] ?? [];
            $neededData = [];
            if (count($results) > 0){
                $neededData = array_filter($results, function ($record){
                    return $record['type'] == 'organic';
                });
            }

            return response()->json($neededData);

        } catch (\Exception $e) {

            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
