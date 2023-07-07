<?php
namespace Tests;

use Ramsey\Uuid\Uuid;
use Src\App\Cart\Domain\Cart;
use Src\App\Cart\Domain\CartItem;
use Src\Shared\Request\RequestRemoveItem;
use Src\App\Cart\Domain\Dto\Cart as CartDto;
use Src\App\Cart\Domain\Transform\CartTransform;
use Src\App\Cart\Infrastructure\Persitence\CartRepository;
use Src\App\Cart\Application\RemoveItem\RemoveItemFromCart;

class RemoveItemFromCartTest extends TestCase {
    public function testRemoveItemFromCart(): void {
        // Crear los objetos necesarios para la prueba:
        //Item:
        $item_uuid = Uuid::uuid4();
        $item_uuid_id = $item_uuid->toString();
        $item = new CartItem("1", $item_uuid_id, "1", "3", 2);
        //Item2:
        $item2_uuid = Uuid::uuid4();
        $item2_uuid_id = $item2_uuid->toString();
        $item2 = new CartItem("1", $item2_uuid_id, "1", "1", 1);
        //Cart:
        $cart_uuid = Uuid::uuid4();
        $cart_uuid_id = $cart_uuid->toString();
        $cart = new Cart("1", $cart_uuid_id, "1", []);
        array_push($cart->items, $item);
        array_push($cart->items, $item2);
        //Request:
        $request = new RequestRemoveItem($item2_uuid_id, $cart_uuid_id);
        //Cart DTO
        $cart_dto = new CartDto("1", $cart_uuid_id, "1", []);
        array_push($cart_dto->items, $item);
        //Dependencias:
        $cartRepository = $this->createMock(CartRepository::class);
        $cartTransform = $this->createMock(CartTransform::class);
        //Mocks:
        $cartRepository->expects($this->once())
            ->method('get')
            ->with($request->id_cart, "")
            ->willReturn($cart);
        $cartRepository->expects($this->once())
            ->method('delete_item')
            ->with($request->id);
        $cartTransform->expects($this->once())
            ->method('transform')
            ->with($cart)
            ->willReturn($cart_dto);
        //Test Class:
        $removeItemFromCart = new RemoveItemFromCart($cartRepository, $cartTransform);
        $result = $removeItemFromCart->remove($request);
        //Assert:
        $this->assertInstanceOf(CartDto::class, $result);
        $this->assertSame($cart_dto, $result);
    }
}
?>