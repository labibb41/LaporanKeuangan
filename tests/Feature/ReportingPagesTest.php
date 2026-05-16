<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportingPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_reporting_pages_can_be_rendered_for_authenticated_admin(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('dashboard'))->assertOk();
        $this->actingAs($user)->get(route('operasional.index'))->assertOk();
        $this->actingAs($user)->get(route('laporan.index'))->assertOk();
        $this->actingAs($user)->get(route('laporan.operasional'))->assertOk();
        $this->actingAs($user)->get(route('laporan.partner'))->assertOk();
        $this->actingAs($user)->get(route('laporan.telly'))->assertOk();
        $this->actingAs($user)->get(route('laporan.paguyuban'))->assertOk();
        $this->actingAs($user)->get(route('laporan.pengeluaran'))->assertOk();
        $this->actingAs($user)->get(route('laporan.keuangan'))->assertOk();
    }
}
