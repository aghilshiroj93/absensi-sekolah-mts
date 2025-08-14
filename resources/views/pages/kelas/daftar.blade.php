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
                                <a href="" data-toggle="modal" data-target="#modalLoginForm"><button
                                        class="btn-sm btn-success">
                                        <span>Tambah Data</span>
                                    </button></a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Kelas</th>

                                            {{-- <th>Tahun Akademik</th> --}}
                                            {{-- <th>Guru Pengajar</th> --}}
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no = 1; @endphp
                                        @foreach ($kelas as $k)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $k->nama_kelas }}</td>

                                                {{-- <td>{{ $k->tahunAkademik->nama_tahun ?? '-' }}</td> --}}
                                                {{-- <td>{{ $k->guruPengajar->nama_lengkap ?? '-' }}</td> --}}
                                                <td class="text-center">
                                                    <a href="/kelas/daftar/{{ $k->id_kelas }}" class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i> Detail
                                                    </a>
                                                    {{-- <a href="{{ route('kelas.edit', $k->id_kelas) }}"
                                                        class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a> --}}
                                                    <form action="{{ route('kelas.destroy', $k->id_kelas) }}" method="POST"
                                                        style="display:inline;">
                                                        @csrf

                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Yakin hapus data?')">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach

                                        @if ($kelas->count() == 0)
                                            <tr>
                                                <td colspan="6" class="text-center">Tidak ada data kelas.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold">Masukkan Kelas</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ url('/kelas-proses') }}" method="post">
                    @csrf
                    <div class="modal-body mx-3">
                        {{-- Nama Kelas --}}
                        <div class="md-form mb-3">
                            <input type="text" id="input-nama-kelas"
                                class="form-control validate @error('kelas') is-invalid @enderror" name="kelas"
                                value="{{ old('kelas') }}" placeholder="Masukkan Kelas (misal. XI IPS A atau XII-IPA)"
                                required>
                            @error('kelas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tahun Akademik --}}
                        <div class="md-form mb-0">
                            <select name="id_tahun_akademik"
                                class="form-control @error('id_tahun_akademik') is-invalid @enderror" required>
                                <option value="" disabled {{ old('id_tahun_akademik') ? '' : 'selected' }}>
                                    -- Pilih Tahun Akademik --
                                </option>
                                @foreach ($tahunAkademik as $ta)
                                    <option value="{{ $ta->id_tahun_akademik }}"
                                        {{ (string) old('id_tahun_akademik') === (string) $ta->id_tahun_akademik || ($ta->status === 'aktif' && !old('id_tahun_akademik')) ? 'selected' : '' }}>
                                        {{ $ta->nama_tahun }} {{ $ta->status === 'aktif' ? '(Aktif)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_tahun_akademik')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer d-flex justify-content-center">
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
