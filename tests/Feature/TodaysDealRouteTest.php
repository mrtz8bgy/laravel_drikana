<?php

namespace Tests\Feature;

use Tests\TestCase;

class TodaysDealRouteTest extends TestCase
{
    public function test_public_and_legacy_admin_todays_deal_routes_are_available_for_get_requests(): void
    {
        $this->assertSame('/products/todays-deal', route('products.todays_deal', [], false));
        $this->assertSame('/admin/products/todays_deal', route('admin.products.todays_deal_page', [], false));
    }
}
