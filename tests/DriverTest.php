<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

use App\Driver;
use App\Track;
use App\Type;

class HotelTest extends TestCase
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

    public function testRawEndpointRedirection()
    {
        $response = $this->json("GET", '/');
        $response->assertResponseStatus(302);
    }

    public function testCanAccessList()
    {
        $response = $this->json("GET", '/api/v1/drivers');
        $response->assertResponseStatus(200);
    }

    public function test404()
    {
        $response = $this->json("GET", '/api/drivers', []);
        $response->assertResponseStatus(404);
    }

    public function testListEmpty()
    {
        $response = $this->json("GET", '/api/v1/drivers', []);
        $response->assertResponseStatus(200);
        $content = json_decode($this->response->getContent());
        $content = isset($content->data) ? $content->data : [];
        $this->assertIsNotObject($content);

        $driver = factory(Driver::class)->create();
        factory(Track::class)->create([
            'driver_id' => $driver->id,
            'has_truckload' => 1,
        ]);
        $content = json_decode($this->response->getContent());
        $this->assertCount(0, $content->data);
    }

    public function testListNotEmpty()
    {
        $driver = factory(Driver::class)->create();
        factory(Track::class)->create([
            'driver_id' => $driver->id
        ]);
        $response = $this->json("GET", '/api/v1/drivers');
        $response->assertResponseStatus(200);
        $content = json_decode($this->response->getContent());
        $this->assertCount(1, $content->data);
    }

    public function testListHasExactNumber()
    {
        $joao = factory(Driver::class)->create();
        factory(Track::class)->create([
            'driver_id' => $joao->id
        ]);
        $pedro = factory(Driver::class)->create();
        factory(Track::class)->create([
            'driver_id' => $pedro->id
        ]);
        $maria = factory(Driver::class)->create();
        factory(Track::class)->create([
            'driver_id' => $maria->id
        ]);
        $response = $this->json("GET", '/api/v1/drivers');
        $response->assertResponseStatus(200);
        $content = json_decode($this->response->getContent());
        $this->assertCount(3, $content->data);
    }

    public function testQtdHasVehicleIsExact()
    {
        factory(Driver::class)->create(["has_vehicles" => 0]);
        factory(Driver::class)->create(["has_vehicles" => 0]);
        factory(Driver::class)->create(["has_vehicles" => 0]);
        $response = $this->json("GET", '/api/v1/drivers/qtdvehicles');
        $response->assertResponseStatus(200);
        $content = json_decode($this->response->getContent());
        $this->assertEquals(0, $content->data);

        factory(Driver::class)->create();
        factory(Driver::class)->create();
        $response = $this->json("GET", '/api/v1/drivers/qtdvehicles');
        $response->assertResponseStatus(200);
        $content = json_decode($this->response->getContent());
        $this->assertEquals(2, $content->data);
    }

    public function testCanStoreData()
    {
        $a  =$this->post('/api/v1/drivers', [
            "payload" => [
                "name" => "Antonio Carlos",
                "age" => 48,
                "gender" => "M",
                "has_vehicles" => 1,
                "cnh_type" => "D"
            ]
        ]);
        $this->assertResponseStatus(201);
    }
}
