<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{
    public function getPrayerTime()
    {
        $client = new Client();
        $uri = "https://api.aladhan.com/v1/timings/1656852244596?latitude=37.5640455&longitude=126.8340033&method=11";

        $response = $client->request('GET', $uri, [
            'verify' => false,
        ]);

        $body = json_decode($response->getBody());
        $data = $body->data;

        if (filter_var($uri, FILTER_VALIDATE_URL) == false) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => $uri." is not a valid URL", 
                'data' => null
            ]);
        }else{
            return $data;
        }
    }

    protected function getCards(): array
    {
        $now = Carbon::now();
        $date = Carbon::parse($now)->format('d F y');
        return [
            Card::make("Asia/Seoul Fajr", $this->getPrayerTime()->timings->Fajr)
                ->description($date)
                ->descriptionIcon('heroicon-s-user-group')
                ->color('success'),
            Card::make("Asia/Seoul Sunrise", $this->getPrayerTime()->timings->Sunrise)
                ->description($date)
                ->descriptionIcon('heroicon-s-user-group')
                ->color('success'),
            Card::make("Asia/Seoul Dhuhr", $this->getPrayerTime()->timings->Dhuhr)
                ->description($date)
                ->descriptionIcon('heroicon-s-user-group')
                ->color('success'),
            Card::make("Asia/Seoul Asr", $this->getPrayerTime()->timings->Asr)
                ->description($date)
                ->descriptionIcon('heroicon-s-user-group')
                ->color('success'),
            Card::make("Asia/Seoul Sunset", $this->getPrayerTime()->timings->Sunset)
                ->description($date)
                ->descriptionIcon('heroicon-s-user-group')
                ->color('success'),
            Card::make("Asia/Seoul Maghrib", $this->getPrayerTime()->timings->Maghrib)
                ->description($date)
                ->descriptionIcon('heroicon-s-user-group')
                ->color('success'),
            Card::make("Asia/Seoul Isha", $this->getPrayerTime()->timings->Isha)
                ->description($date)
                ->descriptionIcon('heroicon-s-user-group')
                ->color('success'),
            Card::make("Asia/Seoul Imsak", $this->getPrayerTime()->timings->Imsak)
                ->description($date)
                ->descriptionIcon('heroicon-s-user-group')
                ->color('success'),
            Card::make("Asia/Seoul Midnight", $this->getPrayerTime()->timings->Midnight)
                ->description($date)
                ->descriptionIcon('heroicon-s-user-group')
                ->color('success'),
        ];
    }
}
