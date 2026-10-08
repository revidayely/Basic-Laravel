<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\SiswaExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        $query = Siswa::query();

        if (!empty($keyword)) {
            $query->where(function($q) use ($keyword) {
                $q->where('nama', 'LIKE', '%' . $keyword . '%')
                  ->orWhere('nisn', 'LIKE', '%' . $keyword . '%')
                  ->orWhere('alamat', 'LIKE', '%' . $keyword . '%')
                  ->orWhere('email', 'LIKE', '%' . $keyword . '%');
            });
        }

        $daftarSiswa = $query->latest()->paginate(5);

        return view('siswa.index', compact('daftarSiswa', 'keyword'));
    }

    public function create()
    {
        return view('siswa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nisn'          => 'required|numeric|unique:siswas,nisn',
            'nama_lengkap'  => 'required|string|max:150',
            'email'         => 'required|email|unique:siswas,email',
            'tempat_lahir'  => 'required|string',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat'        => 'required|string',
            'nomor_hp'      => 'required|numeric',
            'foto'          => 'nullable|image|mimes:jpeg,jpg,png|max:2048'
        ], [
            'foto.image' => 'Berkas yang diunggah harus berupa gambar.',
            'foto.mimes' => 'Format gambar yang diperbolehkan hanya JPEG, JPG, dan PNG.',
            'foto.max'   => 'Ukuran file gambar tidak boleh melebihi 2 Megabytes (2048 KB).'
        ]);

        $data = [
            'nisn'          => $request->nisn,
            'nama'          => $request->nama_lengkap,
            'email'         => $request->email,
            'tempat_lahir'  => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat'        => $request->alamat,
            'no_hp'         => $request->nomor_hp,
        ];

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $namaFoto = $request->nisn . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/foto_siswa', $namaFoto);
            $data['foto'] = $namaFoto;
        }

        Siswa::create($data);

        return redirect()->route('siswa.index')->with('success', 'Data siswa dan foto profil berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);

        return view('siswa.edit', compact('siswa'));
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'nisn'          => 'required|numeric|unique:siswas,nisn,' . $id,
            'nama_lengkap'  => 'required|string|max:150',
            'email'         => 'required|email|unique:siswas,email,' . $id,
            'tempat_lahir'  => 'required|string',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat'        => 'required|string',
            'nomor_hp'      => 'required|numeric',
            'foto'          => 'nullable|image|mimes:jpeg,jpg,png|max:2048'
        ]);

        $data = [
            'nisn'          => $request->nisn,
            'nama'          => $request->nama_lengkap,
            'email'         => $request->email,
            'tempat_lahir'  => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat'        => $request->alamat,
            'no_hp'         => $request->nomor_hp,
        ];

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $namaFoto = $request->nisn . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/foto_siswa', $namaFoto);

            if ($siswa->foto && Storage::exists('public/foto_siswa/' . $siswa->foto)) {
                Storage::delete('public/foto_siswa/' . $siswa->foto);
            }

            $data['foto'] = $namaFoto;
        }

        $siswa->update($data);

        return redirect()->route('siswa.index')->with('success', 'Data siswa dan foto profil berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);

        if ($siswa->foto && Storage::exists('public/foto_siswa/' . $siswa->foto)) {
            Storage::delete('public/foto_siswa/' . $siswa->foto);
        }

        $siswa->delete();

        return redirect()->route('siswa.index')->with('success', 'Data siswa beserta berkas foto profil berhasil dihapus secara permanen!');
    }

    public function exportExcel()
    {
        return Excel::download(new SiswaExport, 'laporan-data-siswa.xlsx');
    }

    /**
     * Memproses unduhan file PDF data siswa
     */
    public function exportPdf()
    {
        $daftarSiswa = Siswa::latest()->get();
        $pdf = Pdf::loadView('siswa.pdf', compact('daftarSiswa'));

        // Atur ukuran kertas menjadi A4 dengan orientasi Portrait
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('laporan-data-siswa.pdf');
    }
}