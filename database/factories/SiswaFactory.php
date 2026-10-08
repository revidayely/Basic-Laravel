<?php
namespace Database\Factories;
use App\Models\Siswa;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Validation\Rules\Unique;

class SiswaFactory extends Factory
{
 protected $model = Siswa::class;
 public function definition()
 {
 // Mengatur Faker agar menghasilkan data lokal Indonesia
 $faker = \Faker\Factory::create('id_ID');
 return [
 'nisn' => $faker->unique()->numerify('##########'), // 10 digit angka acak
 'nama' => $faker->name(),
 'alamat' => $faker->address(),
 'email' => $faker->unique()->safeEmail(),
 'tempat_lahir' => $faker->city(),
 'tanggal_lahir' => $faker->date('Y-m-d', '2008-12-31'), // Tanggal lahir
 'jenis_kelamin' => $faker->randomElement(['L', 'P']),
  'email' => $faker->unique()->safeEmail(),
  'no_hp' => $faker->Unique()->phoneNumber()

 ];
 }
}