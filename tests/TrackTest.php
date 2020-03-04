<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

use App\Driver;
use App\Track;
use App\Type;

class TrackTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp() : void
    {
        parent::setUp();
        factory(Type::class)->create(['name' => "Caminhão 3/4"]);
        factory(Type::class)->create(['name' => "Caminhão Toco"]);
        factory(Type::class)->create(['name' => "Caminhão Truck"]);
        factory(Type::class)->create(['name' => "arreta Simples"]);
        factory(Type::class)->create(['name' => "Carreta Eixo Extendido"]);
    }

    public function testCanAccessTracking()
    {
        $response = $this->json("GET", '/api/v1/tracks/tracking');
        $response->assertResponseStatus(200);
    }

    public function testTrackingEmpty()
    {
        $response = $this->json("GET", '/api/v1/tracks/tracking');
        $response->assertResponseStatus(200);
        $content = json_decode($this->response->getContent());
        $content = isset($content->data) ? $content->data : [];
        $this->assertIsNotObject($content);

        $driver = factory(Driver::class)->create();
        factory(Track::class)->create([
            'driver_id' => $driver->id
        ]);
        $content = json_decode($this->response->getContent());
        $this->assertCount(0, $content->data);
    }

    public function testListNotEmpty()
    {
        $driver = factory(Driver::class)->create();
        factory(Track::class)->create([
            'driver_id' => $driver->id,
            'on_way' => 1
        ]);
        $response = $this->json("GET", '/api/v1/tracks/tracking');
        $response->assertResponseStatus(200);
        $content = json_decode($this->response->getContent());
        $this->assertCount(1, $content->data);
    }

    public function testListHasExactNumber()
    {
        $joao = factory(Driver::class)->create();
        factory(Track::class)->create([
            'driver_id' => $joao->id,
            'on_way' => 1
        ]);
        $maria = factory(Driver::class)->create();
        factory(Track::class)->create([
            'driver_id' => $maria->id,
            'on_way' => 1
        ]);
        $response = $this->json("GET", '/api/v1/tracks/tracking');
        $response->assertResponseStatus(200);
        $content = json_decode($this->response->getContent());
        $this->assertCount(2, $content->data);
    }

    public function testCanStoreData()
    {
        $joao = factory(Driver::class)->create();
        $this->post('/api/v1/tracks', [
            "payload" => [
                "latitude" => "-23.5601802",
                "longitude" => "-46.6415725,15",
                "on_way" => 0,
                "has_truckload" => 0,
                "driver_id" => $joao->id,
                "type_id" => 3,
            ]
        ]);
        $this->assertResponseStatus(201);
    }

    public function testCanNotStoreData()
    {
        $joao = factory(Driver::class)->create();
        $this->post('/api/v1/tracks', [
            "payload" => [
                "latitude" => "-23.560123.560180225560180223560180223560180223560180223560180223560180223560180223560180223560180223560180223560180223601802235601802235601802233.560180223.560180223.560180223.560180223.560180223.560180223.560180223.560180223.560180223.560180223.5601802802",
                "longitude" => "-46.6415725,15",
                "on_way" => 0,
                "has_truckload" => 0,
                "driver_id" => $joao->id,
                "type_id" => 3,
            ]
        ]);
        $content = json_decode($this->response->getContent());
        $this->assertResponseStatus(400);
        $this->assertContains("The payload.latitude may not be greater than 180 characters.", $content->detail);

        $this->post('/api/v1/tracks', [
            "payload" => [
                "latitude" => "-23.5601802",
                "longitude" => "-46.6415725,15",
                "on_way" => 0,
                "has_truckload" => 0,
                "driver_id" => $joao->id,
                "type_id" => -1,
            ]
        ]);
        $content = json_decode($this->response->getContent());
        $this->assertContains("The selected payload.type id is invalid.", $content->detail);

        $this->post('/api/v1/tracks', [
            "payload" => [
                "latitude" => "-23.5601802",
                "longitude" => "-46.6415725,15",
                "on_way" => 0,
                "has_truckload" => 0,
                "driver_id" => -1,
                "type_id" => 2,
            ]
        ]);
        $content = json_decode($this->response->getContent());
        $this->assertContains("The selected payload.driver id is invalid.", $content->detail);
    }
}
