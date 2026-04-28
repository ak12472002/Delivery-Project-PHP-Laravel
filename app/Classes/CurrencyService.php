<?php

namespace App\Classes;
use App\Models\ApiLog;
use Illuminate\Support\Facades\Http;

class CurrencyService
{
    // base url for the exchange rate api
    private string $baseUrl = 'https://api.exchangerate-api.com/v4/latest';

    //convert an amount currency and log the api
    public function convert(float $amount, string $from = 'CAD', string $to = 'USD'): array
    {

        $endpoint = $this->baseUrl . '/' . $from;

        try {

            $response = Http::get($endpoint);
            $success = $response->successful();
            $responseData = $response->json();

            //logs to db
            ApiLog::query()->create([

                'service'=>'ExchangeRate API',
                'endpoint'=> $endpoint,
                'request_payload'=>['from' => $from, 'to' => $to, 'amount' => $amount],
                'response_payload'=> $responseData,
                'status_code'=> $response->status(),
                'success'=> $success,

            ]);

            if (!$success) {
                return [
                    'success'=> false,
                    'message'=> 'Failed to gett exchange rates',
                    'original_amount'=> $amount,
                    'original_currency'=>$from,
                ];
            }

            //get the rate for the target currency

            $rate = $responseData['rates'][$to] ?? null;

            if (!$rate) {
                return [
                    'success'=>false,
                    'message'=>"Currency [{$to}] not found in exchange rates.",
                    'original_amount'=>$amount,
                    'original_currency'=>$from,
                ];
            }

            $convertedAmount = round($amount * $rate, 2);

            return [
                'success'=>true,
                'original_amount'=>$amount,
                'original_currency'=>$from,
                'converted_amount'=> $convertedAmount,
                'converted_currency'=> $to,
                'rate'=> $rate,
            ];

        } catch (\Exception $e) {
            //log bad calls too
            ApiLog::query()->create([
                'service'=>'ExchangeRate API',
                'endpoint'=>$endpoint,
                'request_payload'=>['from'=> $from, 'to'=> $to, 'amount'=>$amount],
                'response_payload'=>['error'=>$e->getMessage()],
                'status_code'=>null,
                'success'=>false,
            ]);

            return [
                'success'=> false,
                'message'=>'Could not connect to exchange rate service.',
                'original_amount'=> $amount,
                'original_currency'=> $from,
            ];

        }
    }
}
