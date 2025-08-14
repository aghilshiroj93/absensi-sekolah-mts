@extends('layouts/main')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/beranda">Beranda</a> / Scan QR</li>
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Kolom kiri: scanner -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="m-0">Menu Scan QR</h5>
                        </div>
                        <div class="card-body text-center">
                            <p class="card-text">
                                Pilih kamera belakang pada kolom di bawah, lalu arahkan ke kode QR pada kartu siswa.
                            </p>

                            <div class="card mt-3">
                                {{-- <div class="card-header">{{ __('Attendance Scanner') }}</div> --}}
                                <div class="card-body d-flex justify-content-center">
                                    <div id="reader" style="width: 100%;"></div>
                                </div>
                            </div>

                            {{-- <a href="#" class="btn btn-warning mt-3">Perlu bantuan ?</a> --}}
                        </div>
                    </div>
                </div>

                <!-- Kolom kanan: tabel monitoring -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="m-0">Monitoring Absensi Hari Ini</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped" id="tabel-absensi">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Siswa</th>
                                        <th>Kelas</th>
                                        <th>Jam Absen</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data akan dimuat via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/html5-qrcode.min.js') }}"></script>
    <script>
        const html5QRCodeScanner = new Html5QrcodeScanner(
            "reader", {
                fps: 10,
                qrbox: {
                    width: 200,
                    height: 200
                }
            }
        );

        function loadAbsensi() {
            console.log("Memuat data absensi..."); // log awal

            fetch("/monitoring/absensi")
                .then(response => {
                    if (!response.ok) {
                        console.error("Gagal mengambil data. Status:", response.status);
                        throw new Error("HTTP status " + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log("Data absensi berhasil diterima:", data); // log sukses
                    let tbody = document.querySelector("#tabel-absensi tbody");
                    tbody.innerHTML = "";
                    data.forEach((item, index) => {
                        tbody.innerHTML += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.nama_lengkap}</td>
                        <td>${item.nama_kelas}</td>
                        <td>${item.jam_absen}</td>
                    </tr>
                `;
                    });
                })
                .catch(err => {
                    console.error("Terjadi kesalahan saat memuat absensi:", err); // log error
                });
        }


        function onScanSuccess(decodedText) {
            fetch("/kirim/scan-qr", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        absensi: decodedText
                    })
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    loadAbsensi(); // Refresh tabel setelah scan
                })
                .catch(error => {
                    console.error(error);
                    alert("Terjadi kesalahan saat mengirim data absensi.");
                });
        }

        html5QRCodeScanner.render(onScanSuccess);

        // Load tabel saat halaman dibuka & auto-refresh setiap 5 detik
        loadAbsensi();
        setInterval(loadAbsensi, 5000);
    </script>
@endsection
