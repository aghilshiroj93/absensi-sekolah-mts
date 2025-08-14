@extends('layouts/main')

<!-- DataTables -->
<link rel="stylesheet" href="/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/beranda">Beranda</a> Kelas / <a href="/kelas/daftar">Daftar Kelas </a>/ Detail</li>
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title pb-3 col-md-10">Data di bawah adalah seluruh murid dari kelas :
                                <b>{{ $kelas->nama_kelas }}</b>
                            </h3>


                            <div class="card-tools">
                                <div class="input-group input-group-sm" style="width: 100px;">
                                    {{-- <div class="input-group-append">
                    <a href="" data-toggle="modal" data-target="#modalLoginForm"><button class="btn-sm btn-success">
                      <span>Tambah Data</span>
                    </button></a>
                  </div> --}}
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nomor</th>
                                            <th>NIS</th>
                                            <th>Nama Lengkap</th>
                                            <th>Kelas</th>
                                            <th>Tahun</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no=1; @endphp
                                        @foreach ($murid as $m)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $m->nis }}</td>
                                                <td>{{ $m->nama_lengkap }}</td>
                                                <td>{{ $m->kelas->nama_kelas }}</td>
                                                <td>{{ $m->kelas->tahun->nama_tahun }}</td>
                                                <td><a href="/detail-murid/{{ $m->id_siswa }}"><button
                                                            class="btn-md btn-info">Detail</button></a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                    <!-- /.card -->
                </div>


                <div class="col-lg-6">
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    <!-- Pop Up Form Hapus Murid -->
    <div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold text-danger">HAPUS KELAS</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="/kelas/hapus/{{ $kelas->id }}" method="post">
                    @csrf
                    <div class="modal-body mx-3">
                        <div class="md-form mb-0">
                            <p class="text-danger">Anda ingin menghapus : <b>{{ $kelas->kelas }}</b></p>
                            <p class="text-danger"><i>Perhatian! Menghapus data kelas dapat menyebabkan <b>KERUSAKAN DATA
                                        SECARA PERMANEN</b>!. Harap diskusikan terlebih dahulu kepada pihak pengembang atau
                                    pihak pengelola pada sekolah anda!. Jika anda sudah yakin akan tindakan anda, maka klik
                                    "Saya Yakin!"
                                    <br>
                                    <br>
                                    <b>Pihak pengembang tidak bertanggung jawab atas kerusakan data yang di sebabkan
                                        menghapus data kelas ini!</b></i></p>
                            <hr>
                            <span><input type="hidden" name="captcha" value="bypass"></span>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="submit" class="btn btn-danger">Saya Yakin!</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
