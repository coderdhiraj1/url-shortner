<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Company;
use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShortUrlTest extends TestCase
{
    use RefreshDatabase;

    private string $longUrl = 'https://www.zepto.com/pn/ugaoo-air-purifying-peace-lily-plant-plant-in-ibiza-pot-gifting-decor/pvid/0018abc3-c7a2-4301-94ba-826cbb1d5c76';

    public function test_admin_can_create_a_short_url(): void
    {
        $company = Company::create(['name' => 'Acme']);

        $admin = User::factory()->create([
            'role' => Role::ADMIN,
            'company_id' => $company->id,
        ]);

        $response = $this->actingAs($admin)->post('/dashboard/short-urls', [
            'original_url' => $this->longUrl,
        ]);

        $response->assertRedirect('/dashboard');

        $this->assertDatabaseHas('short_urls', [
            'company_id' => $company->id,
            'created_by' => $admin->id,
            'original_url' => $this->longUrl,
        ]);
    }

    public function test_member_can_create_a_short_url(): void
    {
        $company = Company::create(['name' => 'Acme']);

        $member = User::factory()->create([
            'role' => Role::MEMBER,
            'company_id' => $company->id,
        ]);

        $response = $this->actingAs($member)->post('/dashboard/short-urls', [
            'original_url' => $this->longUrl,
        ]);

        $response->assertRedirect('/dashboard');

        $this->assertDatabaseHas('short_urls', [
            'company_id' => $company->id,
            'created_by' => $member->id,
            'original_url' => $this->longUrl,
        ]);
    }

    public function test_super_admin_cannot_create_a_short_url(): void
    {
        $superAdmin = User::factory()->create([
            'role' => Role::SUPER_ADMIN,
        ]);

        $response = $this->actingAs($superAdmin)->post('/dashboard/short-urls', [
            'original_url' => $this->longUrl,
        ]);

        $response->assertStatus(403);

        $this->assertDatabaseCount('short_urls', 0);
    }

    public function test_admin_only_sees_short_urls_from_their_company(): void
    {
        $acme = Company::create(['name' => 'Acme']);
        $other = Company::create(['name' => 'Other Co']);

        $admin = User::factory()->create([
            'role' => Role::ADMIN,
            'company_id' => $acme->id,
        ]);

        $teammate = User::factory()->create([
            'role' => Role::MEMBER,
            'company_id' => $acme->id,
        ]);

        $outsider = User::factory()->create([
            'role' => Role::ADMIN,
            'company_id' => $other->id,
        ]);

        $this->makeShortUrl($admin, $this->longUrl, 'acmeadm');
        $this->makeShortUrl($teammate, $this->longUrl, 'acmemem');
        $this->makeShortUrl($outsider, 'https://www.google.com', 'otherco');

        $response = $this->actingAs($admin)->get('/dashboard/short-urls');

        $response->assertStatus(200);
        $response->assertSee('/s/acmeadm');
        $response->assertSee('/s/acmemem');
        $response->assertSee('www.zepto.com/pn/ugaoo');
        $response->assertDontSee('www.google.com');
    }

    public function test_member_only_sees_short_urls_they_created(): void
    {
        $acme = Company::create(['name' => 'Acme']);

        $member = User::factory()->create([
            'role' => Role::MEMBER,
            'company_id' => $acme->id,
        ]);

        $teammate = User::factory()->create([
            'role' => Role::MEMBER,
            'company_id' => $acme->id,
        ]);

        $this->makeShortUrl($member, $this->longUrl, 'minemine');
        $this->makeShortUrl($teammate, 'https://www.google.com', 'theirss');

        $response = $this->actingAs($member)->get('/dashboard/short-urls');

        $response->assertStatus(200);
        $response->assertSee('/s/minemine');
        $response->assertSee('www.zepto.com/pn/ugaoo');
        $response->assertDontSee('/s/theirss');
        $response->assertDontSee('www.google.com');
    }

    public function test_short_url_redirects_to_the_original_url(): void
    {
        $company = Company::create(['name' => 'Acme']);

        $member = User::factory()->create([
            'role' => Role::MEMBER,
            'company_id' => $company->id,
        ]);

        $shortUrl = $this->makeShortUrl($member, $this->longUrl, 'landin');

        $response = $this->get('/s/'.$shortUrl->short_code);

        $response->assertRedirect($this->longUrl);
        $this->assertSame(1, $shortUrl->fresh()->hits);
    }

    private function makeShortUrl(User $user, string $url, string $code): ShortUrl
    {
        return ShortUrl::create([
            'company_id' => $user->company_id,
            'created_by' => $user->id,
            'original_url' => $url,
            'short_code' => $code,
            'hits' => 0,
        ]);
    }
}
