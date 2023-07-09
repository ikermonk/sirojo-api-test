<?php
namespace Tests\Domain;

use Tests\TestCase;
use Ramsey\Uuid\Uuid;
use Src\App\Cart\Domain\CartItem;

class CartItemTest extends TestCase {
    public function test_CartDto() {
        // Crear los objetos necesarios para la prueba:
        //Item:
        $id = "1";
        $item_uuid = Uuid::uuid4();
        $item_uuid_id = $item_uuid->toString();
        $uuid = $item_uuid_id;
        $id_cart = "1";
        $product_id = "3";
        $quantity = 2;
        //Testedt Class:
        $item = new CartItem($id, $uuid, $id_cart, $product_id, $quantity);
        //Assert:
        $this->assertEquals($id, $item->id);
        $this->assertEquals($uuid, $item->uuid);
        $this->assertEquals($id_cart, $item->id_cart);
        $this->assertEquals($product_id, $item->product_id);
        $this->assertEquals($quantity, $item->quantity);
    }
}
?>