@extends('layouts/main')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/beranda">Beranda</a> / Kelas / Daftar Kelas</li>
@endsection

@section('content')
    <section class="content pb-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    @if (session()->has('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session()->has('deleted'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('deleted') }}
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data di bawah adalah data dari keseluruhan kelas</h3>
                            <div class="card-tools">
                                <a href="{{ route('jadwalguru.create') }}" class="btn btn-primary btn-sm">Tambah Jadwal
                                    Guru</a>
                            </div>
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
                                                    <a href="{{ route('jadwalguru.edit', $j->id_jadwal) }}"
                                                        class="btn btn-warning btn-sm">Edit</a>
                                                    <form action="{{ route('jadwalguru.destroy', $j->id_jadwal) }}"
                                                        method="POST" style="display:inline">
                                                        @csrf
                                                        <button onclick="return confirm('Yakin hapus?')"
                                                            class="btn btn-danger btn-sm">Hapus</button>
                                                    </form>
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
