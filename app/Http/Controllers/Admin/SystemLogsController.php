<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SystemLogsController extends Controller
{
    /**
     * Display system logs and activity dashboard
     */
    public function index(Request $request)
    {
        $logType = $request->get('type', 'all');
        $dateRange = $request->get('date_range', '7'); // days
        $search = $request->get('search', '');
        
        // Get Laravel log files
        $logFiles = $this->getLogFiles();
        
        // Get recent system activity
        $systemActivity = $this->getSystemActivity($dateRange, $search, $logType);
        
        // Get database activity logs
        $databaseActivity = $this->getDatabaseActivity($dateRange);
        
        // Get user activity statistics
        $userStats = $this->getUserActivityStats($dateRange);
        
        // Get system metrics
        $systemMetrics = $this->getSystemMetrics();
        
        return view('admin.system-logs', compact(
            'logFiles',
            'systemActivity',
            'databaseActivity', 
            'userStats',
            'systemMetrics',
            'logType',
            'dateRange',
            'search'
        ));
    }
    
    /**
     * Download log file
     */
    public function downloadLog(Request $request)
    {
        $filename = $request->get('file');
        $logPath = storage_path('logs/' . $filename);
        
        if (!File::exists($logPath) || !str_ends_with($filename, '.log')) {
            abort(404, 'Log file not found');
        }
        
        return response()->download($logPath);
    }
    
    /**
     * Clear log file
     */
    public function clearLog(Request $request)
    {
        $filename = $request->get('file');
        $logPath = storage_path('logs/' . $filename);
        
        if (!File::exists($logPath) || !str_ends_with($filename, '.log')) {
            return back()->with('error', 'Log file not found');
        }
        
        File::put($logPath, '');
        Log::info('Log file cleared by admin', ['file' => $filename, 'admin' => auth()->user()->email]);
        
        return back()->with('success', 'Log file cleared successfully');
    }
    
    /**
     * Get available log files
     */
    private function getLogFiles()
    {
        $logPath = storage_path('logs');
        $files = [];
        
        if (File::exists($logPath)) {
            $logFiles = File::files($logPath);
            
            foreach ($logFiles as $file) {
                if ($file->getExtension() === 'log') {
                    $files[] = [
                        'name' => $file->getFilename(),
                        'size' => $this->formatBytes($file->getSize()),
                        'modified' => Carbon::createFromTimestamp($file->getMTime())->format('Y-m-d H:i:s'),
                        'lines' => $this->countLines($file->getPathname())
                    ];
                }
            }
        }
        
        return collect($files)->sortByDesc('modified');
    }
    
    /**
     * Get system activity from logs
     */
    private function getSystemActivity($dateRange, $search, $logType)
    {
        $logPath = storage_path('logs/laravel.log');
        $activities = [];
        
        if (File::exists($logPath)) {
            $logContent = File::get($logPath);
            $lines = explode("\n", $logContent);
            
            $cutoffDate = Carbon::now()->subDays($dateRange);
            
            foreach (array_reverse($lines) as $line) {
                if (empty(trim($line))) continue;
                
                // Parse log line
                if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\].*?\.(\w+):\s*(.*)/', $line, $matches)) {
                    $timestamp = Carbon::createFromFormat('Y-m-d H:i:s', $matches[1]);
                    $level = strtoupper($matches[2]);
                    $message = $matches[3];
                    
                    // Filter by date range
                    if ($timestamp->lt($cutoffDate)) continue;
                    
                    // Filter by log type
                    if ($logType !== 'all' && strtolower($level) !== strtolower($logType)) continue;
                    
                    // Filter by search
                    if (!empty($search) && stripos($message, $search) === false) continue;
                    
                    $activities[] = [
                        'timestamp' => $timestamp->format('Y-m-d H:i:s'),
                        'level' => $level,
                        'message' => $message,
                        'relative_time' => $timestamp->diffForHumans()
                    ];
                    
                    // Limit to 100 recent activities
                    if (count($activities) >= 100) break;
                }
            }
        }
        
        return collect($activities);
    }
    
    /**
     * Get database activity statistics
     */
    private function getDatabaseActivity($dateRange)
    {
        $cutoffDate = Carbon::now()->subDays($dateRange);
        
        return [
            'new_users' => DB::table('users')->where('created_at', '>=', $cutoffDate)->count(),
            'new_clubs' => DB::table('clubs')->where('created_at', '>=', $cutoffDate)->count(),
            'new_events' => DB::table('events')->where('created_at', '>=', $cutoffDate)->count(),
            'new_enrollments' => DB::table('event_enrollments')->where('created_at', '>=', $cutoffDate)->count(),
            'completed_enrollments' => DB::table('event_enrollments')
                ->where('status', 'completed')
                ->where('updated_at', '>=', $cutoffDate)
                ->count(),
        ];
    }
    
    /**
     * Get user activity statistics
     */
    private function getUserActivityStats($dateRange)
    {
        $cutoffDate = Carbon::now()->subDays($dateRange);
        
        return [
            'total_users' => DB::table('users')->count(),
            'active_users' => DB::table('users')->where('updated_at', '>=', $cutoffDate)->count(),
            'new_registrations' => DB::table('users')->where('created_at', '>=', $cutoffDate)->count(),
            'email_verified_users' => DB::table('users')->whereNotNull('email_verified_at')->count(),
            'unverified_users' => DB::table('users')->whereNull('email_verified_at')->count(),
        ];
    }
    
    /**
     * Get system metrics
     */
    private function getSystemMetrics()
    {
        $diskUsage = $this->getDiskUsage();
        $memoryUsage = $this->getMemoryUsage();
        
        return [
            'disk_usage' => $diskUsage,
            'memory_usage' => $memoryUsage,
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_time' => Carbon::now()->format('Y-m-d H:i:s'),
            'uptime' => $this->getSystemUptime(),
        ];
    }
    
    /**
     * Get disk usage information
     */
    private function getDiskUsage()
    {
        $path = storage_path();
        $total = disk_total_space($path);
        $free = disk_free_space($path);
        $used = $total - $free;
        
        return [
            'total' => $this->formatBytes($total),
            'used' => $this->formatBytes($used),
            'free' => $this->formatBytes($free),
            'percentage' => round(($used / $total) * 100, 2)
        ];
    }
    
    /**
     * Get memory usage information
     */
    private function getMemoryUsage()
    {
        $memoryUsage = memory_get_usage(true);
        $memoryPeak = memory_get_peak_usage(true);
        $memoryLimit = ini_get('memory_limit');
        
        return [
            'current' => $this->formatBytes($memoryUsage),
            'peak' => $this->formatBytes($memoryPeak),
            'limit' => $memoryLimit,
        ];
    }
    
    /**
     * Get system uptime (simplified)
     */
    private function getSystemUptime()
    {
        // For demonstration purposes - in real scenarios, you'd use system commands
        return 'N/A (PHP environment)';
    }
    
    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
    
    /**
     * Count lines in file
     */
    private function countLines($filename)
    {
        $linecount = 0;
        $handle = fopen($filename, "r");
        while(!feof($handle)){
            $line = fgets($handle);
            $linecount++;
        }
        fclose($handle);
        return $linecount;
    }
}