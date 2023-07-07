<?php
namespace Tests;

use Ramsey\Uuid\Uuid;
use Src\App\Cart\Domain\Cart;
use Src\App\Cart\Domain\CartItem;
use Src\Shared\Request\RequestUpdateCart;
use Src\App\Cart\Domain\Dto\Cart as CartDto;
use Src\App\Cart\Application\Update\UpdateCart;
use Src\App\Cart\Domain\Transform\CartTransform;
use Src\App\Cart\Infrastructure\Persitence\CartRepository;


class UpdateCartTest extends TestCase {
    public function testUpdateCart(): void {
        // Crear los objetos necesarios para la prueba:
        //Item:
        $item_uuid = Uuid::uuid4();
        $item_uuid_id = $item_uuid->toString();
        $item = new CartItem("1", $item_uuid_id, "1", "3", 1);
        //Item Updated:
        $item2_uuid = Uuid::uuid4();
        $item2_uuid_id = $item2_uuid->toString();
        $item2 = new CartItem("1", $item2_uuid_id, "1", "3", 3);
        //Cart:
        $cart_uuid = Uuid::uuid4();
        $cart_uuid_id = $cart_uuid->toString();
        $cart = new Cart("1", $cart_uuid_id, "1", []);
        array_push($cart->items, $item);
        //Request:
        $items = [
            [
                "id" => $item_uuid_id,
                "quantity" => 3
            ]
        ];
        $request = new RequestUpdateCart($cart_uuid_id, "1", $items);
        //Cart DTO
        $cart_dto = new CartDto("1", $cart_uuid_id, "1", []);
        array_push($cart_dto->items, $item2);        
        //Dependencias:
        $cartRepository = $this->createMock(CartRepository::class);
        $cartTransform = $this->createMock(CartTransform::class);
        //Mocks:
        $cartRepository->expects($this->once())
            ->method('get')
            ->with($request->user_id, "user")
            ->willReturn($cart);
        $cartRepository->expects($this->once())
            ->method('update')
            ->with($cart->uuid, $cart);
        $cartTransform->expects($this->once())
            ->method('transform')
            ->with($cart)
            ->willReturn($cart_dto);
        //Test Class:
        $updateCart = new UpdateCart($cartRepository, $cartTransform);
        $result = $updateCart->update($request);
        //Assert:
        $this->assertInstanceOf(CartDto::class, $result);
        $this->assertSame($cart_dto, $result);
    }
}
?>