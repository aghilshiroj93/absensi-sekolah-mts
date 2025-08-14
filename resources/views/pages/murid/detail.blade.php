@extends('layouts/main')

<!-- DataTables -->
<link rel="stylesheet" href="/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/beranda">Beranda</a> / Murid / <a href="/daftar-murid">Daftar Murid</a> / Detail</li>
@endsection

@section('content')
    <!-- Main content -->
    @if (session()->has('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('#profile form');
            form.addEventListener('submit', function(e) {
                const nama = document.getElementById('nama').value.trim();
                const kelas = document.getElementById('kelas').value;
                const tahun = document.getElementById('tahun').value;

                if (nama.length < 3) {
                    e.preventDefault();
                    alert('Nama harus memiliki minimal 3 karakter');
                    return;
                }

                if (!kelas || !tahun) {
                    e.preventDefault();
                    alert('Kelas dan Tahun harus dipilih');
                    return;
                }
            });
        });
    </script>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    @if (session()->has('fail'))
                        Gagal!
                    @endif
                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid img-circle" src="/img/avatarFix.png"
                                    alt="User profile picture">
                            </div>

                            <h3 class="profile-username text-center">{{ $murid->nama }}</h3>

                            <p class="text-center">{{ $murid->kelas->kelas }}</p>

                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>NIS</b> <a class="float-right">{{ $murid->nis }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Nama Lengkap</b> <a class="float-right">{{ $murid->nama_lengkap }}</a>
                                </li>



                                <li class="list-group-item">
                                    <b>Kehadiran</b> <a class="float-right">98%</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Di Buat</b> <a class="float-right">{{ date('d-m-Y', strtotime($murid->created_at)) }}
                                        | {{ date('H:m:s', strtotime($murid->created_at)) }} WIB</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Di Perbarui</b> <a
                                        class="float-right">{{ date('d-m-Y', strtotime($murid->updated_at)) }} |
                                        {{ date('H:m:s', strtotime($murid->updated_at)) }} WIB</a>
                                </li>
                            </ul>
                            <a href="#" class="btn btn-warning btn-block" data-toggle="modal"
                                data-target="#modalQr"><b>Lihat Kartu</b></a>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                    <!-- About Me Box -->

                    <!-- /.card -->
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link active" href="#activity"
                                        data-toggle="tab">Absensi</a></li>
                                <li class="nav-item"><a class="nav-link" href="#profile" data-toggle="tab">Profil</a></li>
                                @can('admin')
                                    <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Manage</a></li>
                                @endcan
                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="active tab-pane" id="activity">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="startDate">Tanggal Awal</label>
                                                    <input type="date" name="startDate" id="startDate"
                                                        class="form-control" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="endDate">Tanggal Akhir</label>
                                                    <input type="date" name="endDate" id="endDate" class="form-control"
                                                        required>
                                                </div>
                                                <button id="searchButton" class="btn btn-primary mb-4">Cari</button>
                                            </div>
                                            <div class="col-md-8">
                                                <div id="searchResult"></div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">30 absensi terakhir untuk murid : <b>
                                                        {{ $murid->nama_lengkap }}</b></h3>
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body">
                                                <table id="example2" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Tanggal</th>
                                                            <th>Jam Absen</th>
                                                            <th class="text-center">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($absensi as $a)
                                                            @php
                                                                // Format tanggal Indonesia

                                                                // Format jam
                                                                $jam = date('H:i', strtotime($a->jam_absen)) . ' WIB';
                                                                // Tentukan class dan icon berdasarkan status
                                                                $statusClass = '';
                                                                $statusText = '';
                                                                $statusIcon = '';

                                                                switch ($a->status) {
                                                                    case 'hadir':
                                                                        $statusClass = 'table-success';
                                                                        $statusText = 'Hadir';
                                                                        $statusIcon = '/img/success.png';
                                                                        break;
                                                                    case 'terlambat':
                                                                        $statusClass = 'table-warning';
                                                                        $statusText = 'Terlambat';
                                                                        $statusIcon = '/img/warning.png';
                                                                        break;
                                                                    case 'izin':
                                                                        $statusClass = 'table-info';
                                                                        $statusText = 'Izin';
                                                                        $statusIcon = '/img/warning.png';

                                                                        break;
                                                                    case 'sakit':
                                                                        $statusClass = 'table-primary';
                                                                        $statusText = 'Sakit';
                                                                        $statusIcon = '/img/warning.png';

                                                                        break;
                                                                    case 'alpha':
                                                                        $statusClass = 'table-danger';
                                                                        $statusText = 'Alpha';
                                                                        $statusIcon = '/img/fail.png';
                                                                        break;
                                                                    default:
                                                                        $statusClass = 'table-secondary';
                                                                        $statusText = 'Tidak Diketahui';
                                                                }
                                                            @endphp

                                                            <tr>
                                                                <td class="{{ $statusClass }}">
                                                                    {{ $a->tanggal_verifikasi_formatted }}</td>
                                                                <td class="{{ $statusClass }}">{{ $jam }}</td>
                                                                <td class="text-center {{ $statusClass }}">
                                                                    @if ($statusIcon)
                                                                        <img src="{{ $statusIcon }}" width="20px"
                                                                            height="20px" alt="{{ $statusText }}">
                                                                        <span class="ml-2">{{ $statusText }}</span>
                                                                    @else
                                                                        {{ $statusText }}
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane" id="profile">
                                    <form class="form-horizontal" action="/edit-murid/{{ $murid->id }}" method="POST">
                                        @csrf
                                        <div class="form-group row">
                                            <label for="inputNis" class="col-sm-2 col-form-label">NIS</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="nis" name="nis"
                                                    placeholder="NIS" value="{{ $murid->nis }}" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">Nama Lengkap</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="nama" name="nama"
                                                    placeholder="Nama Lengkap" value="{{ $murid->nama_lengkap }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputKelas" class="col-sm-2 col-form-label">Kelas</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" id="kelas" name="id_kelas">
                                                    @if ($murid->id_kelas == null)
                                                        <option value="" selected>Belum ada kelas</option>
                                                    @endif
                                                    @foreach ($kelas as $k)
                                                        <option value="{{ $k->id_kelas }}"
                                                            {{ $k->id_kelas == $murid->id_kelas ? 'selected' : '' }}>
                                                            {{ $k->nama_kelas }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputTahun" class="col-sm-2 col-form-label">Tahun</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" id="tahun" name="id_tahun_akademik">
                                                    @foreach ($tahun as $t)
                                                        <option value="{{ $t->id_tahun_akademik }}"
                                                            {{ $t->id_tahun_akademik == $murid->id_tahun_akademik ? 'selected' : '' }}>
                                                            {{ $t->nama_tahun }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="offset-sm-2 col-sm-10">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" required> Data di atas sudah benar.</a>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        @can('admin')
                                            <div class="form-group row">
                                                <div class="offset-sm-2 col-sm-10">
                                                    <button type="submit" class="btn btn-warning">Ubah</button>
                                                </div>
                                            </div>
                                        @endcan
                                    </form>
                                </div>
                                <!-- /.tab-pane -->

                                <div class="tab-pane" id="settings">
                                    <div class="form-group row">
                                        <label for="inputNis" class="col-sm-2 col-form-label">Hapus Murid ?</label>
                                        <div class="col-sm-10">
                                            <button class="btn btn-danger" data-toggle="modal"
                                                data-target="#modalLoginForm">Hapus</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <!-- Pop Up Form Input -->
    <div class="modal fade" id="modalQr" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content col-md-9">
                <style>
                    .kartu {
                        width: 300px;
                        height: 100%;
                        border: 2px #black;
                        border-style: double;
                        box-shadow: 1px 1px 3px #ccc;
                        padding: 20px;
                        margin: 20px auto;
                        text-align: center;
                        font-family: inherit;
                        font-size: 13px;
                    }

                    .name {
                        font-weight: bold;
                        font-size: 16px;
                        margin-bottom: -5px;
                    }

                    .kelas {
                        font-weight: bold;
                        font-size: 13px;
                        margin-bottom: -5px;
                    }

                    .photo {
                        width: 80px;
                        height: 100px;
                        margin: 0 auto;
                    }

                    .nis {
                        margin-bottom: 10px;
                    }

                    .qr-code {
                        width: 100%;
                        height: 100%;
                        margin: 30px auto;
                        margin-bottom: 25px;
                    }
                </style>

                <div class="kartu">
                    <div class="photo">
                        <img src="/img/logo-sekolah.png" class="img-fluid">
                    </div>
                    <div class="name">
                        {{ $murid->nama }}
                    </div>
                    <div class="kelas">
                        {{ $murid->kelas->kelas }}
                    </div>
                    <div class="nis">
                        NIS: {{ $murid->nis }}
                    </div>
                    <div class="qr-code">
                        {{ $qr }}
                    </div>
                </div>
                <div class="button-container d-flex justify-content-center mb-3">
                    <a href="{{ url('/download-kartu-satuan/' . $murid->id) }}" class="btn btn-success">Download
                        Kartu</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Menampilkan Hasil dari Range Tanggal -->

    <!-- Pop Up Form Hapus Murid -->
    <div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold text-danger">Hapus Murid</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('murid.destroy', $murid->id_siswa) }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $murid->id_siswa }}">

                    <div class="modal-body mx-3">
                        <div class="md-form mb-0">
                            <p class="text-danger">Hapus : <b>{{ $murid->nama_lengkap }}</b></p>
                            <p class="text-danger"><i>Perhatian! Menghapus data siswa tidak dapat di undur! Apabila kamu
                                    sudah mengisi Absensi dengan data siswa ini sebelumnya, maka data absensi untuk siwa ini
                                    akan hilang permanen! Apabila kamu sudah mengerti tentang resiko ini, maka silahkan klik
                                    Submit.</i></p>
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
