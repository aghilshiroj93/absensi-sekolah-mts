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
                        <a href="/guru/create" class="btn btn-primary btn-sm">Tambah Guru</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example1" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Foto</th>
                                    <th>NIP</th>
                                    <th>Nama Lengkap</th>
                                    <th>Email</th>
                                    <th>Mapel</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Jabatan</th>
                                    <th>No Telepon</th>
                                    <th>Alamat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($guru as $g)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if ($g->foto && file_exists(public_path('storage/' . $g->foto)))
                                                <img src="{{ asset('storage/' . $g->foto) }}" alt="Foto Guru" width="50"
                                                    height="50" class="rounded-circle">
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $g->nip }}</td>
                                        <td>{{ $g->nama_lengkap }}</td>
                                        <td>{{ $g->email }}</td>
                                        <td>
                                            @if ($g->mapel->isNotEmpty())
                                                {{ $g->mapel->pluck('nama_mapel')->implode(', ') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>

                                        <td>{{ $g->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $g->jabatan)) }}</td>
                                        <td>{{ $g->nomor_telepon }}</td>
                                        <td>{{ $g->alamat ?? '-' }}</td>
                                        <td>
                                            <a href="{{ url('/guru/' . $g->id_guru . '/edit') }}"
                                                class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ url('/guru/delete') }}" method="POST" class="d-inline"
                                                onsubmit="return confirm('Yakin hapus guru ini?')">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $g->id_guru }}">
                                                <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($guru->isEmpty())
                                    <tr>
                                        <td colspan="11" class="text-center">Tidak ada data guru</td>
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
