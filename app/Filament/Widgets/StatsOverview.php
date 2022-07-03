<?php

namespace App\Filament\Widgets;

use App\Models\Forum;
use App\Models\Masjid;
use App\Models\Product;
use App\Models\User;
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
        return [
            Card::make("Asia/Seoul Fajr", $this->getPrayerTime()->timings->Fajr)
                ->description($this->getPrayerTime()->date->readable)
                ->descriptionIcon('heroicon-s-user-group')
                ->color('success'),
            Card::make("Asia/Seoul Sunrise", $this->getPrayerTime()->timings->Sunrise)
                ->description($this->getPrayerTime()->date->readable)
                ->descriptionIcon('heroicon-s-user-group')
                ->color('success'),
            Card::make("Asia/Seoul Dhuhr", $this->getPrayerTime()->timings->Dhuhr)
                ->description($this->getPrayerTime()->date->readable)
                ->descriptionIcon('heroicon-s-user-group')
                ->color('success'),
            Card::make("Asia/Seoul Asr", $this->getPrayerTime()->timings->Asr)
                ->description($this->getPrayerTime()->date->readable)
                ->descriptionIcon('heroicon-s-user-group')
                ->color('success'),
            Card::make("Asia/Seoul Sunset", $this->getPrayerTime()->timings->Sunset)
                ->description($this->getPrayerTime()->date->readable)
                ->descriptionIcon('heroicon-s-user-group')
                ->color('success'),
            Card::make("Asia/Seoul Maghrib", $this->getPrayerTime()->timings->Maghrib)
                ->description($this->getPrayerTime()->date->readable)
                ->descriptionIcon('heroicon-s-user-group')
                ->color('success'),
            Card::make("Asia/Seoul Isha", $this->getPrayerTime()->timings->Isha)
                ->description($this->getPrayerTime()->date->readable)
                ->descriptionIcon('heroicon-s-user-group')
                ->color('success'),
            Card::make("Asia/Seoul Imsak", $this->getPrayerTime()->timings->Imsak)
                ->description($this->getPrayerTime()->date->readable)
                ->descriptionIcon('heroicon-s-user-group')
                ->color('success'),
            Card::make("Asia/Seoul Midnight", $this->getPrayerTime()->timings->Midnight)
                ->description($this->getPrayerTime()->date->readable)
                ->descriptionIcon('heroicon-s-user-group')
                ->color('success'),
        ];
        // return [
        //     Card::make('Users', User::where('roles_id',2)->count())
        //         ->description('Total Customer')
        //         ->descriptionIcon('heroicon-s-user-group')
        //         ->color('success'),
        //     Card::make('Masjids', Masjid::count())
        //         ->description('Total Masjid')
        //         ->descriptionIcon('heroicon-s-home')
        //         ->color('success'),
        //     Card::make('Products', Product::count())
        //         ->description('Total Product')
        //         ->descriptionIcon('heroicon-s-cube')
        //         ->color('success'),
        //     Card::make('Forums', Forum::count())
        //         ->description('Total Forum')
        //         ->descriptionIcon('heroicon-s-chat-alt-2')
        //         ->color('success'),
        // ];
    }
}
