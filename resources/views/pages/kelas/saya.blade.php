@extends('layouts/main')

<!-- DataTables -->
<link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/beranda">Beranda</a> / Kelas Saya</li>
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            {{-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> --}}
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data di bawah adalah data dari kelas yang anda ajar</h3>
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
                                            <th>Kelas</th>
                                            <th>Mata Pelajaran</th>
                                            <th>Jumlah Siswa</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $nomorurut = 1 @endphp
                                        @foreach ($kelas as $k)
                                            <tr>
                                                <td>{{ $nomorurut++ }}</td>
                                                <td>{{ $k->nama_kelas }}</td>

                                                <td>
                                                    @if ($k->guruPengajar->mapel->isNotEmpty())
                                                        {{ $k->guruPengajar->mapel->pluck('nama_mapel')->implode(', ') }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>

                                                <td>{{ $k->siswa_count }}</td>

                                                <td class="text-center">
                                                    <a href="/kelas/daftar/{{ $k->id_kelas }}"><button
                                                            class="btn btn-sm btn-info">
                                                            <span>Detail</span>
                                                        </button></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    <!-- Pop Up Form Input -->
    <div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold">Masukkan Kelas</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="/kelas-proses" method="post">
                    @csrf
                    <div class="modal-body mx-3">
                        <div class="md-form mb-0">
                            <input type="text" id="defaultForm-email" class="form-control validate" name="kelas"
                                placeholder="Masukkan Kelas (misal. XII atau XI-IPA)" required>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
