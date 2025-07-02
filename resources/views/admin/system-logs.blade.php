@extends('layouts.app')

@section('title', 'System Logs & Activity')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>
    body {
        background-color: #f8f9fa !important;
        color: #212529 !important;
    }
    
    /* Fix navigation text colors */
    nav span.font-bold {
        color: #1f2937 !important;
    }
    nav a {
        color: #374151 !important;
    }
    nav a:hover {
        color: #4f46e5 !important;
    }
    
    /* Dropdown text colors */
    .dropdown-menu a {
        color: #374151 !important;
    }
    
    /* User name in navigation - this targets the username specifically */
    nav .text-gray-900,
    nav .dark\\:text-white,
    nav div.text-sm.font-medium {
        color: #1f2937 !important;
    }
    
    /* User role text */
    nav .text-gray-500,
    nav .dark\\:text-gray-400,
    nav div.text-xs {
        color: #6b7280 !important;
    }
    
    nav .text-gray-700,
    nav .dark\\:text-gray-300 {
        color: #374151 !important;
    }
    
    .card {
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
        border: 1px solid #e3e6f0 !important;
        background-color: #ffffff !important;
    }
    .text-gray-800 {
        color: #5a5c69 !important;
    }
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    .border-left-secondary {
        border-left: 0.25rem solid #858796 !important;
    }
    .text-gray-300 {
        color: #dddfeb !important;
    }
    .text-gray-500 {
        color: #858796 !important;
    }
    .badge-danger {
        background-color: #e74a3b !important;
    }
    .badge-warning {
        background-color: #f6c23e !important;
        color: #fff !important;
    }
    .badge-info {
        background-color: #36b9cc !important;
    }
    .badge-secondary {
        background-color: #858796 !important;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0" style="color: #5a5c69 !important;">
                    <i class="fas fa-chart-line"></i> System Logs & Activity
                </h1>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary" onclick="refreshLogs()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <form action="{{ route('admin.system-logs.download') }}" method="GET" class="d-inline">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-download"></i> Download Logs
                        </button>
                    </form>
                    <form action="{{ route('admin.system-logs.clear') }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to clear all logs?')">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Clear Logs
                        </button>
                    </form>
                </div>
            </div>

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.system-logs') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="type" class="form-label">Log Type</label>
                            <select name="type" id="type" class="form-select">
                                <option value="all" {{ $logType == 'all' ? 'selected' : '' }}>All Logs</option>
                                <option value="error" {{ $logType == 'error' ? 'selected' : '' }}>Errors</option>
                                <option value="warning" {{ $logType == 'warning' ? 'selected' : '' }}>Warnings</option>
                                <option value="info" {{ $logType == 'info' ? 'selected' : '' }}>Info</option>
                                <option value="debug" {{ $logType == 'debug' ? 'selected' : '' }}>Debug</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="date_range" class="form-label">Date Range</label>
                            <select name="date_range" id="date_range" class="form-select">
                                <option value="1" {{ $dateRange == '1' ? 'selected' : '' }}>Last 24 Hours</option>
                                <option value="7" {{ $dateRange == '7' ? 'selected' : '' }}>Last 7 Days</option>
                                <option value="30" {{ $dateRange == '30' ? 'selected' : '' }}>Last 30 Days</option>
                                <option value="90" {{ $dateRange == '90' ? 'selected' : '' }}>Last 90 Days</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" name="search" id="search" class="form-control" value="{{ $search }}" placeholder="Search logs...">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary d-block w-100">
                                <i class="fas fa-search"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- System Metrics -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Log Entries
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold" style="color: #5a5c69 !important;">
                                        {{ number_format(count($systemActivity)) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-list fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Active Users (24h)
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold" style="color: #5a5c69 !important;">
                                        {{ $userStats['active_users_24h'] ?? 0 }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        DB Queries (24h)
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold" style="color: #5a5c69 !important;">
                                        {{ number_format(count($databaseActivity)) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-database fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        System Uptime
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold" style="color: #5a5c69 !important;">
                                        {{ $systemMetrics['uptime'] ?? 'N/A' }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clock fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent System Activity -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent System Activity</h6>
                </div>
                <div class="card-body">
                    @if(count($systemActivity) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Timestamp</th>
                                        <th>Level</th>
                                        <th>Message</th>
                                        <th>Context</th>
                                        <th>User</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($systemActivity as $activity)
                                    <tr>
                                        <td>{{ $activity['timestamp'] ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge badge-{{ 
                                                ($activity['level'] ?? '') == 'error' ? 'danger' : (
                                                ($activity['level'] ?? '') == 'warning' ? 'warning' : (
                                                ($activity['level'] ?? '') == 'info' ? 'info' : 'secondary'
                                                ))
                                            }}">
                                                {{ strtoupper($activity['level'] ?? 'unknown') }}
                                            </span>
                                        </td>
                                        <td>{{ $activity['message'] ?? 'No message' }}</td>
                                        <td>
                                            @if(!empty($activity['context']))
                                                <small class="text-muted">{{ json_encode($activity['context'], JSON_PRETTY_PRINT) }}</small>
                                            @else
                                                <small class="text-muted">No context</small>
                                            @endif
                                        </td>
                                        <td>{{ $activity['user'] ?? 'System' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                            <p style="color: #858796 !important;">No system activity logs found for the selected criteria.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Log Files -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Available Log Files</h6>
                </div>
                <div class="card-body">
                    @if(count($logFiles) > 0)
                        <div class="row">
                            @foreach($logFiles as $logFile)
                            <div class="col-md-6 mb-3">
                                <div class="card border-left-secondary">
                                    <div class="card-body py-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1" style="color: #5a5c69 !important;">{{ $logFile['name'] }}</h6>
                                                <small style="color: #858796 !important;">
                                                    Size: {{ $logFile['size'] }} | 
                                                    Modified: {{ $logFile['modified'] }}
                                                </small>
                                            </div>
                                            <div>
                                                <a href="{{ route('admin.system-logs.download', ['file' => $logFile['name']]) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-file-alt fa-3x text-gray-300 mb-3"></i>
                            <p style="color: #858796 !important;">No log files found.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function refreshLogs() {
    window.location.reload();
}

// Auto-refresh every 30 seconds
setInterval(function() {
    if (document.getElementById('auto-refresh') && document.getElementById('auto-refresh').checked) {
        refreshLogs();
    }
}, 30000);
</script>
@endsection
