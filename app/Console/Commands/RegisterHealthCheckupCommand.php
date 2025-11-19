<?php

namespace App\Console\Commands;

use App\Models\UptimeMonitor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;

class RegisterHealthCheckupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'register:health-checkup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register a health checkup';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $services = config('monitor.services', []);
        $updatedServices = [];

        $this->info('Starting health checkup for ' . count($services) . ' services...');
        $this->newLine();

        foreach ($services as $service) {
            $name = $service['name'];
            $url = $service['url'];

            $startTime = microtime(true);
            $result = $this->checkService($url);
            $responseTime = (int) ((microtime(true) - $startTime) * 1000);

            $status = $result['status'];
            $errorMessage = $result['error'] ?? null;

            // Save to database
            UptimeMonitor::create([
                'service_name' => $name,
                'service_url' => $url,
                'status' => $status,
                'response_time' => $responseTime,
                'error_message' => $errorMessage,
                'checked_at' => now(),
            ]);

            $updatedServices[] = [
                'name' => $name,
                'url' => $url,
                'status' => $status,
            ];

            $statusIcon = $status === 'operational' ? '✓' : '✗';
            $timeInfo = $responseTime > 0 ? " ({$responseTime}ms)" : '';
            $this->line("{$statusIcon} {$name}: {$status}{$timeInfo}");
        }

        // Update the config file
        $this->updateConfigFile($updatedServices);

        $this->newLine();
        $this->info('Health checkup completed!');
    }

    /**
     * Check a service and return its status and error message.
     *
     * @param string $url
     * @return array{status: string, error?: string}
     */
    protected function checkService(string $url): array
    {
        // Check if it's a database connection
        if (str_starts_with($url, 'database::')) {
            return $this->checkDatabase($url);
        }

        // Check if it's a URL path
        if (str_starts_with($url, '/')) {
            return $this->checkHttpEndpoint($url);
        }

        // Default to operational if we can't determine the type
        return ['status' => 'operational'];
    }

    /**
     * Check database connection.
     *
     * @param string $url
     * @return array{status: string, error?: string}
     */
    protected function checkDatabase(string $url): array
    {
        try {
            // Extract connection name (e.g., 'database::default' -> 'default')
            $connection = str_replace('database::', '', $url);

            DB::connection($connection)->getPdo();
            return ['status' => 'operational'];
        } catch (\Exception $e) {
            return [
                'status' => 'down',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check HTTP endpoint.
     *
     * @param string $path
     * @return array{status: string, error?: string}
     */
    protected function checkHttpEndpoint(string $path): array
    {
        try {
            $appUrl = config('app.url', 'http://localhost');
            $fullUrl = rtrim($appUrl, '/') . $path;

            $response = Http::timeout(5)->get($fullUrl);

            // Consider 2xx and 3xx as operational
            if ($response->successful() || $response->redirect()) {
                return ['status' => 'operational'];
            }

            // 4xx might be degraded, 5xx is down
            if ($response->status() >= 400 && $response->status() < 500) {
                return [
                    'status' => 'degraded',
                    'error' => "HTTP {$response->status()}",
                ];
            }

            return [
                'status' => 'down',
                'error' => "HTTP {$response->status()}",
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'down',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Update the config file with new statuses.
     *
     * @param array $services
     * @return void
     */
    protected function updateConfigFile(array $services): void
    {
        $configPath = config_path('monitor.php');
        $content = "<?php\n\nreturn [\n    'services' => [\n";

        foreach ($services as $service) {
            $name = addslashes($service['name']);
            $url = addslashes($service['url']);
            $status = addslashes($service['status']);

            $content .= "        [\n";
            $content .= "            'name' => '{$name}',\n";
            $content .= "            'url' => '{$url}',\n";
            $content .= "            'status' => '{$status}',\n";
            $content .= "        ],\n";
        }

        $content .= "    ],\n];\n";

        File::put($configPath, $content);
    }
}
