<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Employee;
use App\Models\Evaluation;
use App\Models\EvaluationQuestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class WorkforceEvaluationTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $evaluator1;
    protected User $evaluator2;
    protected Employee $emp1;
    protected Employee $emp2;
    protected EvaluationQuestion $question1;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.local',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->evaluator1 = User::create([
            'name' => 'Evaluator One',
            'email' => 'eval1@test.local',
            'password' => Hash::make('password'),
            'role' => 'evaluator',
            'department' => 'Produksi',
            'is_active' => true,
        ]);

        $this->evaluator2 = User::create([
            'name' => 'Evaluator Two',
            'email' => 'eval2@test.local',
            'password' => Hash::make('password'),
            'role' => 'evaluator',
            'department' => 'Logistik',
            'is_active' => true,
        ]);

        $this->emp1 = Employee::create([
            'employee_code' => 'TK-TEST-001',
            'nama' => 'Budi Pegawai 1',
            'jabatan' => 'Operator',
            'divisi' => 'Produksi',
            'evaluator_id' => $this->evaluator1->id,
            'status' => 'active',
        ]);

        $this->emp2 = Employee::create([
            'employee_code' => 'TK-TEST-002',
            'nama' => 'Siti Pegawai 2',
            'jabatan' => 'Admin Gudang',
            'divisi' => 'Logistik',
            'evaluator_id' => $this->evaluator2->id,
            'status' => 'active',
        ]);

        $this->question1 = EvaluationQuestion::create([
            'category' => 'Kedisiplinan',
            'question' => 'Kepatuhan jam kerja dan SOP.',
            'type' => 'rating',
            'min_score' => 1,
            'max_score' => 5,
            'order' => 1,
            'is_required' => true,
            'is_active' => true,
        ]);
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Sistem Evaluasi Tenaga Kerja');
    }

    public function test_admin_can_login_and_is_redirected_to_admin_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@test.local',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($this->admin);
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_evaluator_can_login_and_is_redirected_to_evaluator_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'eval1@test.local',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($this->evaluator1);
        $response->assertRedirect(route('evaluator.dashboard'));
    }

    public function test_inactive_user_cannot_login(): void
    {
        $this->evaluator1->update(['is_active' => false]);

        $response = $this->post('/login', [
            'email' => 'eval1@test.local',
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_evaluator_cannot_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->evaluator1)->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }

    public function test_evaluator_cannot_view_or_evaluate_employee_assigned_to_another_evaluator(): void
    {
        // emp2 is assigned to evaluator2, evaluator1 should be forbidden
        $response = $this->actingAs($this->evaluator1)->get(route('evaluator.employees.show', $this->emp2->id));
        $response->assertStatus(403);

        $formResponse = $this->actingAs($this->evaluator1)->get(route('evaluator.evaluations.form', $this->emp2->id));
        $formResponse->assertStatus(403);
    }

    public function test_evaluator_can_view_own_assigned_employee(): void
    {
        $response = $this->actingAs($this->evaluator1)->get(route('evaluator.employees.show', $this->emp1->id));
        $response->assertStatus(200);
        $response->assertSee($this->emp1->nama);
    }

    public function test_evaluator_can_save_draft_evaluation(): void
    {
        $response = $this->actingAs($this->evaluator1)->post(route('evaluator.evaluations.save', $this->emp1->id), [
            'action' => 'draft',
            'scores' => [$this->question1->id => 4],
            'notes' => [$this->question1->id => 'Catatan draft awal'],
            'strengths' => 'Disiplin dan teliti',
            'areas_for_improvement' => 'Masih butuh adaptasi',
        ]);

        $response->assertRedirect(route('evaluator.employees.index'));

        $this->assertDatabaseHas('evaluations', [
            'employee_id' => $this->emp1->id,
            'evaluator_id' => $this->evaluator1->id,
            'status' => 'draft',
            'total_score' => 4.00,
        ]);
    }

    public function test_evaluator_can_submit_final_evaluation(): void
    {
        $response = $this->actingAs($this->evaluator1)->post(route('evaluator.evaluations.save', $this->emp1->id), [
            'action' => 'submit',
            'scores' => [$this->question1->id => 5],
            'notes' => [$this->question1->id => 'Sangat memuaskan'],
            'strengths' => 'Kinerja luar biasa',
            'areas_for_improvement' => 'Pertahankan konsistensi',
            'recommendations' => 'Perpanjangan kontrak',
        ]);

        $response->assertRedirect(route('evaluator.employees.index'));

        $this->assertDatabaseHas('evaluations', [
            'employee_id' => $this->emp1->id,
            'evaluator_id' => $this->evaluator1->id,
            'status' => 'submitted',
            'total_score' => 5.00,
        ]);

        $eval = Evaluation::where('employee_id', $this->emp1->id)->first();
        $this->assertNotNull($eval->submitted_at);
    }

    public function test_admin_can_unlock_submitted_evaluation(): void
    {
        $eval = Evaluation::create([
            'employee_id' => $this->emp1->id,
            'evaluator_id' => $this->evaluator1->id,
            'status' => 'submitted',
            'total_score' => 5.00,
            'submitted_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.evaluations.unlock', $eval->id), [
            'reason' => 'Perlu revisi pada kriteria kedisiplinan.',
        ]);

        $response->assertSessionHas('success');

        $eval->refresh();
        $this->assertEquals('draft', $eval->status);
        $this->assertEquals($this->admin->id, $eval->unlocked_by);
        $this->assertNotNull($eval->unlocked_at);
    }

    public function test_admin_can_crud_employees(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.employees.store'), [
            'employee_code' => 'TK-NEW-999',
            'nama' => 'Pegawai Baru',
            'jabatan' => 'Staff QC',
            'divisi' => 'Quality Control',
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.employees.index'));
        $this->assertDatabaseHas('employees', ['employee_code' => 'TK-NEW-999', 'nama' => 'Pegawai Baru']);
    }

    public function test_printable_evaluation_route_renders_correctly(): void
    {
        $eval = Evaluation::create([
            'employee_id' => $this->emp1->id,
            'evaluator_id' => $this->evaluator1->id,
            'status' => 'submitted',
            'total_score' => 4.50,
            'submitted_at' => now(),
        ]);

        $response = $this->actingAs($this->evaluator1)->get(route('evaluations.print', $eval->id));
        $response->assertStatus(200);
        $response->assertSee('LEMBAR PENILAIAN KERJA');
        $response->assertSee($this->emp1->nama);
    }
}
