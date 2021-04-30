<?php

use App\Models\Package;

class PackageTest extends TestCase
{

    protected $migrated = false;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    /**
     * Tests create route.
     *
     * @return void
     */
    public function testCreate()
    {
        $this->json('POST', '/create', [
            'origin' => 'Holland',
            'destination' => 'Grand Rapids',
            'weight' => 15,
        ])
        ->seeJson(['data' => [
            'origin' => 'Holland',
            'destination' => 'Grand Rapids',
            'weight' => 15,
            'id' => 1
        ]]);

        $this->seeInDatabase('packages', [
            'id' => 1,
            'origin' => 'Holland',
            'destination' => 'Grand Rapids',
            'weight' => 15
        ]);

        $this->seeInDatabase('tracking', [
            'package_id' => 1,
            'location' => 'Holland',
            'status' => 'arrived'
        ]);
    }

    /**
     * Tests update route
     */
    public function testUpdate()
    {
        $this->createRecords();

        $this->json('PUT', '/update', [
            'id' => 1,
            'location' => 'Grand Rapids',
            'status' => 'arrived'
        ])
        ->seeJson([
            'location' => 'Grand Rapids',
            'status' => 'arrived'
        ]);

        $this->seeInDatabase('tracking', [
            'package_id' => 1,
            'location' => 'Grand Rapids',
            'status' => 'arrived'
        ]);
    }

    /**
     * Tests progress route
     */
    public function testProgress()
    {
        $this->createRecords();

        $this->json('GET', '/check_progress', [
            'id' => 1
        ])
        ->seeJsonStructure([
            'data' => [
                'package' => [
                    'id',
                    'origin',
                    'delivered',
                    'destination',
                    'weight'
                ],
                'tracking' => [
                    [
                        'location',
                        'status',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]
        ]);
    }

    /**
     * Tests mark delivered route
     */
    public function testDelivered()
    {
        $this->createRecords();

        $this->json('POST','/mark_delivered', [
            'id' => 1
        ])->seeJson([
            'delivered' => true,
        ]);

        $this->seeInDatabase('packages', [
            'id' => 1,
            'delivered' => true,
        ]);
    }

    /**
     * Creates default db records for tests.
     */
    protected function createRecords()
    {
        $package = new Package;
        $package->fill([
            'origin' => 'Holland',
            'destination' => 'Grand Rapids',
            'weight' => 15,
        ]);
        $package->save();
        $package->tracking()->create([
            'location' => 'Holland',
            'status' => 'arrived',
        ]);
    }
}
