@extends('layouts/main')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/beranda">Beranda</a> / Jadwal Guru / Edit</li>
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">

            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">{{ $titlepage }}</h3>
                </div>


                <form action="{{ route('jadwalguru.update', $jadwalGuru->id_jadwal) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf


                    <div class="card-body">
                        <div class="form-group">
                            <label>Guru</label>
                            <select name="id_guru" id="guruSelect" class="form-control" required>
                                <option value="">-- Pilih Guru --</option>
                                @foreach ($guru as $g)
                                    <option value="{{ $g->id_guru }}"
                                        {{ $jadwalGuru->id_guru == $g->id_guru ? 'selected' : '' }}>
                                        {{ $g->nama_lengkap }}
                                    </option>
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
                                @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                                    <option {{ $jadwalGuru->hari == $hari ? 'selected' : '' }}>{{ $hari }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Jam Mulai</label>
                            <input type="time" name="jam_mulai" class="form-control" value="{{ $jadwalGuru->jam_mulai }}"
                                required>
                        </div>

                        <div class="form-group">
                            <label>Jam Selesai</label>
                            <input type="time" name="jam_selesai" class="form-control"
                                value="{{ $jadwalGuru->jam_selesai }}" required>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning">Update</button>
                        <a href="{{ route('jadwalguru.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>

        </div>
    </section>
@endsection



@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let guruSelect = document.getElementById('guruSelect');
            let mapelSelect = document.getElementById('mapelSelect');
            let kelasSelect = document.getElementById('kelasSelect');

            let selectedMapelId = "{{ $jadwalGuru->id_mapel }}";
            let selectedKelasId = "{{ $jadwalGuru->id_kelas }}";

            function loadMapelKelas(guruId, setSelected = false) {
                if (!guruId) {
                    mapelSelect.innerHTML = '<option value="">-- Pilih Mapel --</option>';
                    kelasSelect.innerHTML = '<option value="">-- Pilih Kelas --</option>';
                    return;
                }

                fetch(`/guru/${guruId}/mapel-kelas`)
                    .then(response => response.json())
                    .then(data => {
                        mapelSelect.innerHTML = '<option value="">-- Pilih Mapel --</option>';
                        kelasSelect.innerHTML = '<option value="">-- Pilih Kelas --</option>';

                        if (data.mapel && data.mapel.length > 0) {
                            data.mapel.forEach(m => {
                                let option = new Option(m.nama, m.id);
                                if (setSelected && m.id == selectedMapelId) {
                                    option.selected = true;
                                }
                                mapelSelect.add(option);
                            });
                        } else {
                            alert("Guru ini belum memiliki mapel yang terdaftar.");
                        }

                        if (data.kelas && data.kelas.length > 0) {
                            data.kelas.forEach(k => {
                                let option = new Option(k.nama, k.id);
                                if (setSelected && k.id == selectedKelasId) {
                                    option.selected = true;
                                }
                                kelasSelect.add(option);
                            });
                        } else {
                            alert("Guru ini belum memiliki kelas yang terdaftar.");
                        }
                    })
                    .catch(err => {
                        console.error("Error:", err);
                        alert("Terjadi kesalahan saat mengambil data.");
                    });
            }

            // Load saat pertama kali halaman dibuka
            loadMapelKelas(guruSelect.value, true);

            // Reload saat guru diganti
            guruSelect.addEventListener('change', function() {
                loadMapelKelas(this.value, false);
            });
        });
    </script>
@endpush
