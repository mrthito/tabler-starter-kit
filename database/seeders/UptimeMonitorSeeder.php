<?php

namespace Database\Seeders;

use App\Models\UptimeMonitor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UptimeMonitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = config('monitor.services', []);

        if (empty($services)) {
            $this->command->warn('No services found in config/monitor.php');
            return;
        }

        $this->command->info('Generating 100 days of historical data...');

        foreach ($services as $service) {
            $this->generateServiceHistory($service['name'], $service['url']);
        }

        $this->command->info('Historical data generated successfully!');
    }

    /**
     * Generate 100 days of history for a service.
     *
     * @param string $serviceName
     * @param string $serviceUrl
     * @return void
     */
    private function generateServiceHistory(string $serviceName, string $serviceUrl): void
    {
        $days = 100;
        $checksPerDay = 6; // 6 checks per day (every 4 hours)
        $records = [];

        $startDate = Carbon::now()->subDays($days);

        for ($day = 0; $day < $days; $day++) {
            $currentDate = $startDate->copy()->addDays($day);

            // Determine if this day should have any issues
            // 90% operational days, 5% down days, 5% degraded days
            $dayType = $this->getDayType();

            for ($check = 0; $check < $checksPerDay; $check++) {
                $checkTime = $currentDate->copy()
                    ->addHours($check * 4)
                    ->addMinutes(rand(0, 59))
                    ->addSeconds(rand(0, 59));

                // Determine status for this check
                $status = $this->getStatusForCheck($dayType, $check, $checksPerDay);

                $records[] = [
                    'service_name' => $serviceName,
                    'service_url' => $serviceUrl,
                    'status' => $status,
                    'response_time' => $this->getResponseTime($status),
                    'error_message' => $this->getErrorMessage($status),
                    'checked_at' => $checkTime,
                    'created_at' => $checkTime,
                    'updated_at' => $checkTime,
                ];
            }
        }

        // Insert in chunks for better performance
        foreach (array_chunk($records, 500) as $chunk) {
            UptimeMonitor::insert($chunk);
        }

        $this->command->line("  ✓ Generated data for: {$serviceName}");
    }

    /**
     * Get day type (mostly operational, sometimes down/degraded).
     *
     * @return string
     */
    private function getDayType(): string
    {
        $rand = rand(1, 100);

        if ($rand <= 90) {
            return 'operational'; // 90% operational days
        } elseif ($rand <= 95) {
            return 'down'; // 5% days with down status
        } else {
            return 'degraded'; // 5% days with degraded status
        }
    }

    /**
     * Get status for a specific check based on day type.
     *
     * @param string $dayType
     * @param int $checkIndex
     * @param int $totalChecks
     * @return string
     */
    private function getStatusForCheck(string $dayType, int $checkIndex, int $totalChecks): string
    {
        if ($dayType === 'operational') {
            // 95% chance of operational, 3% degraded, 2% down
            $rand = rand(1, 100);
            if ($rand <= 95) {
                return 'operational';
            } elseif ($rand <= 98) {
                return 'degraded';
            } else {
                return 'down';
            }
        } elseif ($dayType === 'down') {
            // On down days, mix of down and operational
            // 60% down, 30% operational, 10% degraded
            $rand = rand(1, 100);
            if ($rand <= 60) {
                return 'down';
            } elseif ($rand <= 90) {
                return 'operational';
            } else {
                return 'degraded';
            }
        } else { // degraded
            // On degraded days, mix of degraded and operational
            // 70% degraded, 25% operational, 5% down
            $rand = rand(1, 100);
            if ($rand <= 70) {
                return 'degraded';
            } elseif ($rand <= 95) {
                return 'operational';
            } else {
                return 'down';
            }
        }
    }

    /**
     * Get response time based on status.
     *
     * @param string $status
     * @return int|null
     */
    private function getResponseTime(string $status): ?int
    {
        return match ($status) {
            'operational' => rand(50, 200), // 50-200ms
            'degraded' => rand(500, 2000), // 500-2000ms
            'down' => null, // No response time when down
            default => rand(100, 300),
        };
    }

    /**
     * Get error message based on status.
     *
     * @param string $status
     * @return string|null
     */
    private function getErrorMessage(string $status): ?string
    {
        return match ($status) {
            'operational' => null,
            'degraded' => 'High latency detected',
            'down' => 'Connection timeout',
            default => null,
        };
    }
}
