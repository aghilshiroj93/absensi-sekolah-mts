@extends('layouts/main')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">{{ __('Attendance Scanner') }}</h5>
                    </div>

                    <div class="card-body">
                        <!-- Camera Selection Dropdown -->
                        <div class="mb-3" id="camera-selector" style="display: none;">
                            <label for="camera-device" class="form-label">Pilih Kamera</label>
                            <select id="camera-device" class="form-select"></select>
                        </div>

                        <!-- Scanner Area -->
                        <div class="text-center mb-4 position-relative">
                            <video id="scanner" width="100%" height="auto" playsinline></video>
                            <div class="scanner-overlay"></div>
                        </div>

                        <!-- Attendance Form -->
                        <form id="attendance-form" method="POST" action="{{ route('attendance.scan') }}">
                            @csrf
                            <input type="hidden" name="nis" id="nis">
                        </form>

                        <!-- Result Display -->
                        <div id="result" class="text-center mt-3"></div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <button id="restart-btn" class="btn btn-secondary" style="display: none;">
                                <i class="fas fa-redo"></i> Mulai Ulang
                            </button>
                            <button id="manual-input-btn" class="btn btn-warning">
                                <i class="fas fa-keyboard"></i> Input Manual
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Manual Input -->
    <div class="modal fade" id="manualInputModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Input NIS Manual</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="manual-form">
                        <div class="mb-3">
                            <label for="manual-nis" class="form-label">Nomor Induk Siswa (NIS)</label>
                            <input type="text" class="form-control" id="manual-nis" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="submit-manual" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const scanner = document.getElementById('scanner');
                const resultDiv = document.getElementById('result');
                const restartBtn = document.getElementById('restart-btn');
                const cameraSelector = document.getElementById('camera-selector');
                const cameraDevice = document.getElementById('camera-device');
                let currentStream = null;
                let scannerInitialized = false;

                // Initialize scanner
                initCamera();

                // Manual input handling
                document.getElementById('manual-input-btn').addEventListener('click', function() {
                    if (scannerInitialized) {
                        Quagga.stop();
                        if (currentStream) {
                            currentStream.getTracks().forEach(track => track.stop());
                        }
                    }
                    new bootstrap.Modal(document.getElementById('manualInputModal')).show();
                });

                document.getElementById('submit-manual').addEventListener('click', function() {
                    const nis = document.getElementById('manual-nis').value;
                    if (nis) {
                        processNIS(nis);
                        bootstrap.Modal.getInstance(document.getElementById('manualInputModal')).hide();
                    }
                });

                // Restart scanner
                restartBtn.addEventListener('click', function() {
                    initCamera();
                    restartBtn.style.display = 'none';
                    resultDiv.innerHTML = '';
                });

                async function initCamera() {
                    try {
                        // Get available cameras
                        const devices = await navigator.mediaDevices.enumerateDevices();
                        const videoDevices = devices.filter(device => device.kind === 'videoinput');

                        if (videoDevices.length > 1) {
                            cameraSelector.style.display = 'block';
                            cameraDevice.innerHTML = videoDevices.map(device =>
                                `<option value="${device.deviceId}">${device.label || 'Camera ' + (cameraDevice.length + 1)}</option>`
                            ).join('');
                        }

                        // Start with environment facing camera
                        await startCamera('environment');
                    } catch (error) {
                        console.error("Initial camera error:", error);
                        resultDiv.innerHTML = `
                        <div class="alert alert-danger">
                            Gagal mengakses kamera: ${error.message}
                            <button onclick="location.reload()" class="btn btn-sm btn-warning ms-2">Coba Lagi</button>
                        </div>`;
                    }
                }

                async function startCamera(facingMode) {
                    if (currentStream) {
                        currentStream.getTracks().forEach(track => track.stop());
                    }

                    const constraints = {
                        video: {
                            width: {
                                ideal: 1280
                            },
                            height: {
                                ideal: 720
                            },
                            facingMode: facingMode,
                            deviceId: cameraDevice.value ? {
                                exact: cameraDevice.value
                            } : undefined
                        }
                    };

                    try {
                        const stream = await navigator.mediaDevices.getUserMedia(constraints);
                        currentStream = stream;
                        scanner.srcObject = stream;
                        await scanner.play();
                        initializeQuagga();
                    } catch (error) {
                        console.error("Camera access error:", error);
                        // Fallback to user facing camera
                        if (facingMode === 'environment') {
                            await startCamera('user');
                        } else {
                            throw error;
                        }
                    }
                }

                function initializeQuagga() {
                    Quagga.init({
                        inputStream: {
                            name: "Live",
                            type: "LiveStream",
                            target: scanner,
                            constraints: {
                                width: 640,
                                height: 480,
                                facingMode: "environment"
                            },
                        },
                        decoder: {
                            readers: ["code_128_reader", "ean_reader", "ean_8_reader", "code_39_reader",
                                "code_39_vin_reader", "codabar_reader", "upc_reader", "upc_e_reader"
                            ]
                        },
                        locate: true,
                        numOfWorkers: 4
                    }, function(err) {
                        if (err) {
                            console.error(err);
                            resultDiv.innerHTML = `
                            <div class="alert alert-danger">
                                Gagal inisialisasi scanner: ${err.message}
                                <button onclick="initCamera()" class="btn btn-sm btn-warning ms-2">Coba Lagi</button>
                            </div>`;
                            return;
                        }
                        Quagga.start();
                        scannerInitialized = true;
                    });

                    Quagga.onDetected(async function(result) {
                        const code = result.codeResult.code;
                        resultDiv.innerHTML = `
                        <div class="alert alert-info">
                            <i class="fas fa-spinner fa-spin"></i> Memproses NIS: ${code}
                        </div>`;

                        Quagga.stop();
                        scannerInitialized = false;

                        await processNIS(code);
                    });
                }

                async function processNIS(nis) {
                    try {
                        // Validate NIS format
                        if (!/^\d+$/.test(nis)) {
                            throw new Error('Format NIS tidak valid. Harus berupa angka.');
                        }

                        // Show processing message
                        resultDiv.innerHTML = `
                        <div class="alert alert-info">
                            <i class="fas fa-spinner fa-spin"></i> Memverifikasi NIS: ${nis}
                        </div>`;

                        // Send to server
                        const response = await fetch("{{ route('attendance.verify') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                nis
                            })
                        });

                        const data = await response.json();

                        if (response.ok && data.success) {
                            // Submit attendance form
                            document.getElementById('nis').value = nis;
                            document.getElementById('attendance-form').submit();
                        } else {
                            throw new Error(data.message || 'Gagal memverifikasi NIS');
                        }
                    } catch (error) {
                        console.error("Error processing NIS:", error);
                        resultDiv.innerHTML = `
                        <div class="alert alert-danger">
                            ${error.message}
                            <button onclick="initCamera()" class="btn btn-sm btn-warning ms-2">Coba Lagi</button>
                        </div>`;
                        restartBtn.style.display = 'block';
                    }
                }

                // Handle camera selection change
                cameraDevice.addEventListener('change', function() {
                    if (scannerInitialized) {
                        Quagga.stop();
                    }
                    startCamera('environment');
                });

                // Clean up on page leave
                window.addEventListener('beforeunload', function() {
                    if (currentStream) {
                        currentStream.getTracks().forEach(track => track.stop());
                    }
                    if (scannerInitialized) {
                        Quagga.stop();
                    }
                });
            });
        </script>
        <style>
            .scanner-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(to bottom,
                        rgba(0, 0, 0, 0.7) 0%,
                        rgba(0, 0, 0, 0) 30%,
                        rgba(0, 0, 0, 0) 70%,
                        rgba(0, 0, 0, 0.7) 100%);
                pointer-events: none;
                z-index: 1;
            }

            #scanner {
                background: #000;
                max-height: 60vh;
                object-fit: cover;
            }
        </style>
    @endpush
@endsection
