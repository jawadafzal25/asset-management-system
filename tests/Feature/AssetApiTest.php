<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Department;
use App\Models\Organization;
use App\Models\SessionToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetApiTest extends TestCase
{
    use RefreshDatabase;

    protected Organization $org;
    protected User $user;
    protected string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organization::create(['name' => 'Test Org']);
        $this->user = User::factory()->create([
            'organization_id' => $this->org->id,
            'is_active' => true,
        ]);
        $this->token = SessionToken::generate('access_token', $this->user);
    }

    public function test_it_creates_an_asset(): void
    {
        $category = Category::create([
            'name' => 'Laptops',
            'status' => true,
            'organization_id' => $this->org->id,
        ]);
        $department = Department::create([
            'department_name' => 'IT Department',
            'status' => true,
            'organization_id' => $this->org->id,
        ]);

        $response = $this->postJson('/api/assets', [
            'asset_name' => 'Macbook Pro M3 Max',
            'asset_code' => 'IT-O30',
            'total_quantity' => 10,
            'status' => 'available',
            'category_id' => $category->id,
            'department_id' => $department->department_id,
            'brand' => 'Apple',
            'purchase_date' => '2026-05-20',
        ], [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.assetName', 'Macbook Pro M3 Max')
            ->assertJsonPath('data.totalQuantity', 10)
            ->assertJsonPath('data.status', 'available')
            ->assertJsonPath('data.categoryId', $category->id)
            ->assertJsonPath('data.brand', 'Apple');

        $this->assertDatabaseHas('assets', [
            'asset_name' => 'Macbook Pro M3 Max',
            'total_quantity' => 10,
            'category_id' => $category->id,
        ]);
    }

    public function test_it_reads_a_single_asset_with_images_and_updated_at(): void
    {
        $category = Category::create([
            'name' => 'Laptops',
            'status' => true,
            'organization_id' => $this->org->id,
        ]);
        $department = Department::create([
            'department_name' => 'IT Department',
            'status' => true,
            'organization_id' => $this->org->id,
        ]);

        $asset = Asset::create([
            'asset_name' => 'Macbook Pro M3 Max',
            'asset_code' => 'IT-O30',
            'total_quantity' => 10,
            'remaining_quantity' => 10,
            'status' => 'assigned',
            'category_id' => $category->id,
            'department_id' => $department->department_id,
            'brand' => 'Apple',
            'purchase_date' => '2026-05-20',
            'asset_image' => '6a0d6863ff0ca0b25c0d6482',
            'invoice_image' => '6a0d6863ff0ca0b25c0d6484',
            'organization_id' => $this->org->id,
        ]);

        $response = $this->getJson('/api/assets/' . $asset->id, [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $asset->id)
            ->assertJsonPath('data.assetName', 'Macbook Pro M3 Max')
            ->assertJsonPath('data.assetImage', 'http://localhost/api/files/6a0d6863ff0ca0b25c0d6482')
            ->assertJsonPath('data.invoiceImage', 'http://localhost/api/files/6a0d6863ff0ca0b25c0d6484')
            ->assertJsonPath('data.categoryId', $category->id)
            ->assertJsonPath('data.departmentId', $department->department_id);
    }

    public function test_it_updates_asset_without_changing_category_if_not_specified(): void
    {
        $category1 = Category::create([
            'name' => 'Laptops',
            'status' => true,
            'organization_id' => $this->org->id,
        ]);

        $asset = Asset::create([
            'asset_name' => 'Macbook Pro M3 Max',
            'asset_code' => 'IT-O30',
            'total_quantity' => 10,
            'remaining_quantity' => 10,
            'status' => 'assigned',
            'category_id' => $category1->id,
            'organization_id' => $this->org->id,
        ]);

        $response = $this->putJson('/api/assets/' . $asset->id, [
            'asset_name' => 'Macbook Pro M3 Max Pro',
            'total_quantity' => 12,
            'status' => 'available',
        ], [
            'Authorization' => "Bearer {$this->token}",
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.assetName', 'Macbook Pro M3 Max Pro')
            ->assertJsonPath('data.totalQuantity', 12)
            ->assertJsonPath('data.categoryId', $category1->id);

        $this->assertDatabaseHas('assets', [
            'id' => $asset->id,
            'asset_name' => 'Macbook Pro M3 Max Pro',
            'category_id' => $category1->id,
        ]);
    }
}
