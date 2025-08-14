<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Murid; // ← make sure this line is here!

class MuridSeeder extends Seeder
{
    public function run(): void
    {
        $murids = [
            //kelas 7
            ['nis' => 129081476,  'nama' => 'DONY IRWAN FIRDAUS',         'kelas_id' => 1, 'tahun_id' => 3],
            ['nis' => 3118009682, 'nama' => 'M. FARHAN',                  'kelas_id' => 1, 'tahun_id' => 3],
            ['nis' => 107241838,  'nama' => 'ROHMAT TULLAH',              'kelas_id' => 1, 'tahun_id' => 3],
            ['nis' => 91405432,   'nama' => 'SINTA RAMADAN AGUSTIN',      'kelas_id' => 1, 'tahun_id' => 3],
            ['nis' => 117874926,  'nama' => 'WAHYUDI',                    'kelas_id' => 1, 'tahun_id' => 3],
            ['nis' => 114847333,  'nama' => 'AULIYATUN NAJWA',            'kelas_id' => 1, 'tahun_id' => 3],
            ['nis' => 104082906,  'nama' => 'MUHAMMAD MISBAHUL MUNIR',    'kelas_id' => 1, 'tahun_id' => 3],
            ['nis' => 118036743,  'nama' => "HALIMATUS SA'DIYAH",         'kelas_id' => 1, 'tahun_id' => 3],
            ['nis' => 113733561,  'nama' => 'WILDA RISKI AMALIA',         'kelas_id' => 1, 'tahun_id' => 3],
            ['nis' => 3125129414, 'nama' => 'SYIFATUL AINI',              'kelas_id' => 1, 'tahun_id' => 3],
            ['nis' => 104805344,  'nama' => 'SITI FATIMAH',               'kelas_id' => 1, 'tahun_id' => 3],
            ['nis' => 117634606,  'nama' => 'M. DODIK IRWANZAH',          'kelas_id' => 1, 'tahun_id' => 3],
            ['nis' => 3120196066, 'nama' => 'FEBI APRILIA',               'kelas_id' => 1, 'tahun_id' => 3],
            ['nis' => 3126397636, 'nama' => 'ZAHROTUL AINI',              'kelas_id' => 1, 'tahun_id' => 3],
            ['nis' => 3126776874, 'nama' => 'KHOLIFATUL HASANAH',         'kelas_id' => 1, 'tahun_id' => 3],
            ['nis' => 3124672189, 'nama' => 'MOH RISKI',                  'kelas_id' => 1, 'tahun_id' => 3],
            ['nis' => 3120190298, 'nama' => 'MUHAMMAD HOLIL',             'kelas_id' => 1, 'tahun_id' => 3],
            ['nis' => 3124408353, 'nama' => 'HASAN FAHRI',                'kelas_id' => 1, 'tahun_id' => 3],
            ['nis' => 3120822440, 'nama' => 'AMIRA TUNGGA DEWI',          'kelas_id' => 1, 'tahun_id' => 3],
            ['nis' => 3111185775, 'nama' => 'AISYAH NUR AINI ZARA',       'kelas_id' => 1, 'tahun_id' => 3],
            ['nis' => 3128237757, 'nama' => 'AHMAD FAIS',                 'kelas_id' => 1, 'tahun_id' => 3],
            ['nis' => 3117256679, 'nama' => 'MUHAMMAD AIDIL FIKRI',       'kelas_id' => 1, 'tahun_id' => 3],
            ['nis' => 129153990,  'nama' => 'MUHAMMAD DAFIR',             'kelas_id' => 1, 'tahun_id' => 3],
            ['nis' => 123498536,  'nama' => 'SITI NUR AISAH',             'kelas_id' => 1, 'tahun_id' => 3],
            ['nis' => 3112809316, 'nama' => 'WARIDATUL HASANAH',          'kelas_id' => 1, 'tahun_id' => 3],
            ['nis' => 114228216,  'nama' => 'MEGA PURNAMASARI',           'kelas_id' => 1, 'tahun_id' => 3],

            //kelas 8
            ['nis' => 3102335213, 'nama' => 'FAREL',                         'kelas_id' => 2, 'tahun_id' => 2],
            ['nis' => 3100028735, 'nama' => 'AROFATUL HASANAH',             'kelas_id' => 2, 'tahun_id' => 2],
            ['nis' => 3090443282, 'nama' => 'M. ANDIKA PRATAMA',            'kelas_id' => 2, 'tahun_id' => 2],
            ['nis' => 3078357323, 'nama' => 'SELVIANA DEWI',                'kelas_id' => 2, 'tahun_id' => 2],
            ['nis' => 91372354,   'nama' => 'ACHMAD HAMDANI',               'kelas_id' => 2, 'tahun_id' => 2],
            ['nis' => 2021300067,       'nama' => 'ALFINO RADITIA PRATAMA',       'kelas_id' => 2, 'tahun_id' => 2],
            ['nis' => 105644120,  'nama' => 'DIKI RAMADANI',                'kelas_id' => 2, 'tahun_id' => 2],
            ['nis' => 97102657,   'nama' => 'FANDI HARTONO',                'kelas_id' => 2, 'tahun_id' => 2],
            ['nis' => 3116552438, 'nama' => 'HILYATUL JANNAH',              'kelas_id' => 2, 'tahun_id' => 2],
            ['nis' => 3117992914, 'nama' => 'HOLIFATUL KAMELIA',            'kelas_id' => 2, 'tahun_id' => 2],
            ['nis' => 123621003,  'nama' => 'IIN QURROTUL AINI',            'kelas_id' => 2, 'tahun_id' => 2],
            ['nis' => 106389767,  'nama' => 'M. FAIQUL HAMDANI',            'kelas_id' => 2, 'tahun_id' => 2],
            ['nis' => 105407621,  'nama' => 'M. RAHMAD FARIZY A.',          'kelas_id' => 2, 'tahun_id' => 2],
            ['nis' => 3110556634, 'nama' => 'MOH. ALFIN AMINULLAH',         'kelas_id' => 2, 'tahun_id' => 2],
            ['nis' => 113785388,  'nama' => 'MOHAMMAD AGUNG BAHTIAR',       'kelas_id' => 2, 'tahun_id' => 2],
            ['nis' => 3116868675, 'nama' => 'MOHAMMAD FIRMAN SYAH PUTRA',   'kelas_id' => 2, 'tahun_id' => 2],
            ['nis' => 101635111,  'nama' => 'MOHAMMAD HABIL',               'kelas_id' => 2, 'tahun_id' => 2],
            ['nis' => 101079019,  'nama' => 'MOH. HASAN ROSYIDIL ILHAM',    'kelas_id' => 2, 'tahun_id' => 2],
            ['nis' => 3119475490, 'nama' => 'MUHAMMAD ANGGA',               'kelas_id' => 2, 'tahun_id' => 2],
            ['nis' => 104267802,  'nama' => 'MUHAMMAT YASIN',               'kelas_id' => 2, 'tahun_id' => 2],
            ['nis' => 3083994477, 'nama' => 'MURIFATUL ISLAMIAH',           'kelas_id' => 2, 'tahun_id' => 2],
            ['nis' => 101577499,  'nama' => 'NUR FADHILA',                  'kelas_id' => 2, 'tahun_id' => 2],
            ['nis' => 3104935017, 'nama' => 'SALMAN NUR FARISI',            'kelas_id' => 2, 'tahun_id' => 2],
            ['nis' => 119409076,  'nama' => 'SITI ALFIATUS SYAROFAH',       'kelas_id' => 2, 'tahun_id' => 2],
            ['nis' => 107787893,  'nama' => 'MOH. FADJAR',                   'kelas_id' => 2, 'tahun_id' => 2],

            //kelas 9
            ['nis' => 3128911928, 'nama' => 'MUHAMMAD REZA MAULIDI',             'kelas_id' => 3, 'tahun_id' => 1],
            ['nis' => 3105909975, 'nama' => 'MOH. HUSEN HAIKAL',                'kelas_id' => 3, 'tahun_id' => 1],
            ['nis' => 3100276033, 'nama' => 'NUR FADILA',                       'kelas_id' => 3, 'tahun_id' => 1],
            ['nis' => 3106169381, 'nama' => 'AHMAD DAFFA HAFIDHUL AHKAM',       'kelas_id' => 3, 'tahun_id' => 1],
            ['nis' => 3117580879, 'nama' => 'RISKI ABDULLAH',                   'kelas_id' => 3, 'tahun_id' => 1],
            ['nis' => 3111459271, 'nama' => 'KAREL HOLILAH',                    'kelas_id' => 3, 'tahun_id' => 1],
            ['nis' => 3103403299, 'nama' => 'AMELIA REGINA PUTRI',              'kelas_id' => 3, 'tahun_id' => 1],
            ['nis' => 3117566111, 'nama' => 'ELINA FIRNANDA',                   'kelas_id' => 3, 'tahun_id' => 1],
            ['nis' => 3109733145, 'nama' => 'MUHAMMAD ADILLAH',                 'kelas_id' => 3, 'tahun_id' => 1],
            ['nis' => 3107552722, 'nama' => 'AHMAD RIDO`I',                     'kelas_id' => 3, 'tahun_id' => 1],
            ['nis' => 168922917,  'nama' => 'ALI MURTADOK',                     'kelas_id' => 3, 'tahun_id' => 1],
            ['nis' => 116165931,  'nama' => 'MUHAMMAD SULTAN TASYIM BILLAH',    'kelas_id' => 3, 'tahun_id' => 1],
            ['nis' => 95983404,   'nama' => 'REISMA NABILA FATARANI',           'kelas_id' => 3, 'tahun_id' => 1],
            ['nis' => 92109838,   'nama' => 'NABILA RAMADANI',                  'kelas_id' => 3, 'tahun_id' => 1],
            ['nis' => 104016498,  'nama' => 'DEVI CITASARI',                    'kelas_id' => 3, 'tahun_id' => 1],
            ['nis' => 3067543636, 'nama' => 'USWATUN HASANAH',                  'kelas_id' => 3, 'tahun_id' => 1],
        ];
        foreach ($murids as $murid) {
            Murid::updateOrCreate(
                ['nis' => $murid['nis']], // avoid duplicate entry error
                $murid
            );
        }
    }
}
