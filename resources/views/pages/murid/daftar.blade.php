@extends('layouts/main')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/beranda">Beranda</a> / Murid / Daftar Murid</li>
@endsection

@section('content')
    <!-- Main content -->
    <!-- Main content -->
    <section class="content pb-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    @if (session()->has('deleted'))
                        <div class="alert alert-danger" role="alert">
                            Data Murid Berhasil di Hapus!.
                        </div>
                    @endif
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data di bawah adalah data dari keseluruhan murid</h3>
                            <div class="card-tools">
                                <a href="{{ route('murid.input') }}"><button class="btn-sm btn-success">
                                        <span>Tambah Murid</span>
                                    </button></a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <!-- Responsive table wrapper -->
                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>NIS</th>
                                            <th>Nama Lengkap</th>
                                            <th>Kelas</th>
                                            <th>Tahun</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($murid as $m)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $m->nis }}
                                                </td>
                                                <td>{{ $m->nama_lengkap }}</td>
                                                @if (empty($m->kelas->nama_kelas))
                                                    <td><i class="text-danger">Kelas tidak di temukan.</i></td>
                                                @else
                                                    <td><a
                                                            href="/kelas/daftar/{{ $m->id_kelas }}">{{ $m->kelas->nama_kelas }}</a>
                                                    </td>
                                                @endif
                                                <td>{{ $m->kelas->tahun->nama_tahun }}</td>


                                                <td>
                                                    <a href="/detail-murid/{{ $m->id_siswa }}"
                                                        class="btn btn-warning btn-sm">Detail</a>
                                                    <form action="{{ route('murid.destroy', $m->id_siswa) }}" method="POST"
                                                        style="display:inline-block">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $m->id_siswa }}">
                                                        <button type="submit" onclick="return confirm('Yakin dihapus?')"
                                                            class="btn btn-danger btn-sm">Hapus</button>
                                                    </form>
                                                </td>

                                            </tr>
                                        @endforeach
                                        @if ($murid->count() == 0)
                                            <tr>
                                                <td colspan="6" class="text-center">Tidak ada data mapel.</td>
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
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
@endsection
