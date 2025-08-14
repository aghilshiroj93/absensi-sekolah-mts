@extends('layouts/main')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/beranda">Beranda</a> / Jadwal Guru / Tambah</li>
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{ $titlepage }}</h3>
                </div>

                <form action="{{ route('jadwalguru.store') }}" method="POST">
                    @csrf

                    <div class="card-body">
                        <div class="form-group">
                            <label>Guru</label>
                            <select name="id_guru" id="id_guru" class="form-control" required>
                                <option value="">-- Pilih Guru --</option>
                                @foreach ($guru as $g)
                                    <option value="{{ $g->id_guru }}">{{ $g->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Pilih Tahun Akademik</label>
                            <select name="id_tahun_akademik" class="form-control" required>
                                <option value="">-- Pilih Tahun Akademik --</option>
                                @foreach ($tahun as $t)
                                    <option value="{{ $t->id_tahun_akademik }}">{{ $t->nama_tahun }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Mapel</label>
                            <select name="id_mapel" class="form-control" id="mapelSelect" required>
                                <option value="">-- Pilih Mapel --</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Kelas</label>
                            <select name="id_kelas" class="form-control" id="kelasSelect" required>
                                <option value="">-- Pilih Kelas --</option>
                            </select>
                        </div>


                        <div class="form-group">
                            <label>Hari</label>
                            <select name="hari" class="form-control" required>
                                <option value="">-- Pilih Hari --</option>
                                @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                                    <option value="{{ $hari }}">{{ $hari }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Jam Mulai</label>
                            <input type="time" name="jam_mulai" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Jam Selesai</label>
                            <input type="time" name="jam_selesai" class="form-control" required>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('jadwalguru.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>

        </div>
    </section>
@endsection


@push('scripts')
    <script>
        document.querySelector('select[name="id_guru"]').addEventListener('change', function() {
            let guruId = this.value;
            console.log("Guru dipilih:", guruId);

            let mapelSelect = document.getElementById('mapelSelect');
            let kelasSelect = document.getElementById('kelasSelect');

            // Reset dropdown
            mapelSelect.innerHTML = '<option value="">-- Pilih Mapel --</option>';
            kelasSelect.innerHTML = '<option value="">-- Pilih Kelas --</option>';

            if (guruId) {
                let url = `/guru/${guruId}/mapel-kelas`;
                console.log("Mengambil data dari URL:", url);

                fetch(url)
                    .then(response => {
                        console.log("Response status:", response.status);
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log("Data diterima dari server:", data);

                        let mapelKosong = true;
                        let kelasKosong = true;

                        if (data.mapel && Array.isArray(data.mapel) && data.mapel.length > 0) {
                            mapelKosong = false;
                            data.mapel.forEach(m => {
                                console.log("Menambahkan mapel:", m);
                                let option = new Option(m.nama, m.id);
                                mapelSelect.add(option);
                            });
                        } else {
                            mapelSelect.innerHTML = '<option value="">-- Tidak Ada Mapel --</option>';
                        }

                        if (data.kelas && Array.isArray(data.kelas) && data.kelas.length > 0) {
                            kelasKosong = false;
                            data.kelas.forEach(k => {
                                console.log("Menambahkan kelas:", k);
                                let option = new Option(k.nama, k.id);
                                kelasSelect.add(option);
                            });
                        } else {
                            kelasSelect.innerHTML = '<option value="">-- Tidak Ada Kelas --</option>';
                        }

                        // Tampilkan alert jika ada yang kosong
                        if (mapelKosong && kelasKosong) {
                            alert("Guru ini belum memiliki mapel dan kelas yang terdaftar.");
                        } else if (mapelKosong) {
                            alert("Guru ini belum memiliki mapel yang terdaftar.");
                        } else if (kelasKosong) {
                            alert("Guru ini belum memiliki kelas yang terdaftar.");
                        }
                    })
                    .catch(err => {
                        console.error("Terjadi error saat mengambil data:", err);
                        alert("Terjadi kesalahan saat mengambil data. Silakan coba lagi.");
                    });
            } else {
                console.warn("Guru belum dipilih.");
                alert("Silakan pilih guru terlebih dahulu.");
            }
        });
    </script>
@endpush
