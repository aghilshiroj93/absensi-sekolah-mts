@extends('layouts/main')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/beranda">Beranda</a> / Guru / Tambah</li>
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">

            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">{{ $titlepage }}</h3>
                </div>

                <form action="{{ url('/guru/store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label>NIP</label>
                            <input type="number" name="nip" class="form-control" value="{{ old('nip') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap') }}"
                                required>
                        </div>

                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" value="{{ old('username') }}"
                                required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
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
                            <label>Foto</label>
                            <input type="file" name="foto" class="form-control" id="foto" accept="image/*">
                            <div class="mt-2">
                                <img id="preview-foto" src="" alt="Preview Foto"
                                    style="max-height: 150px; display: none; border: 1px solid #ccc; padding: 4px; border-radius: 4px;">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-control" required>
                                <option value="">-- Pilih --</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Jabatan</label>
                            <select name="jabatan" class="form-control" required>
                                <option value="">-- Pilih --</option>
                                <option value="guru_pengajar">Guru Pengajar</option>
                                <option value="guru_bk">Guru BK</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>No Telepon</label>
                            <input type="text" name="nomor_telepon" class="form-control"
                                value="{{ old('nomor_telepon') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control">{{ old('alamat') }}</textarea>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning">Simpan</button>
                        <a href="{{ url('/guru') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>

        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.getElementById('foto').addEventListener('change', function(event) {
            const preview = document.getElementById('preview-foto');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                preview.src = '';
                preview.style.display = 'none';
            }
        });
    </script>
@endpush
