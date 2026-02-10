<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class YahooTextAnalysisService
{
  /**
   * Yahoo! APIで形態素解析を行い、単語（表記）のリストを返す
   */
  public function get_tokens(string $text): array
  {
    if (empty($text)) {
      return [];
    }

    $client_id = config('services.yahoo.client_id');
    $url = 'https://jlp.yahooapis.jp/MAService/V2/parse';

    try {
      // リクエスト送信
      $response = Http::timeout(10)->withHeaders([
        'User-Agent' => "Yahoo AppID: {$client_id}",
      ])->post($url, [
        'id'      => (string) rand(1000, 9999),
        'jsonrpc' => '2.0',
        'method'  => 'jlp.maservice.parse',
        'params'  => [
          'q' => $text,
        ],
      ]);

      if ($response->successful()) {
        $body = $response->json();
        $tokens = data_get($body, 'result.tokens', []);

        $surfaces = array_map(function ($token) {
          return $token[0] ?? '';
        }, $tokens);

        return $surfaces;
      }

      Log::error('Yahoo API Error: ' . $response->body());
      return [];
    } catch (\Exception $e) {
      Log::error('Yahoo API Exception: ' . $e->getMessage());
      return [];
    }
  }
}
