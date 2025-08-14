@extends('layouts/main')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/beranda">Beranda</a> / Mata Pelajaran</li>
@endsection

@section('content')
    <section class="content pb-5">
        <div class="container-fluid">

            @if (session()->has('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session()->has('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $titlepage }}</h3>
                    <div class="card-tools">
                        <a href="/mapel/create" class="btn btn-primary btn-sm">Tambah Mapel</a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Responsive table wrapper -->
                    <div class="table-responsive">
                        <table id="example1" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Mapel</th>
                                    <th>Kode</th>
                                    <th>Tahun Akademik</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mapel as $i => $m)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $m->nama_mapel }}</td>
                                        <td>{{ $m->kode_mapel }}</td>
                                        <td>{{ $m->tahunAkademik->nama_tahun ?? '-' }}</td>
                                        <td>
                                            @if ($m->status == 'aktif')
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-danger">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="/mapel/{{ $m->id_mapel }}/edit"
                                                class="btn btn-warning btn-sm">Edit</a>
                                            <form action="/mapel/delete" method="POST" style="display:inline-block">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $m->id_mapel }}">
                                                <button type="submit" onclick="return confirm('Yakin dihapus?')"
                                                    class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($mapel->count() == 0)
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data mapel.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection
