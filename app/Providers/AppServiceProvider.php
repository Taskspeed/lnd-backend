<?php

namespace App\Providers;

use App\Models\Employee\NominatedEmployee;
use App\Models\RSP\vwEmployee;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Events\CommandStarting;
use Illuminate\Support\Facades\Event;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Question\Question;
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

        Relation::morphMap(['employee' => NominatedEmployee::class]);
 if ($this->app->runningInConsole()) {
        Event::listen(CommandStarting::class, function (CommandStarting $event) {
            $dangerous = ['migrate:fresh', 'migrate:refresh', 'migrate:reset', 'migrate:rollback', 'db:wipe'];

            if (! in_array($event->command, $dangerous, true)) {
                return;
            }

            if (! $event->input->isInteractive()) {
                $event->output->writeln('<error>Bawal ito sa non-interactive mode.</error>');
                exit(1);
            }

            $guardPassword = 'Millan2026';

            $connection = $event->input->getParameterOption('--database') ?: config('database.default');
            $dbName     = config("database.connections.{$connection}.database");

            $event->output->writeln("<error>Wag gamitin ang command na ito pwede ma apektohan ang ibang database naka connect sa LND project '{$event->command}' ang database '{$dbName}' ({$connection}).</error>");

            $question = (new Question('Password: '))
                ->setHidden(true)
                ->setHiddenFallback(true);

            $typed = (string) (new QuestionHelper())->ask($event->input, $event->output, $question);

            if (! hash_equals($guardPassword, $typed)) {
                $event->output->writeln('<error>Incorrect password please contact the developer for this concern</error>');
                exit(1);
            }
        });
    }
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
