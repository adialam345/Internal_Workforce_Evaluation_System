<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\Employee;
use App\Models\Evaluation;
use App\Models\EvaluationAnswer;
use App\Models\EvaluationQuestion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Admin
        $admin = User::create([
            'name' => 'Super Administrator',
            'email' => 'admin@workeval.local',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'department' => 'Human Resources',
            'phone' => '081234567890',
            'is_active' => true,
        ]);

        // 2. Create Evaluators
        $evaluators = [];
        $evaluatorData = [
            ['name' => 'Budi Santoso', 'email' => 'budi@workeval.local', 'dept' => 'Produksi', 'phone' => '081298765431'],
            ['name' => 'Siti Rahmawati', 'email' => 'siti@workeval.local', 'dept' => 'Logistik & Gudang', 'phone' => '081298765432'],
            ['name' => 'Ahmad Hidayat', 'email' => 'ahmad@workeval.local', 'dept' => 'Quality Control', 'phone' => '081298765433'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi@workeval.local', 'dept' => 'Maintenance', 'phone' => '081298765434'],
            ['name' => 'Hendro Wijaya', 'email' => 'hendro@workeval.local', 'dept' => 'Operasional', 'phone' => '081298765435'],
        ];

        foreach ($evaluatorData as $data) {
            $evaluators[] = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => 'evaluator',
                'department' => $data['dept'],
                'phone' => $data['phone'],
                'is_active' => true,
            ]);
        }

        // 3. Create Evaluation Questions
        $questions = [
            [
                'category' => 'Kedisiplinan & Integritas',
                'question' => 'Kepatuhan terhadap jam kerja, SOP, dan tata tertib perusahaan.',
                'description' => 'Menilai ketepatan waktu hadir, kepatuhan atribut kerja, dan etika kerja.',
                'type' => 'rating',
                'min_score' => 1,
                'max_score' => 5,
                'order' => 1,
            ],
            [
                'category' => 'Kedisiplinan & Integritas',
                'question' => 'Tingkat kehadiran dan rekam jejak absensi selama periode evaluasi.',
                'description' => 'Evaluasi absensi tanpa izin, keterlambatan, dan permohonan izin.',
                'type' => 'rating',
                'min_score' => 1,
                'max_score' => 5,
                'order' => 2,
            ],
            [
                'category' => 'Kinerja & Kompetensi Teknis',
                'question' => 'Kualitas hasil kerja dan ketelitian dalam menyelesaikan tugas harian.',
                'description' => 'Tingkat akurasi output, minimnya kesalahan kerja, dan kerapian hasil.',
                'type' => 'rating',
                'min_score' => 1,
                'max_score' => 5,
                'order' => 3,
            ],
            [
                'category' => 'Kinerja & Kompetensi Teknis',
                'question' => 'Kecepatan dan produktivitas pencapaian target kerja yang ditetapkan.',
                'description' => 'Ketepatan batas waktu pengerjaan dan efisiensi durasi kerja.',
                'type' => 'rating',
                'min_score' => 1,
                'max_score' => 5,
                'order' => 4,
            ],
            [
                'category' => 'Kerjasama & Komunikasi',
                'question' => 'Kemampuan berkoordinasi dan bekerjasama dalam tim kerja.',
                'description' => 'Sikap saling membantu, komunikasi efektif dengan rekan kerja dan atasan.',
                'type' => 'rating',
                'min_score' => 1,
                'max_score' => 5,
                'order' => 5,
            ],
            [
                'category' => 'Inisiatif & Tanggung Jawab',
                'question' => 'Tanggung jawab terhadap peralatan kerja dan proaktif dalam pemecahan masalah.',
                'description' => 'Inisiatif melaporkan kendala lebih awal serta menjaga fasilitas kerja.',
                'type' => 'rating',
                'min_score' => 1,
                'max_score' => 5,
                'order' => 6,
            ],
        ];

        $createdQuestions = [];
        foreach ($questions as $q) {
            $createdQuestions[] = EvaluationQuestion::create($q);
        }

        // 4. Create Dummy Employees (40 employees across divisions)
        $divisions = [
            'Produksi' => ['Operator Mesin', 'Teknisi Produksi', 'Helper Produksi', 'Foreman Produksi'],
            'Logistik & Gudang' => ['Staff Gudang', 'Picker', 'Forklift Driver', 'Admin Logistik'],
            'Quality Control' => ['QC Inspector', 'Lab Analyst', 'QC Line Checker'],
            'Maintenance' => ['Mekanik', 'Elektrikal', 'Staff Maintenance'],
            'Operasional' => ['General Worker', 'Driver Operasional', 'Admin Operasional'],
        ];

        $firstNames = ['Rian', 'Doni', 'Agus', 'Bambang', 'Eko', 'Fajar', 'Gunawan', 'Hadi', 'Imam', 'Joko', 'Kurniawan', 'Lukman', 'Mulyadi', 'Nur', 'Oki', 'Prasetyo', 'Qori', 'Rizky', 'Supriadi', 'Taufik', 'Umar', 'Vicky', 'Wahyu', 'Yudi', 'Zainal', 'Ratna', 'Sri', 'Mega', 'Putri', 'Anita', 'Lestari', 'Maya', 'Nita', 'Rani', 'Sari', 'Tri', 'Wulan', 'Yulia', 'Dian', 'Fitri'];
        $lastNames = ['Kusuma', 'Saputra', 'Pratama', 'Hidayat', 'Wibowo', 'Nugroho', 'Setiawan', 'Firmansyah', 'Santoso', 'Utomo', 'Siregar', 'Harahap', 'Ginting', 'Sinaga', 'Nasution', 'Lubis', 'Pasaribu', 'Simanjuntak', 'Sitompul', 'Manurung'];

        $empIndex = 1;
        $allEmployees = [];

        foreach ($divisions as $divisiName => $jobs) {
            // Find evaluator for this department
            $evaluator = collect($evaluators)->firstWhere('department', $divisiName) ?? $evaluators[0];

            for ($i = 0; $i < 8; $i++) {
                $code = 'TK-' . str_pad($empIndex, 4, '0', STR_PAD_LEFT);
                $name = $firstNames[($empIndex - 1) % count($firstNames)] . ' ' . $lastNames[($empIndex - 1) % count($lastNames)];
                $job = $jobs[$i % count($jobs)];

                // Assign to evaluator, leave last 3 in total as unassigned for testing assignment flow
                $assignedEvaluatorId = ($empIndex > 37) ? null : $evaluator->id;

                $employee = Employee::create([
                    'employee_code' => $code,
                    'nama' => $name,
                    'jabatan' => $job,
                    'divisi' => $divisiName,
                    'department' => $divisiName,
                    'unit' => 'Unit ' . (($i % 3) + 1),
                    'evaluator_id' => $assignedEvaluatorId,
                    'status' => 'active',
                ]);

                $allEmployees[] = $employee;
                $empIndex++;
            }
        }

        // 5. Create some initial Evaluations (some submitted, some draft)
        // For Evaluator 1 (Budi): 4 submitted, 2 draft, 2 un-evaluated
        $evaluator1Employees = collect($allEmployees)->where('evaluator_id', $evaluators[0]->id)->values();

        // 4 Submitted evaluations
        for ($i = 0; $i < 4; $i++) {
            $emp = $evaluator1Employees[$i];
            $eval = Evaluation::create([
                'employee_id' => $emp->id,
                'evaluator_id' => $evaluators[0]->id,
                'status' => 'submitted',
                'strengths' => 'Disiplin tinggi, selalu menyelesaikan target tepat waktu, komunikatif.',
                'areas_for_improvement' => 'Perlu peningkatan pemahaman teknis mesin generasi baru.',
                'recommendations' => 'Dipertahankan dan direkomendasikan untuk perpanjangan kontrak kerja.',
                'evaluator_notes' => 'Kinerja selama 6 bulan terakhir sangat memuaskan.',
                'submitted_at' => now()->subDays(rand(1, 10)),
            ]);

            $scores = [4, 5, 4, 4, 5, 4];
            foreach ($createdQuestions as $idx => $q) {
                EvaluationAnswer::create([
                    'evaluation_id' => $eval->id,
                    'question_id' => $q->id,
                    'score' => $scores[$idx % count($scores)],
                    'answer' => 'Skor: ' . $scores[$idx % count($scores)],
                    'notes' => 'Memenuhi standar dengan baik.',
                ]);
            }
            $eval->recalculateTotalScore();
        }

        // 2 Draft evaluations
        for ($i = 4; $i < 6; $i++) {
            $emp = $evaluator1Employees[$i];
            $eval = Evaluation::create([
                'employee_id' => $emp->id,
                'evaluator_id' => $evaluators[0]->id,
                'status' => 'draft',
                'strengths' => 'Sikap kerja baik.',
                'areas_for_improvement' => 'Masih dalam proses observasi.',
                'recommendations' => null,
                'evaluator_notes' => 'Draft sementara evaluasi awal.',
            ]);

            foreach ($createdQuestions as $q) {
                EvaluationAnswer::create([
                    'evaluation_id' => $eval->id,
                    'question_id' => $q->id,
                    'score' => 3,
                    'answer' => 'Skor: 3',
                    'notes' => 'Catatan sementara.',
                ]);
            }
            $eval->recalculateTotalScore();
        }

        // Evaluator 2 (Siti): 3 submitted, 1 draft
        $evaluator2Employees = collect($allEmployees)->where('evaluator_id', $evaluators[1]->id)->values();
        for ($i = 0; $i < 3; $i++) {
            $emp = $evaluator2Employees[$i];
            $eval = Evaluation::create([
                'employee_id' => $emp->id,
                'evaluator_id' => $evaluators[1]->id,
                'status' => 'submitted',
                'strengths' => 'Ketelitian inventaris sangat baik.',
                'areas_for_improvement' => 'Tingkatkan kecepatan loading barang.',
                'recommendations' => 'Direkomendasikan lanjut.',
                'evaluator_notes' => 'Sangat bertanggung jawab terhadap stok.',
                'submitted_at' => now()->subDays(rand(2, 5)),
            ]);

            foreach ($createdQuestions as $q) {
                EvaluationAnswer::create([
                    'evaluation_id' => $eval->id,
                    'question_id' => $q->id,
                    'score' => rand(4, 5),
                    'answer' => 'Sesuai ekspektasi.',
                ]);
            }
            $eval->recalculateTotalScore();
        }

        // 6. Record Initial Audit Log
        AuditLog::create([
            'user_id' => $admin->id,
            'action' => 'SYSTEM_INITIALIZATION',
            'description' => 'Sistem evaluasi diinisialisasi dengan data master tenaga kerja dan evaluator.',
            'target_type' => 'System',
            'target_id' => 1,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Seeder',
        ]);
    }
}
