@extends('layouts/main')

<!-- DataTables -->
<link rel="stylesheet" href="/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/beranda">Beranda</a> Kelas / <a href="/jadwalguru">Daftar Jadwal</a> / Detail</li>
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title pb-3 col-md-10">Data di bawah adalah seluruh murid dari kelas :
                                <b>{{ $kelas->nama_kelas }} </b>
                            </h3>
                            @can('admin')
                                <div class="btn-group pb-2 col-sm-2">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                        Manage
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item text-success"
                                            href="/download-kartu-massal/{{ $kelas->id }}">Download Kartu Absensi Massal</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger" href="#" data-toggle="modal"
                                            data-target="#modalLoginForm">Hapus Kelas</a>
                                    </div>
                                </div>
                            @endcan

                            <div class="card-tools">
                                <div class="input-group input-group-sm" style="width: 100px;">

                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nomor</th>
                                            <th>NIS</th>
                                            <th>Nama Lengkap</th>
                                            <th>Kelas</th>
                                            <th>Tahun</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no=1; @endphp
                                        @foreach ($murid as $m)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $m->nis }}</td>
                                                <td>{{ $m->nama_lengkap }}</td>
                                                <td>{{ $m->kelas->nama_kelas }}</td>
                                                <td>{{ $m->kelas->tahun->nama_tahun }}</td>
                                                <td><a href="/detail-murid/{{ $m->id_siswa }}"><button
                                                            class="btn-md btn-info">Detail</button></a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                    <!-- /.card -->
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <p class="card-text">
                                Pilih kamera belakang pada kolom di bawah, lalu arahkan ke kode QR pada kartu siswa.
                            </p>

                            <div class="card mt-3">
                                {{-- <div class="card-header">{{ __('Attendance Scanner') }}</div> --}}
                                <div class="card-body d-flex justify-content-center">
                                    <div id="reader" style="width: 90%;"></div>
                                </div>
                            </div>

                            {{-- <a href="#" class="btn btn-warning mt-3">Perlu bantuan ?</a> --}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
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
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data akan dimuat via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
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

                fetch("/jadwalguru/monitoring")
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
                        <td>${item.status}</td>
                        <td>
                            <form action="/jadwalguru/absensi/${item.id_absensi}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus absensi ini?');">
                                @csrf

                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>

                    </tr>
                `;
                        });
                    })
                    .catch(err => {
                        console.error("Terjadi kesalahan saat memuat absensi:", err); // log error
                    });
            }


            function onScanSuccess(decodedText) {
                fetch("/jadwalguru/absensi", {
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
        </script><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    <!-- Pop Up Form Hapus Murid -->
    <div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold text-danger">HAPUS KELAS</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="/kelas/hapus/{{ $kelas->id }}" method="post">
                    @csrf
                    <div class="modal-body mx-3">
                        <div class="md-form mb-0">
                            <p class="text-danger">Anda ingin menghapus : <b>{{ $kelas->kelas }}</b></p>
                            <p class="text-danger"><i>Perhatian! Menghapus data kelas dapat menyebabkan <b>KERUSAKAN DATA
                                        SECARA PERMANEN</b>!. Harap diskusikan terlebih dahulu kepada pihak pengembang atau
                                    pihak pengelola pada sekolah anda!. Jika anda sudah yakin akan tindakan anda, maka klik
                                    "Saya Yakin!"
                                    <br>
                                    <br>
                                    <b>Pihak pengembang tidak bertanggung jawab atas kerusakan data yang di sebabkan
                                        menghapus data kelas ini!</b></i></p>
                            <hr>
                            <span><input type="hidden" name="captcha" value="bypass"></span>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="submit" class="btn btn-danger">Saya Yakin!</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
