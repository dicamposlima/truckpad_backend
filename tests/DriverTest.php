<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

use App\Driver;
use App\Track;
use App\Type;

class DriverTest extends TestCase
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
        $response = $this->json("GET", '/api/v1/drivers/hasTruckloadList');
        $response->assertResponseStatus(200);
    }

    public function test404()
    {
        $response = $this->json("GET", '/api/drivers');
        $response->assertResponseStatus(404);
    }

    public function testListEmpty()
    {
        $response = $this->json("GET", '/api/v1/drivers/hasTruckloadList');
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
        $response = $this->json("GET", '/api/v1/drivers/hasTruckloadList');
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
        $response = $this->json("GET", '/api/v1/drivers/hasTruckloadList');
        $response->assertResponseStatus(200);
        $content = json_decode($this->response->getContent());
        $this->assertCount(3, $content->data);
    }

    public function testHasVehicleQtdIsExact()
    {
        factory(Driver::class)->create(["has_vehicles" => 0]);
        factory(Driver::class)->create(["has_vehicles" => 0]);
        factory(Driver::class)->create(["has_vehicles" => 0]);
        $response = $this->json("GET", '/api/v1/drivers/hasVehiclesQtd');
        $response->assertResponseStatus(200);
        $content = json_decode($this->response->getContent());
        $this->assertEquals(0, $content->data);

        factory(Driver::class)->create();
        factory(Driver::class)->create();
        $response = $this->json("GET", '/api/v1/drivers/hasVehiclesQtd');
        $response->assertResponseStatus(200);
        $content = json_decode($this->response->getContent());
        $this->assertEquals(2, $content->data);
    }

    public function testCanStoreData()
    {
        $this->post('/api/v1/drivers', [
            "payload" => [
                "name" => "Antonio Carlos",
                "gender" => "M",
                "has_vehicles" => 1,
                "cnh_type" => "D",
                "cnh" => 26462857289,
                "cpf" => 16294627452,
                "date_of_birth" => "04-12-1976"
            ]
        ]);
        $this->assertResponseStatus(201);
    }

    public function testCanNotStoreData()
    {
        $this->post('/api/v1/drivers', [
            "payload" => [
                "name" => "Antonio Carlos",
                "gender" => "Y",
                "has_vehicles" => 1,
                "cnh_type" => "G",
                "cnh" => 26462857289,
                "cpf" => 16294627452,
                "date_of_birth" => "04-12-1976"
            ]
        ]);
        $content = json_decode($this->response->getContent());
        $this->assertResponseStatus(400);
        $this->assertContains("The selected payload.cnh type is invalid.", $content->detail);
        $this->assertContains("The selected payload.gender is invalid.", $content->detail);
    }

    public function testCanUpdateData()
    {
        $driver = factory(Driver::class)->create();
        $this->put("/api/v1/drivers/{$driver->id}", [
            "payload" => [
                "name" => "Carlos Antonio",
                "gender" => "M",
                "has_vehicles" => 0,
                "cnh_type" => "E",
                "cnh" => 26462857289,
                "cpf" => 16294627452,
                "date_of_birth" => "04-12-1976"
            ]
        ]);
        $this->assertResponseStatus(201);

    }

    public function testCanNotUpdateData()
    {
        $driver = factory(Driver::class)->create();
        $this->put("/api/v1/drivers/{$driver->id}", [
            "payload" => [
                "name" => "Antonio Carlos",
                "gender" => "M",
                "has_vehicles" => 0,
                "cnh_type" => "G",
                "cnh" => 26462857289,
                "cpf" => 16294627452,
                "date_of_birth" => "04-12-1976"
            ]
        ]);
        $content = json_decode($this->response->getContent());
        $this->assertResponseStatus(400);
        $this->assertContains("The selected payload.cnh type is invalid.", $content->detail);

        $this->put("/api/v1/drivers/2", [
            "payload" => [
                "name" => "Antonio Carlos",
                "gender" => "M",
                "has_vehicles" => 0,
                "cnh_type" => "E",
                "cnh" => 26462857289,
                "cpf" => 16294627452,
                "date_of_birth" => "04-12-1976"
            ]
        ]);
        $this->assertResponseStatus(400);
    }
}
