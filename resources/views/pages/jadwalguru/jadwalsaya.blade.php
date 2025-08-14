@extends('layouts/main')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/beranda">Beranda</a> / Kelas / Daftar Kelas</li>
@endsection

@section('content')
    <section class="content pb-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">


                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data di bawah adalah data dari keseluruhan Jadwal</h3>

                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Guru</th>
                                            <th>Mapel</th>
                                            <th>Kelas</th>
                                            <th>Hari</th>
                                            <th>Jam</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($jadwal as $j)
                                            <tr>
                                                <td>{{ $j->guru->nama_lengkap }}</td>
                                                <td>{{ $j->mapel->nama_mapel }}</td>
                                                <td>{{ $j->kelas->nama_kelas }}</td>
                                                <td>{{ $j->hari }}</td>
                                                <td>{{ $j->jam_mulai }} - {{ $j->jam_selesai }}</td>
                                                <td>
                                                    <a href="/jadwalguru/detail/{{ $j->kelas->id_kelas }}"
                                                        class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i> Detail
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
