<?php
namespace Tests\Dto;

use Tests\TestCase;
use Src\App\Cart\Domain\Dto\Cart;

class CartTest extends TestCase {
    public function test_CartDto() {
        // Crear los objetos necesarios para la prueba:
        //Cart:
        $id = "cart_id";
        $uuid = "cart_uuid";
        $user_id = "user_id";
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