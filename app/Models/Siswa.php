<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Siswa extends Model
{
 use HasFactory;
 protected $table = 'siswas';

protected $fillable = [
    'nisn',
    'nama',
    'email',
    'tempat_lahir', // <-- Tambahkan baris ini
    'tanggal_lahir',
    'jenis_kelamin',
    'alamat',
    'no_hp',
    'foto'
];
 
}