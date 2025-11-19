<?php

namespace App\Http\Controllers;

use App\Models\UptimeMonitor;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UptimeMonitorController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $title = __('Uptime Monitor');
        $configServices = config('monitor.services', []);

        $services = collect($configServices)->map(function ($service) {
            return $this->getServiceWithHistory($service);
        })->toArray();

        // Calculate overall status
        $allOperational = collect($services)->every(function ($service) {
            return $service['status'] === 'operational';
        });

        $lastUpdated = UptimeMonitor::max('checked_at');
        $lastUpdatedText = $lastUpdated
            ? Carbon::parse($lastUpdated)?->diffForHumans()
            : 'Never';

        // Get past incidents
        $incidents = $this->getPastIncidents($configServices);

        return view('status.index', compact('title', 'services', 'allOperational', 'lastUpdatedText', 'incidents'));
    }

    /**
     * Get service data with history from database.
     *
     * @param array $service
     * @return array
     */
    private function getServiceWithHistory(array $service): array
    {
        $serviceName = $service['name'];
        $serviceUrl = $service['url'];

        // Get the latest status
        $latestCheck = UptimeMonitor::where('service_name', $serviceName)
            ->where('service_url', $serviceUrl)
            ->latest('checked_at')
            ->first();

        $currentStatus = $latestCheck ? $latestCheck->status : 'operational';

        // Get history for last 60 days (2 months)
        $history = $this->generateUptimeHistory($serviceName, $serviceUrl);

        // Calculate uptime percentage
        $uptimePercentage = $this->calculateUptimePercentage($history);

        return [
            'name' => $serviceName,
            'url' => $serviceUrl,
            'status' => $currentStatus,
            'status_class' => $this->getStatusClass($currentStatus),
            'status_label' => $this->getStatusLabel($currentStatus),
            'history' => $history,
            'uptime' => $uptimePercentage,
        ];
    }

    /**
     * Generate uptime history for the last 60 days.
     *
     * @param string $serviceName
     * @param string $serviceUrl
     * @return array
     */
    private function generateUptimeHistory(string $serviceName, string $serviceUrl): array
    {
        $daysAgo = 60;
        $history = [];

        // Get all checks for this service in the last 60 days
        $checks = UptimeMonitor::where('service_name', $serviceName)
            ->where('service_url', $serviceUrl)
            ->where('checked_at', '>=', now()->subDays($daysAgo))
            ->orderBy('checked_at', 'asc')
            ->get()
            ->groupBy(function ($check) {
                return Carbon::parse($check->checked_at)->format('Y-m-d');
            });

        // For each day, determine the status
        for ($i = $daysAgo - 1; $i >= 0; $i--) {
            $dateObj = now()->subDays($i);
            $date = $dateObj->format('Y-m-d');
            $dateFormatted = $dateObj->format('M d, Y');

            if (isset($checks[$date])) {
                // Get the worst status for the day
                $dayChecks = $checks[$date];
                $statusCounts = $dayChecks->countBy('status');

                // If any time during the day it's down, show as degraded (not down)
                // Prioritize: down (show as degraded) > degraded > operational
                if ($statusCounts->get('down', 0) > 0) {
                    $statusValue = 'degraded'; // Show down days as degraded in label
                    $statusClass = 'bg-danger'; // But keep red color
                    $statusLabel = 'Degraded';
                } elseif ($statusCounts->get('degraded', 0) > 0) {
                    $statusValue = 'degraded';
                    $statusClass = 'bg-warning';
                    $statusLabel = 'Degraded';
                } else {
                    $statusValue = 'operational';
                    $statusClass = 'bg-success';
                    $statusLabel = 'Operational';
                }
            } else {
                // No data for this day - show as unknown/gray
                $statusValue = 'unknown';
                $statusClass = 'bg-secondary bg-opacity-25';
                $statusLabel = 'Unknown';
            }

            $history[] = [
                'class' => $statusClass,
                'label' => $statusLabel,
                'status' => $statusValue,
                'date' => $dateFormatted,
            ];
        }

        return $history;
    }

    /**
     * Calculate uptime percentage from history.
     *
     * @param array $history
     * @return float
     */
    private function calculateUptimePercentage(array $history): float
    {
        if (empty($history)) {
            return 100.0;
        }

        $total = count($history);
        $operational = 0;

        foreach ($history as $day) {
            // Only count fully operational days (degraded/down days are not counted)
            if (isset($day['status']) && $day['status'] === 'operational') {
                $operational++;
            }
        }

        return round(($operational / $total) * 100, 1);
    }

    /**
     * Get status class for a status value.
     *
     * @param string $status
     * @return string
     */
    private function getStatusClass(string $status): string
    {
        return match ($status) {
            'operational' => 'bg-success',
            'degraded' => 'bg-warning',
            'down' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    /**
     * Get status label for a status value.
     *
     * @param string $status
     * @return string
     */
    private function getStatusLabel(string $status): string
    {
        return match ($status) {
            'operational' => 'Operational',
            'degraded' => 'Degraded Performance',
            'down' => 'Major Outage',
            default => 'Unknown',
        };
    }

    /**
     * Get past incidents from database.
     *
     * @param array $services
     * @return array
     */
    private function getPastIncidents(array $services): array
    {
        $incidents = [];
        $daysToCheck = 60; // Check last 60 days for incidents

        foreach ($services as $service) {
            $serviceName = $service['name'];
            $serviceUrl = $service['url'];

            // Get all checks for this service in the last 60 days
            $checks = UptimeMonitor::where('service_name', $serviceName)
                ->where('service_url', $serviceUrl)
                ->where('checked_at', '>=', now()->subDays($daysToCheck))
                ->orderBy('checked_at', 'asc')
                ->get()
                ->groupBy(function ($check) {
                    return Carbon::parse($check->checked_at)->format('Y-m-d');
                });

            $currentIncident = null;

            // Check each day for incidents
            for ($i = $daysToCheck - 1; $i >= 0; $i--) {
                $dateObj = now()->subDays($i)->startOfDay();
                $date = $dateObj->format('Y-m-d');

                if (isset($checks[$date])) {
                    $dayChecks = $checks[$date];
                    $statusCounts = $dayChecks->countBy('status');

                    // Check if this day has any issues (down or degraded)
                    $hasDown = $statusCounts->get('down', 0) > 0;
                    $hasDegraded = $statusCounts->get('degraded', 0) > 0;

                    if ($hasDown || $hasDegraded) {
                        // Determine severity
                        $severity = $hasDown ? 'down' : 'degraded';
                        $title = $hasDown
                            ? $this->getIncidentTitle($serviceName, 'down')
                            : $this->getIncidentTitle($serviceName, 'degraded');

                        $description = $hasDown
                            ? $this->getIncidentDescription($serviceName, 'down')
                            : $this->getIncidentDescription($serviceName, 'degraded');

                        // Get error message from checks
                        $errorMessage = $dayChecks->firstWhere('error_message')?->error_message;

                        if (
                            $currentIncident === null ||
                            !$this->isConsecutiveDay($currentIncident['end_date'], $dateObj)
                        ) {
                            // Start new incident
                            if ($currentIncident !== null) {
                                $incidents[] = $currentIncident;
                            }

                            $currentIncident = [
                                'service_name' => $serviceName,
                                'start_date' => $dateObj,
                                'end_date' => $dateObj,
                                'severity' => $severity,
                                'title' => $title,
                                'description' => $description,
                                'error_message' => $errorMessage,
                                'status' => 'resolved', // Assume resolved if it's in the past
                            ];
                        } else {
                            // Extend current incident
                            $currentIncident['end_date'] = $dateObj;
                            // Update severity if we found a more severe issue
                            if ($severity === 'down' && $currentIncident['severity'] !== 'down') {
                                $currentIncident['severity'] = 'down';
                                $currentIncident['title'] = $this->getIncidentTitle($serviceName, 'down');
                                $currentIncident['description'] = $this->getIncidentDescription($serviceName, 'down');
                            }
                            if ($errorMessage && !$currentIncident['error_message']) {
                                $currentIncident['error_message'] = $errorMessage;
                            }
                        }
                    } else {
                        // Day is operational, close current incident if exists
                        if ($currentIncident !== null) {
                            $incidents[] = $currentIncident;
                            $currentIncident = null;
                        }
                    }
                } else {
                    // No data for this day, close current incident if exists
                    if ($currentIncident !== null) {
                        $incidents[] = $currentIncident;
                        $currentIncident = null;
                    }
                }
            }

            // Add final incident if exists
            if ($currentIncident !== null) {
                $incidents[] = $currentIncident;
            }
        }

        // Sort by start date (newest first) and limit to 10 most recent
        usort($incidents, function ($a, $b) {
            return $b['start_date']->timestamp <=> $a['start_date']->timestamp;
        });

        return array_slice($incidents, 0, 10);
    }

    /**
     * Check if a date is consecutive to another date.
     *
     * @param Carbon $previousDate
     * @param Carbon $currentDate
     * @return bool
     */
    private function isConsecutiveDay(Carbon $previousDate, Carbon $currentDate): bool
    {
        return $currentDate->diffInDays($previousDate) === 1;
    }

    /**
     * Get incident title based on service and severity.
     *
     * @param string $serviceName
     * @param string $severity
     * @return string
     */
    private function getIncidentTitle(string $serviceName, string $severity): string
    {
        if ($severity === 'down') {
            return "{$serviceName} Outage";
        } else {
            return "{$serviceName} Performance Issues";
        }
    }

    /**
     * Get incident description based on service and severity.
     *
     * @param string $serviceName
     * @param string $severity
     * @return string
     */
    private function getIncidentDescription(string $serviceName, string $severity): string
    {
        if ($severity === 'down') {
            return "We experienced an outage affecting {$serviceName}. Our team investigated and resolved the issue.";
        } else {
            return "We detected performance degradation affecting {$serviceName}. The issue has been resolved.";
        }
    }
}
