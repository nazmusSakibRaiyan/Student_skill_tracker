@extends('layouts.app')

@section('title', 'QR Code - ' . $event->name)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">QR Code for Attendance</h1>
                <p class="mt-2 text-gray-600">{{ $event->name }} • {{ $event->club->name }}</p>
            </div>
            <a href="{{ route('club-manager.attendance.show', $event) }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                Back to Attendance
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- QR Code Display -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">QR Code</h2>
            <div class="text-center">
                <!-- Loading indicator -->
                <div id="qr-loading" class="flex justify-center mb-4">
                    <div class="text-gray-500">
                        <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-gray-500 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Generating QR code...
                    </div>
                </div>
                
                <!-- QR Code using multiple fallback methods -->
                <div id="qr-container" class="flex justify-center mb-4" style="display: none;">
                    <img id="qr-image" alt="QR Code for Attendance Check-in" class="border border-gray-300 rounded-lg shadow-lg max-w-xs">
                </div>
                
                <!-- Manual creation with Canvas -->
                <div id="qr-canvas-container" class="flex justify-center mb-4" style="display: none;">
                    <canvas id="qr-canvas" class="border border-gray-300 rounded-lg shadow-lg"></canvas>
                </div>
                
                <!-- Error fallback -->
                <div id="qr-error" class="hidden">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                        <div class="flex items-start">
                            <svg class="h-5 w-5 text-yellow-400 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="text-yellow-800 text-sm font-medium">QR Code Generation Failed</p>
                                <p class="text-yellow-700 text-sm mt-1">
                                    The QR code could not be generated automatically. Please use the manual URL below or visit 
                                    <a href="https://www.qr-code-generator.com/" target="_blank" class="underline">an online QR code generator</a> 
                                    to create one manually.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <p class="text-sm text-gray-600 mb-4">Students can scan this QR code to mark their attendance</p>
                
                <!-- Manual URL display -->
                <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm font-medium text-blue-800 mb-2">Manual Check-in URL:</p>
                    <div class="flex">
                        <input type="text" id="manualUrl" value="{{ $checkInUrl }}" readonly class="flex-1 text-xs border-blue-300 rounded-l-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-white">
                        <button onclick="copyManualUrl()" class="bg-blue-600 text-white px-3 py-2 rounded-r-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                            Copy
                        </button>
                    </div>
                    <p class="text-xs text-blue-600 mt-2">Students can visit this URL directly to check in</p>
                </div>
                
                <div class="flex gap-2 justify-center flex-wrap">
                    <button onclick="downloadQR()" id="downloadBtn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        Download QR Code
                    </button>
                    <button onclick="saveQRDirect()" id="saveDirectBtn" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        Save Image
                    </button>
                    <button onclick="printQR()" id="printBtn" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        Print QR Code
                    </button>
                    <button onclick="retryQR()" id="retryBtn" class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 hidden">
                        Retry QR Code
                    </button>
                    <a href="https://www.qr-code-generator.com/" target="_blank" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 inline-block">
                        Manual Generator
                    </a>
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Instructions</h2>
            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-sm font-medium text-blue-600">1</span>
                        </div>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-gray-900">Display or Share QR Code</h3>
                        <p class="text-sm text-gray-600">Show the QR code to students or display it prominently at the event venue.</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-sm font-medium text-blue-600">2</span>
                        </div>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-gray-900">Students Scan QR Code</h3>
                        <p class="text-sm text-gray-600">Students use their phone's camera or QR code scanner to scan the code.</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-sm font-medium text-blue-600">3</span>
                        </div>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-gray-900">Automatic Check-in</h3>
                        <p class="text-sm text-gray-600">Students enter their email address and are automatically marked as present.</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-sm font-medium text-blue-600">4</span>
                        </div>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-gray-900">Real-time Updates</h3>
                        <p class="text-sm text-gray-600">Attendance is updated in real-time and you can monitor progress from the attendance management page.</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 p-4 bg-yellow-50 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Important Notes</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Only enrolled students can check in using the QR code</li>
                                <li>Students can only check in once per event</li>
                                <li>QR code check-in automatically marks students as "Present"</li>
                                <li>You can still manually adjust attendance after QR check-ins</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- URL for Manual Sharing -->
    <div class="mt-8 bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Check-in URL</h2>
        <p class="text-sm text-gray-600 mb-3">You can also share this URL directly with students:</p>
        <div class="flex">
            <input type="text" id="checkinUrl" value="{{ $checkInUrl }}" readonly class="flex-1 border-gray-300 rounded-l-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white">
            <button onclick="copyUrl()" class="bg-indigo-600 text-white px-4 py-2 rounded-r-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                Copy
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
// QR Code generation with multiple fallbacks
const qrCodeUrl = @json($checkInUrl);
let currentMethod = 0;
const qrMethods = [
    // Method 1: QR Server API
    () => `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(qrCodeUrl)}`,
    
    // Method 2: QR Code API
    () => `https://qr-generator.qrcode.studio/qr/custom?download=false&file=png&data=${encodeURIComponent(qrCodeUrl)}&size=300`,
    
    // Method 3: Chart.googleapis.com
    () => `https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=${encodeURIComponent(qrCodeUrl)}`,
    
    // Method 4: QuickChart.io
    () => `https://quickchart.io/qr?text=${encodeURIComponent(qrCodeUrl)}&size=300`
];

function hideLoading() {
    document.getElementById('qr-loading').style.display = 'none';
}

function showError() {
    hideLoading();
    document.getElementById('qr-error').classList.remove('hidden');
    document.getElementById('retryBtn').classList.remove('hidden');
}

function showSuccess() {
    hideLoading();
    document.getElementById('qr-container').style.display = 'block';
    document.getElementById('downloadBtn').disabled = false;
    document.getElementById('saveDirectBtn').disabled = false;
    document.getElementById('printBtn').disabled = false;
}

function tryNextQRMethod() {
    if (currentMethod >= qrMethods.length) {
        showError();
        return;
    }
    
    const qrImage = document.getElementById('qr-image');
    const qrUrl = qrMethods[currentMethod]();
    
    qrImage.onload = function() {
        showSuccess();
    };
    
    qrImage.onerror = function() {
        currentMethod++;
        setTimeout(tryNextQRMethod, 1000);
    };
    
    qrImage.src = qrUrl;
}

function retryQR() {
    currentMethod = 0;
    document.getElementById('qr-error').classList.add('hidden');
    document.getElementById('retryBtn').classList.add('hidden');
    document.getElementById('qr-container').style.display = 'none';
    document.getElementById('qr-loading').style.display = 'block';
    document.getElementById('downloadBtn').disabled = true;
    document.getElementById('saveDirectBtn').disabled = true;
    document.getElementById('printBtn').disabled = true;
    
    setTimeout(tryNextQRMethod, 500);
}

// Simple, reliable download method
function saveQRDirect() {
    const image = document.querySelector('#qr-image');
    
    if (!image || !image.src) {
        alert('No QR code available to save');
        return;
    }
    
    // Method 1: Simple link download
    try {
        const link = document.createElement('a');
        link.href = image.src;
        link.download = `qr-code-event-{{ $event->id }}.png`;
        link.target = '_blank';
        
        // Try to force download
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Show success message
        const button = document.getElementById('saveDirectBtn');
        const originalText = button.textContent;
        button.textContent = 'Opening...';
        button.classList.add('bg-green-600');
        
        setTimeout(() => {
            button.textContent = originalText;
            button.classList.remove('bg-green-600');
        }, 2000);
        
    } catch (e) {
        console.error('Direct save failed:', e);
        
        // Fallback: Open in new tab with instructions
        const newWindow = window.open(image.src, '_blank');
        
        if (newWindow) {
            alert('QR code opened in new tab. Right-click the image and select "Save image as..." to download it to your device.');
        } else {
            // Pop-up blocked - copy URL instead
            navigator.clipboard.writeText(image.src).then(() => {
                alert('QR code URL copied to clipboard. Paste it in a new browser tab, then right-click the image to save it.');
            }).catch(() => {
                alert(`Please copy this URL and open it in a new tab to save the QR code:\n\n${image.src}`);
            });
        }
    }
}

function downloadQR() {
    const image = document.querySelector('#qr-image');
    
    if (!image || !image.src) {
        alert('No QR code available to download');
        return;
    }
    
    // Method 1: Try direct download (works for same-origin images)
    if (image.complete && image.naturalWidth > 0) {
        try {
            // Create a canvas to convert the image
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            canvas.width = image.naturalWidth || 300;
            canvas.height = image.naturalHeight || 300;
            
            // Set CORS to anonymous to avoid tainted canvas
            const tempImage = new Image();
            tempImage.crossOrigin = 'anonymous';
            
            tempImage.onload = function() {
                try {
                    ctx.drawImage(tempImage, 0, 0);
                    
                    // Convert to blob and download
                    canvas.toBlob(function(blob) {
                        if (blob) {
                            const url = window.URL.createObjectURL(blob);
                            const link = document.createElement('a');
                            link.href = url;
                            link.download = `attendance-qr-{{ $event->id }}.png`;
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);
                            window.URL.revokeObjectURL(url);
                        } else {
                            fallbackDownload();
                        }
                    }, 'image/png', 1.0);
                } catch (e) {
                    console.error('Canvas drawing failed:', e);
                    fallbackDownload();
                }
            };
            
            tempImage.onerror = function() {
                console.error('Image loading failed for canvas');
                fallbackDownload();
            };
            
            tempImage.src = image.src;
            
        } catch (e) {
            console.error('Canvas method failed:', e);
            fallbackDownload();
        }
    } else {
        fallbackDownload();
    }
    
    // Fallback method: Direct link download or fetch
    function fallbackDownload() {
        console.log('Using fallback download method');
        
        // Method 2: Try fetch with cors proxy
        const corsProxyUrl = `https://cors-anywhere.herokuapp.com/${image.src}`;
        
        fetch(corsProxyUrl)
            .then(res => {
                if (!res.ok) throw new Error('Cors proxy failed');
                return res.blob();
            })
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                link.download = `attendance-qr-{{ $event->id }}.png`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                window.URL.revokeObjectURL(url);
            })
            .catch(err => {
                console.error('Fetch download failed:', err);
                
                // Method 3: Direct link download (opens in new tab)
                try {
                    const link = document.createElement('a');
                    link.href = image.src;
                    link.download = `attendance-qr-{{ $event->id }}.png`;
                    link.target = '_blank';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                } catch (e) {
                    console.error('Direct link failed:', e);
                    
                    // Method 4: Open in new window for manual save
                    try {
                        const newWindow = window.open(image.src, '_blank');
                        if (newWindow) {
                            alert('QR code opened in new tab. Right-click the image and select "Save image as..." to download.');
                        } else {
                            showManualInstructions();
                        }
                    } catch (e) {
                        showManualInstructions();
                    }
                }
            });
    }
    
    // Manual instructions as last resort
    function showManualInstructions() {
        const instructions = `
Download Instructions:
1. Right-click on the QR code image above
2. Select "Save image as..." or "Save picture as..."
3. Choose a location and save the file

Alternative:
1. Copy this URL: ${image.src}
2. Paste it in a new browser tab
3. Right-click the image and save it

The QR code URL has been copied to your clipboard.
        `;
        
        // Copy URL to clipboard
        navigator.clipboard.writeText(image.src).catch(() => {});
        
        alert(instructions);
    }
}

function printQR() {
    const image = document.querySelector('#qr-image');
    
    if (image && image.src) {
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>QR Code - {{ $event->name }}</title>
                    <style>
                        body { 
                            font-family: Arial, sans-serif; 
                            text-align: center; 
                            padding: 20px; 
                            margin: 0;
                        }
                        .header { 
                            margin-bottom: 20px; 
                        }
                        .qr-container { 
                            margin: 20px 0; 
                        }
                        .instructions { 
                            margin-top: 20px; 
                            text-align: left; 
                            max-width: 400px; 
                            margin-left: auto; 
                            margin-right: auto; 
                        }
                        @media print {
                            body { margin: 0; padding: 10px; }
                        }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h1>{{ $event->name }}</h1>
                        <h2>{{ $event->club->name }}</h2>
                        <p>{{ $event->start_date->format('M d, Y \a\t h:i A') }}</p>
                    </div>
                    <div class="qr-container">
                        <img src="${image.src}" alt="QR Code" style="width: 300px; height: 300px; border: 2px solid #000;">
                    </div>
                    <div class="instructions">
                        <h3>How to Check In:</h3>
                        <ol>
                            <li>Scan this QR code with your phone's camera</li>
                            <li>Enter your email address on the webpage</li>
                            <li>Click "Check In" to confirm attendance</li>
                        </ol>
                        <p><strong>Important:</strong> Only enrolled students can check in.</p>
                        <p><strong>Manual URL:</strong><br>{{ $checkInUrl }}</p>
                    </div>
                </body>
            </html>
        `);
        printWindow.document.close();
        
        // Wait for images to load then print
        setTimeout(() => {
            printWindow.focus();
            printWindow.print();
        }, 1000);
    } else {
        alert('No QR code available to print');
    }
}

function copyManualUrl() {
    const urlInput = document.getElementById('manualUrl');
    urlInput.select();
    urlInput.setSelectionRange(0, 99999);
    
    navigator.clipboard.writeText(urlInput.value).then(function() {
        const button = event.target;
        const originalText = button.textContent;
        button.textContent = 'Copied!';
        button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
        button.classList.add('bg-green-600');
        
        setTimeout(function() {
            button.textContent = originalText;
            button.classList.remove('bg-green-600');
            button.classList.add('bg-blue-600', 'hover:bg-blue-700');
        }, 2000);
    }).catch(function(error) {
        console.error('Copy failed:', error);
        // Fallback for older browsers
        urlInput.focus();
        urlInput.select();
        try {
            document.execCommand('copy');
            showCopySuccess(event.target);
        } catch (e) {
            alert('Please manually copy the URL: ' + urlInput.value);
        }
    });
}

function copyUrl() {
    const urlInput = document.getElementById('checkinUrl');
    urlInput.select();
    urlInput.setSelectionRange(0, 99999);
    
    navigator.clipboard.writeText(urlInput.value).then(function() {
        const button = event.target;
        const originalText = button.textContent;
        button.textContent = 'Copied!';
        button.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
        button.classList.add('bg-green-600');
        
        setTimeout(function() {
            button.textContent = originalText;
            button.classList.remove('bg-green-600');
            button.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
        }, 2000);
    }).catch(function(error) {
        console.error('Copy failed:', error);
        alert('Failed to copy URL. Please select and copy manually.');
    });
}

function showCopySuccess(button) {
    const originalText = button.textContent;
    button.textContent = 'Copied!';
    button.classList.add('bg-green-600');
    
    setTimeout(function() {
        button.textContent = originalText;
        button.classList.remove('bg-green-600');
    }, 2000);
}

// Initialize QR code generation when page loads
document.addEventListener('DOMContentLoaded', function() {
    tryNextQRMethod();
});
</script>
@endpush
@endsection
