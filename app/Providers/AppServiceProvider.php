<?php

namespace App\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use Spatie\Dropbox\Client as DropboxClient;
use Spatie\FlysystemDropbox\DropboxAdapter;

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
        Storage::extend('dropbox', function (Application $app, array $config) {
            $accessToken = $this->getDropboxAccessToken($config);

            $adapter = new DropboxAdapter(new DropboxClient($accessToken));
   
            return new FilesystemAdapter(
                new Filesystem($adapter, $config),
                $adapter,
                $config
            );
        });
    }

    /**
     * Fetch a new Dropbox access token using the refresh token.
     */
    private function getDropboxAccessToken(array $config): string
    {
        $response = Http::asForm()->post('https://api.dropbox.com/oauth2/token', [
            'grant_type'    => 'refresh_token',
            'refresh_token' => $config['refresh_token'],
            'client_id'     => $config['app_key'],
            'client_secret' => $config['app_secret'],
        ]);

        if ($response->successful()) {
            return $response->json()['access_token'];
        }

        throw new \Exception('Failed to refresh Dropbox access token.');
    }
}
