<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class SystemLogController extends Controller
{
    public function index(Request $request)
    {
        $logDir = storage_path('logs');
        $files = [];

        if (File::exists($logDir)) {
            $rawFiles = File::files($logDir);
            foreach ($rawFiles as $file) {
                if ($file->getExtension() === 'log') {
                    $files[] = [
                        'name' => $file->getFilename(),
                        'size' => $this->formatBytes($file->getSize()),
                        'bytes' => $file->getSize(),
                        'modified' => date('Y-m-d H:i:s', $file->getMTime()),
                    ];
                }
            }
        }

        // Sort files by modified date descending
        usort($files, fn($a, $b) => strcmp($b['modified'], $a['modified']));

        $currentFile = $request->input('file', 'laravel.log');
        $filePath = $logDir . DIRECTORY_SEPARATOR . basename($currentFile);

        $entries = [];
        $counts = [
            'total' => 0,
            'error' => 0,
            'warning' => 0,
            'info' => 0,
            'debug' => 0,
            'other' => 0,
        ];
        $fileSizeFormatted = '0 B';

        if (File::exists($filePath)) {
            $fileSizeFormatted = $this->formatBytes(File::size($filePath));
            $parsed = $this->parseLogFile($filePath);
            $entries = $parsed['entries'];
            $counts = $parsed['counts'];
        }

        // Filter by Level
        $selectedLevel = strtolower($request->input('level', ''));
        if (!empty($selectedLevel) && $selectedLevel !== 'all') {
            $entries = array_filter($entries, function ($item) use ($selectedLevel) {
                return strtolower($item['level']) === $selectedLevel;
            });
        }

        // Filter by Search Query
        $search = trim($request->input('search', ''));
        if (!empty($search)) {
            $entries = array_filter($entries, function ($item) use ($search) {
                return stripos($item['message'], $search) !== false
                    || stripos($item['stack'], $search) !== false
                    || stripos($item['timestamp'], $search) !== false;
            });
        }

        // Manual Pagination
        $perPage = 25;
        $page = max(1, (int) $request->input('page', 1));
        $totalEntries = count($entries);
        $offset = ($page - 1) * $perPage;
        $paginatedItems = array_slice($entries, $offset, $perPage);

        $paginator = new LengthAwarePaginator(
            $paginatedItems,
            $totalEntries,
            $perPage,
            $page,
            ['path' => route('admin.logs.index'), 'query' => $request->query()]
        );

        // System environment details
        $systemInfo = [
            'php_version'    => PHP_VERSION,
            'laravel_version'=> app()->version(),
            'environment'    => app()->environment(),
            'debug_mode'     => config('app.debug') ? 'Enabled (True)' : 'Disabled (False)',
            'cache_driver'   => config('cache.default'),
            'session_driver' => config('session.driver'),
            'queue_driver'   => config('queue.default'),
        ];

        return view('admin.pages.logs.index', compact(
            'files',
            'currentFile',
            'fileSizeFormatted',
            'paginator',
            'counts',
            'systemInfo'
        ));
    }

    public function clear(Request $request)
    {
        $currentFile = $request->input('file', 'laravel.log');
        $filePath = storage_path('logs/' . basename($currentFile));

        if (File::exists($filePath)) {
            File::put($filePath, '');
            return redirect()->route('admin.logs.index', ['file' => $currentFile])
                ->with('success', "Log file '{$currentFile}' has been cleared successfully.");
        }

        return redirect()->route('admin.logs.index')
            ->with('error', 'Log file not found.');
    }

    public function download(Request $request)
    {
        $currentFile = $request->input('file', 'laravel.log');
        $filePath = storage_path('logs/' . basename($currentFile));

        if (File::exists($filePath)) {
            return response()->download($filePath, $currentFile);
        }

        return redirect()->route('admin.logs.index')
            ->with('error', 'Log file not found.');
    }

    /**
     * Run Laravel Optimization and Cache Commands
     */
    public function optimize(Request $request)
    {
        $action = $request->input('action');
        $output = '';

        try {
            switch ($action) {
                case 'optimize_clear':
                    Artisan::call('optimize:clear');
                    $output = Artisan::output();
                    $message = 'System Cache Cleared (Compiled classes, config, routes, and views refreshed).';
                    break;

                case 'optimize_cache':
                    Artisan::call('config:cache');
                    Artisan::call('route:cache');
                    Artisan::call('view:cache');
                    $output = Artisan::output();
                    $message = 'Application Optimized (Config, Routes, and Blade Views pre-compiled).';
                    break;

                case 'config_clear':
                    Artisan::call('config:clear');
                    $output = Artisan::output();
                    $message = 'Configuration cache cleared.';
                    break;

                case 'cache_clear':
                    Artisan::call('cache:clear');
                    $output = Artisan::output();
                    $message = 'Application data cache cleared.';
                    break;

                case 'view_clear':
                    Artisan::call('view:clear');
                    $output = Artisan::output();
                    $message = 'Compiled Blade templates cleared.';
                    break;

                case 'route_clear':
                    Artisan::call('route:clear');
                    $output = Artisan::output();
                    $message = 'Route registration cache cleared.';
                    break;

                case 'storage_link':
                    Artisan::call('storage:link');
                    $output = Artisan::output();
                    $message = 'Public storage symbolic link regenerated.';
                    break;

                default:
                    return redirect()->back()->with('error', 'Unknown optimization action.');
            }

            return redirect()->back()->with('success', $message . (!empty(trim($output)) ? " Details: {$output}" : ''));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Optimization command failed: ' . $e->getMessage());
        }
    }

    private function parseLogFile(string $filePath): array
    {
        // Read file contents (limit to last 4MB for high performance)
        $maxBytes = 4 * 1024 * 1024;
        $size = File::size($filePath);
        $content = '';

        if ($size > $maxBytes) {
            $fp = fopen($filePath, 'r');
            fseek($fp, -$maxBytes, SEEK_END);
            $content = fread($fp, $maxBytes);
            fclose($fp);
        } else {
            $content = File::get($filePath);
        }

        $pattern = '/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] ([a-zA-Z0-9_\-]+)\.([A-Z]+): (.*?)(?=\n\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\]|\Z)/s';
        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

        $entries = [];
        $counts = [
            'total' => 0,
            'error' => 0,
            'warning' => 0,
            'info' => 0,
            'debug' => 0,
            'other' => 0,
        ];

        // Process in reverse chronological order (newest on top)
        $matches = array_reverse($matches);

        foreach ($matches as $index => $match) {
            $timestamp = $match[1];
            $env = $match[2];
            $level = strtoupper($match[3]);
            $body = trim($match[4]);

            // Split first line (message) from stack trace
            $lines = explode("\n", $body, 2);
            $message = trim($lines[0]);
            $stack = isset($lines[1]) ? trim($lines[1]) : '';

            $levelKey = strtolower($level);
            if (in_array($levelKey, ['error', 'critical', 'alert', 'emergency'])) {
                $counts['error']++;
            } elseif ($levelKey === 'warning') {
                $counts['warning']++;
            } elseif ($levelKey === 'info' || $levelKey === 'notice') {
                $counts['info']++;
            } elseif ($levelKey === 'debug') {
                $counts['debug']++;
            } else {
                $counts['other']++;
            }
            $counts['total']++;

            $entries[] = [
                'id' => $index + 1,
                'timestamp' => $timestamp,
                'env' => $env,
                'level' => $level,
                'message' => $message,
                'stack' => $stack,
            ];
        }

        return [
            'entries' => $entries,
            'counts' => $counts,
        ];
    }

    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
