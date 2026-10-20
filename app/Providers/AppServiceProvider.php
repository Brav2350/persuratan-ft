<?php

namespace App\Providers;

use App\Models\PengajuanSurat;
use App\Observers\PengajuanSuratObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Rate limiter verifikasi publik
        RateLimiter::for('verifikasi-publik', function (Request $request) {
            return Limit::perMinute(15)->by($request->ip());
        });

        // Rate limiter export laporan
        RateLimiter::for('laporan-export', function (Request $request) {
            return Limit::perMinute(5)->by($request->user()?->id ?: $request->ip());
        });

        // Observer pengajuan surat
        PengajuanSurat::observe(PengajuanSuratObserver::class);

        // Paksa URL HTTPS di production
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
        RateLimiter::for('otp', function (Request $request) {
    return [
        Limit::perMinute(3)->by($request->ip()),
        Limit::perDay(15)->by(strtolower((string) $request->input('email', $request->ip()))),
    ];
});
    }
    
}