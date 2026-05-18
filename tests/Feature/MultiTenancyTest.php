<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Department;
use App\Models\Organization;
use App\Models\SessionToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultiTenancyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_filters_departments_and_categories_by_organization(): void
    {
        // 1. Create two Organizations
        $org1 = Organization::create(['name' => 'Organization One']);
        $org2 = Organization::create(['name' => 'Organization Two']);

        // 2. Create Users for both organizations
        $user1 = User::factory()->create([
            'email' => 'user1@org1.com',
            'organization_id' => $org1->id,
            'is_active' => true,
        ]);

        $user2 = User::factory()->create([
            'email' => 'user2@org2.com',
            'organization_id' => $org2->id,
            'is_active' => true,
        ]);

        // 3. Create active tokens for both users
        $token1 = SessionToken::generate('access_token', $user1);
        $token2 = SessionToken::generate('access_token', $user2);

        // 4. Create Department and Category for Org 1 (using direct creation or scoping)
        // Since we are in seeder/direct creation context, request() is empty, so we assign organization_id explicitly
        $dept1 = Department::create([
            'department_name' => 'HR Org One',
            'organization_id' => $org1->id,
        ]);

        $cat1 = Category::create([
            'name' => 'Laptops Org One',
            'organization_id' => $org1->id,
        ]);

        // 5. Create Department and Category for Org 2
        $dept2 = Department::create([
            'department_name' => 'Engineering Org Two',
            'organization_id' => $org2->id,
        ]);

        $cat2 = Category::create([
            'name' => 'Servers Org Two',
            'organization_id' => $org2->id,
        ]);

        // 6. Request Departments as User 1 (should only see Org 1 departments)
        $responseDept1 = $this->getJson('/api/departments/read', [
            'Authorization' => "Bearer {$token1}",
        ]);

        $responseDept1->assertOk();
        $deptData1 = $responseDept1->json('data');
        $this->assertCount(1, $deptData1);
        $this->assertEquals('HR Org One', $deptData1[0]['name']);

        // 7. Request Departments as User 2 (should only see Org 2 departments)
        $responseDept2 = $this->getJson('/api/departments/read', [
            'Authorization' => "Bearer {$token2}",
        ]);

        $responseDept2->assertOk();
        $deptData2 = $responseDept2->json('data');
        $this->assertCount(1, $deptData2);
        $this->assertEquals('Engineering Org Two', $deptData2[0]['name']);

        // 8. Request Categories as User 1 (should only see Org 1 categories)
        $responseCat1 = $this->getJson('/api/categories/read', [
            'Authorization' => "Bearer {$token1}",
        ]);

        $responseCat1->assertOk();
        $catData1 = $responseCat1->json('data');
        $this->assertCount(1, $catData1);
        $this->assertEquals('Laptops Org One', $catData1[0]['name']);

        // 9. Request Categories as User 2 (should only see Org 2 categories)
        $responseCat2 = $this->getJson('/api/categories/read', [
            'Authorization' => "Bearer {$token2}",
        ]);

        $responseCat2->assertOk();
        $catData2 = $responseCat2->json('data');
        $this->assertCount(1, $catData2);
        $this->assertEquals('Servers Org Two', $catData2[0]['name']);
    }
}
