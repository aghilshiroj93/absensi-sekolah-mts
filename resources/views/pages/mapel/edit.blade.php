@extends('layouts/main')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/beranda">Beranda</a> / Mata Pelajaran / Edit</li>
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">

            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">{{ $titlepage }}</h3>
                </div>

                <form action="/mapel/update/{{ $mapel->id_mapel }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label>Nama Mapel</label>
                            <input type="text" name="nama_mapel" class="form-control" value="{{ $mapel->nama_mapel }}"
                                required>
                        </div>
                        <div class="form-group">
                            <label>Kode Mapel</label>
                            <input type="text" name="kode_mapel" class="form-control" value="{{ $mapel->kode_mapel }}"
                                required>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea name="deskripsi" class="form-control">{{ $mapel->deskripsi }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Tahun Akademik</label>
                            <select name="id_tahun_akademik" class="form-control" required>
                                @foreach ($tahunAkademik as $t)
                                    <option value="{{ $t->id_tahun_akademik }}"
                                        {{ $mapel->id_tahun_akademik == $t->id_tahun_akademik ? 'selected' : '' }}>
                                        {{ $t->nama_tahun }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="aktif" {{ $mapel->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="tidak_aktif" {{ $mapel->status == 'tidak_aktif' ? 'selected' : '' }}>Tidak
                                    Aktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning">Update</button>
                        <a href="/mapel" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>

        </div>
    </section>
@endsection
