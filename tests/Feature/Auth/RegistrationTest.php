<?php

namespace Tests\Feature\Auth;

use App\Mail\RegistrationDetailsMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Models\Programme;
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

        $programme = Programme::create([
            'name' => 'Dentistry',
            'slug' => 'computer-science',
        ]);

        $response = $this->post('/register', [
            'first_name' => 'Test',
            'middle_name' => 'Middle',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'matric_no' => '123456',
            'programme_id' => $programme->id,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));

        Mail::assertQueued(RegistrationDetailsMail::class, function ($mail) {
            return $mail->user->email === 'test@example.com';
        });
    }

    public function test_new_users_can_register_without_middle_name(): void
    {
        Mail::fake();

        $programme = Programme::create([
            'name' => 'Software Engineering',
            'slug' => 'software-engineering',
        ]);

        $response = $this->post('/register', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'nomiddle@example.com',
            'matric_no' => '654321',
            'programme_id' => $programme->id,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }
}
