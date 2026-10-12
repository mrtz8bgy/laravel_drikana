<?php

namespace Tests\Feature;

use App\Http\Controllers\Jewelry\JewelryCertificateController;
use App\Models\JewelryCertificate;
use App\Models\JewelryOwner;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class JewelryCertificatePrintTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function print_view_embeds_certificate_image_as_a_valid_data_uri()
    {
        Storage::fake('public');
        $imagePath = 'jewelry-certificates/print-test-' . uniqid() . '.png';
        $imageBytes = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+jgfsAAAAASUVORK5CYII=');
        Storage::disk('public')->put($imagePath, $imageBytes);

        $user = new User();
        $user->name = 'Print image test';
        $user->email = 'print-image-' . uniqid() . '@example.com';
        $user->password = bcrypt('test-password-12345');
        $user->user_type = 'customer';
        $user->role = 'customer';
        $user->save();

        $owner = JewelryOwner::create([
            'user_id' => $user->id,
            'full_name' => 'مالک تست چاپ',
            'phone' => '09123456789',
        ]);

        $certificate = JewelryCertificate::create([
            'owner_id' => $owner->id,
            'product_name' => 'قطعه تست چاپ',
            'metal_type' => 'طلا',
            'serial_number' => 'PRINT-' . strtoupper(uniqid()),
            'certificate_file' => $imagePath,
        ]);

        $response = app(JewelryCertificateController::class)->print($certificate);
        $html = $response->render();

        $this->assertStringContainsString('src="data:image/png;base64,' . base64_encode($imageBytes) . '"', $html);
        $this->assertStringContainsString('نمایش عکس', $html);

        Storage::disk('public')->delete($imagePath);
    }

    /** @test */
    public function certificate_show_uses_a_complete_application_url_for_the_view_link()
    {
        $imagePath = 'jewelry-certificates/view-link-' . uniqid() . '.jpg';
        $user = new User();
        $user->name = 'Certificate view link test';
        $user->email = 'certificate-view-' . uniqid() . '@example.com';
        $user->password = bcrypt('test-password-12345');
        $user->user_type = 'customer';
        $user->role = 'customer';
        $user->save();

        $owner = JewelryOwner::create([
            'user_id' => $user->id,
            'full_name' => 'مالک لینک مشاهده',
            'phone' => '09123456789',
        ]);

        $certificate = JewelryCertificate::create([
            'owner_id' => $owner->id,
            'product_name' => 'قطعه لینک مشاهده',
            'metal_type' => 'طلا',
            'serial_number' => 'VIEW-' . strtoupper(uniqid()),
            'certificate_file' => $imagePath,
        ]);

        $response = app(JewelryCertificateController::class)->show($certificate);
        $html = $response->render();
        $expectedUrl = preg_replace(
            '/^https?:/',
            request()->getScheme() . ':',
            asset('storage/' . $imagePath)
        );

        $this->assertStringContainsString('href="' . $expectedUrl . '"', $html);
        $this->assertStringNotContainsString('href="/storage/' . $imagePath . '"', $html);
    }
}
