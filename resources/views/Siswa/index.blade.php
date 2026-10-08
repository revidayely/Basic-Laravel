<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Data Siswa - Modul 13</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
  <div class="container mt-5 mb-5">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-primary text-white py-3">
        <h4 class="card-title mb-0 fw-bold">🏫 DATA INDUK SISWA</h4>
        <p class="mb-0 text-white-50">Menampilkan data terurut abjad & dilengkapi alamat</p>
      </div>

      <div class="card-body p-4">
        <!-- Tombol Aksi Tambah Siswa & Export -->
        <div class="d-flex justify-content-between align-items-center mb-3"> 
          <h4 class="my-0">Daftar Data Siswa</h4> 
          <div>
            <a href="{{ route('siswa.create') }}" class="btn btn-primary btn-sm">+ Tambah Siswa Baru</a> 
            <a href="{{ route('siswa.export.excel') }}" class="btn btn-success btn-sm">Export Excel</a>
            <a href="{{ route('siswa.export.pdf') }}" class="btn btn-danger btn-sm" target="_blank">Export PDF</a>
          </div>
        </div> 

        @if(session('success')) 
        <div class="alert alert-success alert-dismissible fade show" role="alert"> 
          {{ session('success') }} 
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div> 
        @endif

        <div class="row mb-4">
          <div class="col-md-6">
            <form action="{{ route('siswa.index') }}" method="GET">
              <div class="input-group">
                <input type="text" 
                       name="keyword" 
                       class="form-control" 
                       placeholder="Cari berdasarkan nama, nisn, atau alamat..." 
                       value="{{ $keyword ?? '' }}">
                <button class="btn btn-primary" type="submit">
                  <i class="bi bi-search"></i> Cari
                </button>
                @if(!empty($keyword))
                  <a href="{{ route('siswa.index') }}" class="btn btn-secondary">Reset</a>
                @endif
              </div>
            </form>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered align-middle"> 
            <thead> 
              <tr> 
                <th>No</th> 
                <th>Foto</th> 
                <th>NISN</th> 
                <th>Nama Lengkap</th> 
                <th>Tempat Lahir</th> 
                <th>Alamat</th> 
                <th>Nomor HP</th> 
                <th>Aksi</th> 
              </tr> 
            </thead> 
            <tbody> 
              @forelse($daftarSiswa as $siswa) 
              <tr> 
                <td>{{ ($daftarSiswa->currentPage() - 1) * $daftarSiswa->perPage() + $loop->iteration }}</td> 
                <td class="text-center"> 
                  @if($siswa->foto) 
                    <img src="{{ asset('storage/foto_siswa/' . $siswa->foto) }}" 
                         alt="Foto Siswa" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                  @else 
                    <div class="bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-size: 12px;">
                      No Image 
                    </div> 
                  @endif 
                </td> 
                <td>{{ $siswa->nisn }}</td> 
                <td>{{ $siswa->nama ?? $siswa->nama_lengkap }}</td> 
                <td>{{ $siswa->tempat_lahir }}</td> 
                <td>{{ $siswa->alamat }}</td> 
                <td>{{ $siswa->no_hp ?? $siswa->nomor_hp }}</td> 
                <td class="text-center">
                  <a href="{{ route('siswa.edit', $siswa->id) }}" class="btn btn-warning btn-sm">Edit</a>
                  
                  <form action="{{ route('siswa.destroy', $siswa->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data siswa ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                  </form>
                </td>
              </tr> 
              @empty 
              <tr>
                <td colspan="8" class="text-center text-danger fw-bold py-4">
                  Data siswa tidak ditemukan.
                </td>
              </tr>
              @endforelse 
            </tbody> 
          </table>
        </div>

        <div class="d-flex justify-content-end mt-3">
          {!! $daftarSiswa->appends(['keyword' => $keyword])->links() !!}
        </div>

      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>