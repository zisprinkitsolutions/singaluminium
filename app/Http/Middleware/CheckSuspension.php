<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class CheckSuspension
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        // Fetch API endpoint and client ID from configuration
        // $api_endpoint = \App\AppConfig::where('config_name', 'api_endpoint')->first()->config_value;
        // $client_id = \App\AppConfig::where('config_name', 'client_id')->first()->config_value;
        // $api_url = "{$api_endpoint}/api/suspension/{$client_id}";
        // $update_api_url = "{$api_endpoint}/api/notification-log-update";

        // // Make an HTTP GET request to check suspension status
        // $response = Http::get($api_url);
        // // Check if the response is successful and the user is suspended
        // if ($response->successful() &&  $response['status']) {
        //     $message = $response['message'];
        //     $notification_history_id = $response['notification_history_id'];
        //     $user_name = Auth::user() ? Auth::user()->name : 'No Name Found';
        //     $type = 'Suspension';

        //     $postData = [
        //         'user_name' => $user_name,
        //         'client_id' => $client_id,
        //         'notification_history_id' => $notification_history_id,
        //         'type' => $type,
        //     ];

        //     // Post to update the API
        //     Http::post($update_api_url, $postData);

        //     // Return a view with the suspension message
        //     return response()->view('api-controll-module.redirect-page', compact('message'));
        // }

        // If not suspended, continue with the request
        return $next($request);
    }
}
