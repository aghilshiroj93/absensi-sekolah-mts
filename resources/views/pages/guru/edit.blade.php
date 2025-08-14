@extends('layouts/main')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/beranda">Beranda</a> / Guru / Edit</li>
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">

            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">{{ $titlepage }}</h3>
                </div>

                <form action="{{ url('/guru/update/' . $guru->id_guru) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">

                        <div class="form-group">
                            <label>NIP</label>
                            <input type="text" name="nip" class="form-control" value="{{ old('nip', $guru->nip) }}"
                                required>
                        </div>

                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control"
                                value="{{ old('nama_lengkap', $guru->nama_lengkap) }}" required>
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', $guru->email) }}" required>
                        </div>

                        <div class="form-group">
                            <label>User</label>
                            <select name="id_user" class="form-control" required>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ $guru->id_user == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Mapel</label>
                            <select name="id_mapel[]" class="form-control" multiple required>
                                @foreach ($mapel as $m)
                                    <option value="{{ $m->id_mapel }}">{{ $m->nama_mapel }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Tekan Ctrl (Windows) / Command (Mac) untuk memilih lebih dari
                                satu.</small>
                        </div>

                        <div class="form-group">
                            <label>Foto</label><br>
                            @if ($guru->foto)
                                <img src="{{ asset('storage/' . $guru->foto) }}" alt="Foto Guru" width="150"
                                    class="mb-2">
                            @endif
                            <input type="file" name="foto" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-control" required>
                                <option value="L" {{ $guru->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki
                                </option>
                                <option value="P" {{ $guru->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Jabatan</label>
                            <select name="jabatan" class="form-control" required>
                                <option value="guru_pengajar" {{ $guru->jabatan == 'guru_pengajar' ? 'selected' : '' }}>
                                    Guru Pengajar</option>
                                <option value="guru_bk" {{ $guru->jabatan == 'guru_bk' ? 'selected' : '' }}>Guru BK
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>No Telepon</label>
                            <input type="text" name="nomor_telepon" class="form-control"
                                value="{{ old('nomor_telepon', $guru->nomor_telepon) }}" required>
                        </div>

                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control">{{ old('alamat', $guru->alamat) }}</textarea>
                        </div>

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning">Update</button>
                        <a href="{{ url('/guru') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>

        </div>
    </section>
@endsection
