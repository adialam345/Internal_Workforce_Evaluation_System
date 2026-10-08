<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\EvaluationQuestion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with 70 Evaluators and 4,000 Employees.
     */
    public function run(): void
    {
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        // 1. Create Super Admin
        $admin = User::create([
            'name' => 'Super Administrator',
            'email' => 'admin@workeval.local',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'department' => 'Human Resources & Corporate',
            'phone' => '081234567890',
            'is_active' => true,
        ]);

        // 2. Define Departments and create 70 Evaluators
        $deptDistribution = [
            'Produksi Line 01-05' => 5,
            'Produksi Line 06-10' => 5,
            'Produksi Line 11-15' => 5,
            'Produksi Line 16-20' => 5,
            'Logistik & Pergudangan' => 10,
            'Quality Control & Lab' => 8,
            'Maintenance & Engineering' => 8,
            'Operasional & Distribusi' => 10,
            'HSE & Keselamatan Kerja' => 4,
            'General Affairs & Fasilitas' => 5,
            'Administrasi Lapangan' => 5,
        ]; // Total: 70 Evaluators

        $firstNames = ['Budi', 'Siti', 'Ahmad', 'Dewi', 'Hendro', 'Rian', 'Doni', 'Agus', 'Bambang', 'Eko', 'Fajar', 'Gunawan', 'Hadi', 'Imam', 'Joko', 'Kurniawan', 'Lukman', 'Mulyadi', 'Nur', 'Oki', 'Prasetyo', 'Qori', 'Rizky', 'Supriadi', 'Taufik', 'Umar', 'Vicky', 'Wahyu', 'Yudi', 'Zainal', 'Ratna', 'Sri', 'Mega', 'Putri', 'Anita', 'Lestari', 'Maya', 'Nita', 'Rani', 'Sari', 'Tri', 'Wulan', 'Yulia', 'Dian', 'Fitri', 'Arief', 'Bayu', 'Candra', 'Dimas', 'Edi', 'Farhan', 'Gilang', 'Heru', 'Irfan', 'Januar', 'Kusuma', 'Latif', 'Mustofa', 'Nugroho', 'Pandu', 'Rahmat', 'Sigit', 'Teguh', 'Untung', 'Viktor', 'Wawan', 'Yoga', 'Zulham', 'Indah', 'Kartika'];
        $lastNames = ['Santoso', 'Rahmawati', 'Hidayat', 'Lestari', 'Wijaya', 'Kusuma', 'Saputra', 'Pratama', 'Wibowo', 'Nugroho', 'Setiawan', 'Firmansyah', 'Utomo', 'Siregar', 'Harahap', 'Ginting', 'Sinaga', 'Nasution', 'Lubis', 'Pasaribu', 'Simanjuntak', 'Sitompul', 'Manurung', 'Subekti', 'Kurnia', 'Permadi', 'Suhendar', 'Kusnandar', 'Suryanto', 'Purnomo'];

        $evaluators = [];
        $evaluatorUsers = [];
        $evalIndex = 1;
        $commonPassword = Hash::make('password');
        $now = now();

        foreach ($deptDistribution as $deptName => $count) {
            for ($i = 1; $i <= $count; $i++) {
                $fName = $firstNames[($evalIndex - 1) % count($firstNames)];
                $lName = $lastNames[($evalIndex - 1) % count($lastNames)];
                $name = "{$fName} {$lName}";
                $email = "evaluator" . str_pad($evalIndex, 2, '0', STR_PAD_LEFT) . "@workeval.local";

                // Keep special easy emails for the first few
                if ($evalIndex === 1) {
                    $email = 'budi@workeval.local';
                    $name = 'Budi Santoso (Lead Produksi)';
                } elseif ($evalIndex === 2) {
                    $email = 'siti@workeval.local';
                    $name = 'Siti Rahmawati (Lead Logistik)';
                }

                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => $commonPassword,
                    'role' => 'evaluator',
                    'department' => $deptName,
                    'phone' => '0812' . str_pad(10000000 + $evalIndex, 8, '0', STR_PAD_LEFT),
                    'is_active' => true,
                ]);

                $evaluators[] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'dept' => $deptName,
                ];
                $evalIndex++;
            }
        }

        // 3. Create Standard Evaluation Criteria Bank (6 questions)
        $questionsData = [
            [
                'category' => 'Kedisiplinan & Integritas',
                'question' => 'Kepatuhan terhadap jam kerja, kehadiran, SOP, dan tata tertib perusahaan.',
                'description' => 'Tingkat ketepatan waktu hadir, kepatuhan pemakaian APD, dan etika kerja harian.',
                'type' => 'rating',
                'min_score' => 1,
                'max_score' => 5,
                'order' => 1,
                'is_required' => true,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category' => 'Kedisiplinan & Integritas',
                'question' => 'Tingkat kehadiran dan rekam jejak absensi selama periode berjalan.',
                'description' => 'Evaluasi kepatuhan jadwal shift dan minimnya ketidakhadiran tanpa izin.',
                'type' => 'rating',
                'min_score' => 1,
                'max_score' => 5,
                'order' => 2,
                'is_required' => true,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category' => 'Kinerja & Kompetensi Teknis',
                'question' => 'Kualitas hasil kerja, ketelitian, dan kerapian output operasional.',
                'description' => 'Akurasi pelaksanaan tugas, minimnya kesalahan kerja (zero defect), dan kerapian.',
                'type' => 'rating',
                'min_score' => 1,
                'max_score' => 5,
                'order' => 3,
                'is_required' => true,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category' => 'Kinerja & Kompetensi Teknis',
                'question' => 'Kecepatan dan produktivitas pencapaian target harian/mingguan.',
                'description' => 'Ketepatan batas waktu pengerjaan, efisiensi waktu, dan volume penyelesaian beban kerja.',
                'type' => 'rating',
                'min_score' => 1,
                'max_score' => 5,
                'order' => 4,
                'is_required' => true,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category' => 'Kerjasama & Komunikasi',
                'question' => 'Kemampuan koordinasi, kerjasama tim, dan komunikasi efektif.',
                'description' => 'Sikap saling mendukung antar anggota tim, responsif terhadap arahan atasan.',
                'type' => 'rating',
                'min_score' => 1,
                'max_score' => 5,
                'order' => 5,
                'is_required' => true,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category' => 'Inisiatif & Tanggung Jawab',
                'question' => 'Tanggung jawab terhadap peralatan kerja dan kepedulian terhadap fasilitas.',
                'description' => 'Inisiatif merawat sarana kerja, melaporkan kendala lebih awal, dan proaktif.',
                'type' => 'rating',
                'min_score' => 1,
                'max_score' => 5,
                'order' => 6,
                'is_required' => true,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('evaluation_questions')->insert($questionsData);
        $questions = DB::table('evaluation_questions')->orderBy('order')->get();

        // 4. Generate 4,000 Employees in batches of 500
        $totalEmployeesToGenerate = 4000;
        $jobTitles = [
            'Produksi Line 01-05' => ['Operator Mesin Assembly', 'Teknisi Lini 1', 'Helper Produksi', 'Inspector Inline'],
            'Produksi Line 06-10' => ['Operator Packaging', 'Teknisi Lini 2', 'Material Handler', 'Line Feeder'],
            'Produksi Line 11-15' => ['Operator Cetak', 'Teknisi Mesin Presisi', 'Helper Operator', 'Foreman Shift'],
            'Produksi Line 16-20' => ['Operator Finishing', 'Teknisi Conveyor', 'Helper Finishing', 'Quality Gate Keeper'],
            'Logistik & Pergudangan' => ['Staff Gudang', 'Picker Barang', 'Forklift Driver', 'Admin Inventory', 'Checker Inbound', 'Checker Outbound'],
            'Quality Control & Lab' => ['QC Inspector', 'Lab Analyst', 'Quality Auditor', 'Sampling Officer', 'Incoming QC'],
            'Maintenance & Engineering' => ['Mekanik Mesin', 'Teknisi Elektrikal', 'Utility Staff', 'Automation Technician', 'Staff Maintenance'],
            'Operasional & Distribusi' => ['Driver Operasional', 'Admin Distribusi', 'Fleet Checker', 'Helper Logistik', 'Dispatcher'],
            'HSE & Keselamatan Kerja' => ['Safety Officer', 'Fire Safety Inspector', 'Environmental Staff', 'First Aider'],
            'General Affairs & Fasilitas' => ['Facility Staff', 'Building Maintenance', 'Inventory GA', 'Asset Officer'],
            'Administrasi Lapangan' => ['Data Entry Operator', 'Admin Operasional', 'Clerk Lapangan', 'Dokumentasi Proyek'],
        ];

        $deptKeys = array_keys($deptDistribution);
        $evaluatorCount = count($evaluators);

        $employeeRecords = [];
        $employeeIdCounter = 1;

        for ($i = 1; $i <= $totalEmployeesToGenerate; $i++) {
            $deptIndex = ($i - 1) % count($deptKeys);
            $deptName = $deptKeys[$deptIndex];
            $jobsInDept = $jobTitles[$deptName];
            $job = $jobsInDept[($i - 1) % count($jobsInDept)];

            $fName = $firstNames[($i * 7 + 3) % count($firstNames)];
            $lName = $lastNames[($i * 11 + 5) % count($lastNames)];
            $empName = "{$fName} {$lName}";
            $code = "TK-" . str_pad($i, 5, '0', STR_PAD_LEFT);

            // Assign to evaluator: distribute among 70 evaluators (~55 employees per evaluator)
            // Leave last 100 employees as unassigned
            $assignedEvaluatorId = null;
            if ($i <= 3900) {
                $evalTargetIndex = ($i - 1) % $evaluatorCount;
                $assignedEvaluatorId = $evaluators[$evalTargetIndex]['id'];
            }

            $employeeRecords[] = [
                'id' => $i,
                'employee_code' => $code,
                'nama' => $empName,
                'jabatan' => $job,
                'divisi' => $deptName,
                'department' => $deptName,
                'unit' => 'Unit ' . ((($i - 1) % 4) + 1) . ' / Shift ' . ((($i - 1) % 3) + 1),
                'evaluator_id' => $assignedEvaluatorId,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if (count($employeeRecords) === 500) {
                DB::table('employees')->insert($employeeRecords);
                $employeeRecords = [];
            }
        }

        if (!empty($employeeRecords)) {
            DB::table('employees')->insert($employeeRecords);
            $employeeRecords = [];
        }

        // 5. Generate Realistic Evaluations for ~3,000 employees:
        // - ~2,500 Submitted (Selesai, with full criteria answers & feedback) (~64%)
        // - ~500 Draft (Dalam Proses) (~13%)
        // - Remaining ~1,000 Belum Dievaluasi (~23%)

        $strengthsList = [
            'Disiplin tinggi, selalu hadir tepat waktu, dan sangat mematuhi SOP kerja.',
            'Kualitas hasil kerja sangat teliti dan konsisten mencapai target harian.',
            'Memiliki inisiatif tinggi dalam membantu rekan tim dan merawat peralatan kerja.',
            'Komunikasi dan koordinasi di lapangan sangat baik, sigap merespons instruksi kerja.',
            'Sikap kerja profesional, bertanggung jawab penuh terhadap kebersihan dan keselamatan kerja.',
            'Cepat beradaptasi dengan mesin baru dan minim tingkat kesalahan operasional.',
            'Kemampuan pemecahan masalah di lini produksi sangat baik dan proaktif.',
        ];

        $improvementList = [
            'Perlu terus meningkatkan kecepatan penanganan kendala minor pada mesin.',
            'Tingkatkan koordinasi antar shift saat serah terima pekerjaan harian.',
            'Perlu mengikuti pelatihan teknis lanjutan untuk pengoperasian sistem otomatisasi.',
            'Tingkatkan kerapian dokumentasi log kerja harian di area kerja.',
            'Pertahankan konsistensi kehadiran dan jaga stamina kerja selama shift malam.',
            'Perlu lebih proaktif menyampaikan ide perbaikan metode kerja di divisi.',
        ];

        $recommendationList = [
            'Dipertahankan dan direkomendasikan untuk perpanjangan kontrak kerja tahunan.',
            'Sangat direkomendasikan untuk promosi jabatan sebagai Team Leader / Foreman.',
            'Direkomendasikan mengikuti program pelatihan kompetensi teknis lanjutan.',
            'Dipertahankan dengan evaluasi berkala per 6 bulan.',
            'Diberikan apresiasi atas dedikasi dan konsistensi kinerja di lapangan.',
        ];

        $evaluationsBatch = [];
        $answersBatch = [];
        $evalCounter = 1;

        // Fetch assigned employees
        $assignedEmployees = DB::table('employees')
            ->whereNotNull('evaluator_id')
            ->select('id', 'evaluator_id')
            ->orderBy('id')
            ->get();

        foreach ($assignedEmployees as $idx => $emp) {
            // First 2,500 are submitted, next 500 are draft, rest are un-evaluated
            if ($idx >= 3000) {
                break;
            }

            $isSubmitted = ($idx < 2500);
            $status = $isSubmitted ? 'submitted' : 'draft';

            // Generate scores (weights: mostly 4 and 5, some 3)
            $scorePattern = [
                rand(3, 5),
                rand(4, 5),
                rand(3, 5),
                rand(4, 5),
                rand(3, 5),
                rand(4, 5),
            ];
            $totalScore = round(array_sum($scorePattern) / count($scorePattern), 2);

            $evalId = $evalCounter;
            $submittedAt = $isSubmitted ? $now->copy()->subHours(rand(1, 360))->subMinutes(rand(1, 59)) : null;

            $evaluationsBatch[] = [
                'id' => $evalId,
                'employee_id' => $emp->id,
                'evaluator_id' => $emp->evaluator_id,
                'status' => $status,
                'total_score' => $totalScore,
                'strengths' => $strengthsList[$idx % count($strengthsList)],
                'areas_for_improvement' => $improvementList[$idx % count($improvementList)],
                'recommendations' => $isSubmitted ? $recommendationList[$idx % count($recommendationList)] : null,
                'evaluator_notes' => $isSubmitted ? 'Hasil evaluasi kinerja periode berjalan telah diverifikasi dan disahkan oleh Evaluator.' : 'Draft sementara dalam proses observasi lapangan.',
                'submitted_at' => $submittedAt,
                'unlocked_by' => null,
                'unlocked_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            // Build answers for this evaluation
            foreach ($questions as $qIdx => $q) {
                $score = $scorePattern[$qIdx];
                $answersBatch[] = [
                    'evaluation_id' => $evalId,
                    'question_id' => $q->id,
                    'score' => $score,
                    'answer' => "Skor: {$score}",
                    'notes' => $score >= 4 ? 'Memenuhi standar dengan sangat baik.' : 'Cukup memenuhi standar kompetensi.',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            $evalCounter++;

            // Chunk insert for high performance
            if (count($evaluationsBatch) === 500) {
                DB::table('evaluations')->insert($evaluationsBatch);
                DB::table('evaluation_answers')->insert($answersBatch);
                $evaluationsBatch = [];
                $answersBatch = [];
            }
        }

        if (!empty($evaluationsBatch)) {
            DB::table('evaluations')->insert($evaluationsBatch);
            DB::table('evaluation_answers')->insert($answersBatch);
            $evaluationsBatch = [];
            $answersBatch = [];
        }

        // 6. Record Initial Audit Logs
        $auditLogsData = [
            [
                'user_id' => $admin->id,
                'action' => 'SYSTEM_INITIALIZATION',
                'description' => 'Inisialisasi sistem evaluasi master: 70 akun Evaluator, 4.000 data Tenaga Kerja, 6 Kriteria Penilaian, dan 2.500+ hasil evaluasi.',
                'target_type' => 'System',
                'target_id' => 1,
                'details' => json_encode(['total_employees' => 4000, 'total_evaluators' => 70]),
                'ip_address' => '127.0.0.1',
                'user_agent' => 'System Seeder / Console',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id' => $admin->id,
                'action' => 'BULK_ASSIGN_EMPLOYEES',
                'description' => 'Melakukan alokasi penugasan massal 3.900 tenaga kerja ke 70 Evaluator Departemen.',
                'target_type' => 'Employee',
                'target_id' => null,
                'details' => json_encode(['assigned' => 3900, 'unassigned' => 100]),
                'ip_address' => '127.0.0.1',
                'user_agent' => 'System Seeder / Console',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('audit_logs')->insert($auditLogsData);
    }
}
