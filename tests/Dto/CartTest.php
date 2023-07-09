<?php
namespace Tests\Dto;

use Tests\TestCase;
use Ramsey\Uuid\Uuid;
use Src\App\Cart\Domain\Dto\Cart;

class CartTest extends TestCase {
    public function test_CartDto() {
        // Crear los objetos necesarios para la prueba:
        //Cart:
        $id = "1";
        $cart_dto_uuid = Uuid::uuid4();
        $cart_dto_uuid_id = $cart_dto_uuid->toString();
        $uuid = $cart_dto_uuid_id;
        $user_id = "1";
        $items = [];
        //Testedt Class:
        $cart = new Cart($id, $uuid, $user_id, $items);
        //Assert:
        $this->assertEquals($id, $cart->id);
        $this->assertEquals($uuid, $cart->uuid);
        $this->assertEquals($user_id, $cart->user_id);
        $this->assertEquals($items, $cart->items);
    }
}
?>