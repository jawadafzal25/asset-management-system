<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware();
    }

    public function test_it_creates_a_category_and_persists_status(): void
    {
        $response = $this->postJson('/api/categories/create', [
            'name' => 'Computers',
            'description' => 'Desktop and laptop assets',
            'status' => false,
        ]);

        $response->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Computers')
            ->assertJsonPath('data.status', false);

        $this->assertDatabaseHas('categories', [
            'name' => 'Computers',
            'status' => false,
        ]);
    }

    public function test_it_lists_categories_with_paginated_payload(): void
    {
        Category::create([
            'name' => 'Network',
            'description' => 'Routers and switches',
            'status' => true,
        ]);

        $response = $this->getJson('/api/categories/read');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonFragment(['name' => 'Network']);
    }

    public function test_it_shows_a_single_category(): void
    {
        $category = Category::create([
            'name' => 'Furniture',
            'description' => 'Office furniture',
            'status' => true,
        ]);

        $response = $this->getJson('/api/categories/' . $category->id);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Furniture')
            ->assertJsonPath('data.status', true);
    }

    public function test_it_updates_a_category_without_overwriting_missing_status(): void
    {
        $category = Category::create([
            'name' => 'Printers',
            'description' => 'Old description',
            'status' => false,
        ]);

        $response = $this->putJson('/api/categories/' . $category->id . '/update', [
            'name' => 'Printers Updated',
            'description' => 'New description',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Printers Updated')
            ->assertJsonPath('data.status', false);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Printers Updated',
            'status' => false,
        ]);
    }

    public function test_it_soft_deletes_a_category(): void
    {
        $category = Category::create([
            'name' => 'Disposable',
            'description' => 'Temporary assets',
            'status' => true,
        ]);

        $response = $this->deleteJson('/api/categories/' . $category->id . '/delete');

        $response->assertOk()
            ->assertJsonPath('success', true);

        $this->assertSoftDeleted('categories', [
            'id' => $category->id,
        ]);
    }
}
