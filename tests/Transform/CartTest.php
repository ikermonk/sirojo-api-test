<?php
namespace Tests\Transform;

use Tests\TestCase;
use Ramsey\Uuid\Uuid;
use Src\App\Cart\Domain\Cart;
use Src\App\Cart\Domain\CartItem;
use Src\App\Cart\Domain\Dto\Cart as CartDto;
use Src\App\Cart\Domain\Transform\CartTransform;

class CartTest extends TestCase {
    public function test_Cart_OkTransform() {
        // Crear los objetos necesarios para la prueba:
        //Cart:
        $cart_uuid = Uuid::uuid4();
        $cart_uuid_id = $cart_uuid->toString();
        $cart = new Cart("1", $cart_uuid_id, "1", []);
        //Item:
        $item_uuid = Uuid::uuid4();
        $item_uuid_id = $item_uuid->toString();
        $item = new CartItem("1", $item_uuid_id, "1", "3", 1);
        array_push($cart->items, $item);
        //Tested Class:
        $transform = new CartTransform();
        $result = $transform->transform($cart);
        //Assert:
        $this->assertInstanceOf(CartDto::class, $result);
        $this->assertEquals($result->id, $cart->id);
        $this->assertEquals($result->uuid, $cart->uuid);
        $this->assertEquals($result->user_id, $cart->user_id);
        $this->assertEquals($result->items, $cart->items);
    }
}
?>