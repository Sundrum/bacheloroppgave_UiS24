<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use Log;
use Redirect;

class Api extends Model {

    public static function getApi($url) {
        $client = new Client();

        $headers = [
            'Authorization' => env('API_TOKEN'),
        ];

        try {
            $response = $client->get(env('API_URL').$url, [
                'headers' => $headers,
                'timeout' => 10,
            ]);
            $data = $response->getBody()->getContents();
            $data = json_decode($data,true);
            return $data;
        } catch (RequestException $e) {
            if ($e->getResponse()) {
                if ($e->getResponse()->getStatusCode() == '400') {
                    return 'Error';
                } else if ($e->getResponse()->getStatusCode() == '500'){
                    return 'error';
                }
            }
        }
    }

    public static function postApi($url) {
        $client = new Client();

        $headers = [
            'Authorization' => env('API_TOKEN'),
        ];
        try {
            $response = $client->post(env('API_URL').$url, [
                'headers' => $headers,
                'timeout' => 10,
            ]);
            $data = $response->getBody()->getContents();
            $data = json_decode($data,true);
            return $data;
        } catch (RequestException $e) {
            if ($e->getResponse()->getStatusCode() == '400') {
                return 'error';
            } else if ($e->getResponse()->getStatusCode() == '500'){
                return 'error';
            }
        }
    }

    public static function patchApi($url) {
        $client = new Client();

        $headers = [
            'Authorization' => env('API_TOKEN'),
        ];

        try {
            $response = $client->patch(env('API_URL').$url, [
                'headers' => $headers,
                'timeout' => 10,
            ]);
            $data = $response->getBody()->getContents();
            $data = json_decode($data,true);
            return $data;
        } catch (RequestException $e) {
            if ($e->getResponse()) {
                if ($e->getResponse()->getStatusCode() == '400') {
                    return 'error';
                } else if ($e->getResponse()->getStatusCode() == '500'){
                    return 'error';
                }
            } else {
                return 'No connection with 7Sense Portal. Please try again later';
            }
        }
    }

    public static function deleteApi($url) {
        $client = new Client();

        $headers = [
            'Authorization' => env('API_TOKEN'),
        ];

        try {
            $response = $client->delete(env('API_URL').$url, [
                'headers' => $headers,
                'timeout' => 10,
            ]);
            $data = $response->getBody()->getContents();
            $data = json_decode($data,true);
            return $data;
        } catch (RequestException $e) {
            if ($e->getResponse()->getStatusCode() == '400') {
                return 'error';
            } else if ($e->getResponse()->getStatusCode() == '500'){
                return 'error';
            }
        }
    }

    // Queue Api
    public static function getQueue($url) {
        $client = new Client();

        try {
            $response = $client->get(env('API_URL').$url, [
                'timeout' => 10,
            ]);
            $data = $response->getBody()->getContents();
            $data = json_decode($data,true);
            return $data;
        } catch (RequestException $e) {
            if ($e->getResponse()->getStatusCode() == '400') {
                return 'error';
            } else if ($e->getResponse()->getStatusCode() == '500'){
                return 'error';
            }
        }
    }

    public static function postQueue($url) {
        $client = new Client();

        try {
            $response = $client->post(env('API_URL').$url, [
                'timeout' => 10,
            ]);
            $data = $response->getBody()->getContents();
            $data = json_decode($data,true);
            return $data;
        } catch (RequestException $e) {
            if ($e->getResponse()->getStatusCode() == '400') {
                return 'error';
            } else if ($e->getResponse()->getStatusCode() == '500'){
                return 'error';
            }
        }
    }

    public static function patchQueue($url) {
        $client = new Client();

        try {
            $response = $client->patch(env('API_URL').$url, [
                'timeout' => 10,
            ]);
            $data = $response->getBody()->getContents();
            $data = json_decode($data,true);
            return $data;
        } catch (RequestException $e) {
            if ($e->getResponse()->getStatusCode() == '400') {
                return 'error';
            } else if ($e->getResponse()->getStatusCode() == '500'){
                return 'error';
            } else if ($e->getResponse()->getStatusCode() == null){
                return 'No connection with 7Sense Portal.';
            }
        }
    }

    public static function deleteQueue($url) {
        $client = new Client();

        try {
            $response = $client->delete(env('API_URL').$url, [
                'timeout' => 10,
            ]);
            $data = $response->getBody()->getContents();
            $data = json_decode($data,true);
            return $data;
        } catch (RequestException $e) {
            if ($e->getResponse()->getStatusCode() == '400') {
                return 'error';
            } else if ($e->getResponse()->getStatusCode() == '500'){
                return 'error';
            }
        }
    }

    public static function getProxy($url) {
        $client = new Client();
        $headers = [
            'Authorization' => 'Bearer 39792997464529572645497315286824',
        ];

        try {
            $response = $client->get(env('API_PROXY').$url, [
                // 'headers' => $headers,
                'timeout' => 10,
            ]);
            $data = $response->getBody()->getContents();
            $data = json_decode($data,true);
            return $data;
        } catch (RequestException $e) {
            if ($e->getResponse()->getStatusCode() == '400') {
                return 'error';
            } else if ($e->getResponse()->getStatusCode() == '500'){
                return 'error';
            }
        }
    }

    public static function postProxy($url) {
        $client = new Client();
        // $headers = [
        //     'Authorization' => 'Bearer 39792997464529572645497315286824',
        // ];

        try {
            $response = $client->post(env('API_PROXY').$url, [
                // 'headers' => $headers,
                'timeout' => 10,
            ]);
            $data = $response->getBody()->getContents();
            $data = json_decode($data,true);
            return $data;
        } catch (RequestException $e) {
            if ($e->getResponse()->getStatusCode() == '400') {
                return 'error';
            } else if ($e->getResponse()->getStatusCode() == '500'){
                return 'error';
            }
        }
    }

    public static function deleteProxy($url) {
        $client = new Client();
        // $headers = [
        //     'Authorization' => 'Bearer 39792997464529572645497315286824',
        // ];

        try {
            $response = $client->delete(env('API_PROXY').$url, [
                // 'headers' => $headers,
                'timeout' => 10,
            ]);
            $data = $response->getBody()->getContents();
            $data = json_decode($data,true);
            return $data;
        } catch (RequestException $e) {
            if ($e->getResponse()->getStatusCode() == '400') {
                return 'error';
            } else if ($e->getResponse()->getStatusCode() == '500'){
                return 'error';
            }
        }
    }

    public static function getDummy($url) {
        $client = new Client();

        try {
            $response = $client->get('https://dummyjson.com/'.$url, [
                'timeout' => 10,
            ]);
            $data = $response->getBody()->getContents();
            $data = json_decode($data,true);
            return $data;
        } catch (RequestException $e) {
            if ($e->getResponse()->getStatusCode() == '400') {
                return 'error';
            } else if ($e->getResponse()->getStatusCode() == '500'){
                return 'error';
            }
        }
    }

    public static function SMS($content) {
        $client = new Client();
        
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => env('API_SMS_TOKEN'),
            'charset' => 'utf-8',
        ];

        try {
            $response = $client->post(env('API_SMS_URL'), [
                'headers' => $headers,
                'body' => $content,
                'timeout' => 10,
            ]);
            $data = $response->getBody()->getContents();
            $data = json_decode($data,true);
            return $data;

        } catch (RequestException $e) {
            if ($e->getResponse()->getStatusCode() == '400') {
                return 'error';
            } else if ($e->getResponse()->getStatusCode() == '500'){
                return 'error';
            } 
        }
    }

    public static function postOpenAi($dataset) {
        $client = new Client();
        
        $headers = [
            'Authorization' => env('API_OPENAI_TOKEN'),
            'Content-Type' => "application/json"
        ];

        try {
            $response = $client->post(env('API_OPENAI_URL'), [
                'headers' => $headers,
                'body' => json_encode($dataset,true),
                'timeout' => 80,
            ]);
            $data = $response->getBody()->getContents();
            return $data;
        } catch (RequestException $e) {
            Log::error($e);
            if ($e->getResponse()->getStatusCode() == '400') {
                return 'error';
            } else if ($e->getResponse()->getStatusCode() == '500'){
                return 'error';
            } else {
                return $e;
            }
        }

        Log::error('Error from Open AI');
        
    }
}
