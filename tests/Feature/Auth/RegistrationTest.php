<?php

namespace Tests\Feature\Auth;

use App\Mail\RegistrationDetailsMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Models\Faculty;
use App\Models\Department;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        Mail::fake();

        $faculty = Faculty::create(['name' => 'Science', 'slug' => 'science']);
        $department = Department::create([
            'name' => 'Computer Science',
            'slug' => 'computer-science',
            'faculty_id' => $faculty->id
        ]);

        $response = $this->post('/register', [
            'first_name' => 'Test',
            'middle_name' => 'Middle',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'matric_no' => '123456',
            'faculty_id' => $faculty->id,
            'department_id' => $department->id,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));

        Mail::assertQueued(RegistrationDetailsMail::class, function ($mail) {
            return $mail->user->email === 'test@example.com';
        });
    }
}
