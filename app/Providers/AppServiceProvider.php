<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Log;
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
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url') . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        $this->connectNetworkShare();
        $this->connectNetworkShareImagePDS(); // 10.0.1.35 - open share, no credentials
    }

    // ✅ Clean separate method
    private function connectNetworkShare(): void
    {
        $host     = config('app.network_share.host');
        $name     = config('app.network_share.name');
        $username = config('app.network_share.username');
        $password = config('app.network_share.password');

        if (!$host || !$name || !$username || !$password) {
            Log::warning('Network share config is incomplete, skipping connection1.');
            return;
        }

        $sharePath = "\\\\{$host}\\{$name}";

        // Check if already connected
        exec("net use \"{$sharePath}\" 2>&1", $output, $code);

        if ($code !== 0) {
            // Not connected, connect now
            exec(
                "net use \"{$sharePath}\" \"{$password}\" /user:\"{$username}\" /persistent:yes 2>&1",
                $output,
                $code
            );

            if ($code !== 0) {
                // Log::error('Network share connection failed: ' . implode(' ', $output));
            } else {
                // Log::info("Network share connected: {$sharePath}");
            }
        } else {
            // Log::info("Network share already connected: {$sharePath}");
        }
    }

    private function connectNetworkShareImagePDS(): void
    {
        $host  = config('app.network_share_img_pds.host_image'); // 10.0.1.35
        $name  = config('app.network_share_img_pds.name_image'); // htdocs
        $username = config('app.network_share.username_image');
        $password = config('app.network_share.password_image');

        if (!$host || !$name || !$username || !$password) {
            Log::warning('Network share config is incomplete, skipping connection2.');
            return;
        }

        $sharePath = "\\\\{$host}\\{$name}";

        // Check if already connected
        exec("net use \"{$sharePath}\" 2>&1", $output, $code);

        if ($code !== 0) {
            // Not connected, connect now
            exec(
                "net use \"{$sharePath}\" \"{$password}\" /user:\"{$username}\" /persistent:yes 2>&1",
                $output,
                $code
            );

            if ($code !== 0) {
                // Log::error('Network share connection failed: ' . implode(' ', $output));
            } else {
                // Log::info("Network share connected: {$sharePath}");
            }
        } else {
            // Log::info("Network share already connected: {$sharePath}");
        }
    }
}
