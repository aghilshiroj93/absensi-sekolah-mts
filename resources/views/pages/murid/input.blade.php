@extends('layouts/main')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/beranda">Beranda</a> / Murid / Input Murid</li>
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    @if (session()->has('success'))
                        <div class="alert alert-success">Data Murid Berhasil di Tambahkan.</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Form Tambah Murid</h3>
                        </div>

                        <form action="/input-murid-proses" method="post" enctype="multipart/form-data">

                            @csrf
                            <div class="card-body">
                                {{-- NIS --}}
                                <div class="form-group">
                                    <label>NIS</label>
                                    <input type="text" id="nis" name="nis" class="form-control"
                                        value="{{ old('nis') }}">
                                </div>

                                {{-- Nama Lengkap --}}
                                <div class="form-group">
                                    <label>Nama Lengkap</label>
                                    <input type="text" name="nama_lengkap" class="form-control"
                                        value="{{ old('nama_lengkap') }}">
                                </div>

                                {{-- Kelas --}}
                                <div class="form-group">
                                    <label>Kelas</label>
                                    <select class="form-control" name="id_kelas">
                                        @foreach ($kelas as $k)
                                            <option value="{{ $k->id_kelas }}">{{ $k->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Jenis Kelamin --}}
                                <div class="form-group">
                                    <label>Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="form-control">
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>

                                {{-- Tanggal Lahir --}}
                                <div class="form-group">
                                    <label>Tanggal Lahir</label>
                                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control"
                                        value="{{ old('tanggal_lahir') }}">
                                </div>

                                {{-- Umur --}}
                                <div class="form-group">
                                    <label>Umur</label>
                                    <input type="text" id="umur" name="umur" class="form-control" readonly>
                                </div>

                                {{-- Tempat Lahir --}}
                                <div class="form-group">
                                    <label>Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" class="form-control"
                                        value="{{ old('tempat_lahir') }}">
                                </div>

                                {{-- Nomor Telepon --}}
                                <div class="form-group">
                                    <label>Nomor Telepon</label>
                                    <input type="text" name="nomor_telepon" class="form-control"
                                        value="{{ old('nomor_telepon') }}">
                                </div>

                                {{-- Foto --}}
                                <div class="form-group">
                                    <label>Foto</label>
                                    <input type="file" name="foto" class="form-control">
                                </div>

                                {{-- Alamat --}}
                                <div class="form-group">
                                    <label>Alamat</label>
                                    <textarea name="alamat" class="form-control">{{ old('alamat') }}</textarea>
                                </div>

                                {{-- Nama Ayah --}}
                                <div class="form-group">
                                    <label>Nama Ayah</label>
                                    <input type="text" name="nama_ayah" class="form-control"
                                        value="{{ old('nama_ayah') }}">
                                </div>

                                {{-- Nama Ibu --}}
                                <div class="form-group">
                                    <label>Nama Ibu</label>
                                    <input type="text" name="nama_ibu" class="form-control"
                                        value="{{ old('nama_ibu') }}">
                                </div>

                                {{-- Nomor Telepon Ayah --}}
                                <div class="form-group">
                                    <label>Nomor Telepon Ayah</label>
                                    <input type="text" name="nomor_telepon_ayah" class="form-control"
                                        value="{{ old('nomor_telepon_ayah') }}">
                                </div>

                                {{-- Nomor Telepon Ibu --}}
                                <div class="form-group">
                                    <label>Nomor Telepon Ibu</label>
                                    <input type="text" name="nomor_telepon_ibu" class="form-control"
                                        value="{{ old('nomor_telepon_ibu') }}">
                                </div>

                                {{-- Barcode (readonly, auto dari NIS) --}}
                                <div class="form-group">
                                    <label>Barcode</label>
                                    <input type="text" id="barcode" name="barcode" class="form-control" readonly>
                                </div>

                                {{-- Status --}}
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="aktif">Aktif</option>
                                        <option value="tidak_aktif">Tidak Aktif</option>
                                    </select>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Script perhitungan umur & barcode --}}
    <script>
        document.getElementById('tanggal_lahir').addEventListener('change', function() {
            let dob = new Date(this.value);
            if (!isNaN(dob.getTime())) {
                let today = new Date();
                let age = today.getFullYear() - dob.getFullYear();
                let m = today.getMonth() - dob.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
                    age--;
                }
                document.getElementById('umur').value = age + " tahun";
            } else {
                document.getElementById('umur').value = '';
            }
        });

        document.getElementById('nis').addEventListener('input', function() {
            let nis = this.value.trim();
            if (nis !== '') {
                document.getElementById('barcode').value = "BAR-" + nis;
            } else {
                document.getElementById('barcode').value = '';
            }
        });
    </script>
@endsection
