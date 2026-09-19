<?php

namespace Tests\Feature;

use App\Customer;
use App\Http\Controllers\CustomerController;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class CustomerIndexPageTest extends TestCase
{
    public function test_customer_index_ignores_rows_without_associated_user(): void
    {
        $admin = User::forceCreate([
            'name' => 'Admin User',
            'email' => uniqid('admin_', true) . '@example.com',
            'password' => bcrypt('password'),
            'user_type' => 'admin',
            'phone' => '09120000000',
            'email_verified_at' => now(),
        ]);

        Auth::login($admin);

        $user = User::forceCreate([
            'name' => 'Real Customer',
            'email' => uniqid('customer_', true) . '@example.com',
            'password' => bcrypt('password'),
            'user_type' => 'customer',
            'phone' => '09121111111',
            'email_verified_at' => now(),
        ]);

        Customer::create(['user_id' => $user->id]);
        Customer::create(['user_id' => 999999]);

        $response = (new CustomerController())->index(new Request());

        $this->assertStringContainsString('Real Customer', $response->render());
    }
}
