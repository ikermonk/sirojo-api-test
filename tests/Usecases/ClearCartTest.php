<?php
namespace Tests\Usecases;

use Tests\TestCase;
use Ramsey\Uuid\Uuid;
use Src\App\Cart\Domain\Cart;
use Src\App\Cart\Domain\CartItem;
use Src\Shared\Request\RequestId;
use Src\App\Cart\Domain\Dto\Cart as CartDto;
use Src\App\Cart\Domain\Transform\CartTransform;
use Src\App\Cart\Application\ClearCart\ClearCart;
use Src\App\Cart\Infrastructure\Persitence\CartRepository;

class ClearCartTest extends TestCase {
    public function testClearCart(): void {
        // Crear los objetos necesarios para la prueba:
        //Request:
        $request = new RequestId("1", "");
        //Item:
        $item_uuid = Uuid::uuid4();
        $item_uuid_id = $item_uuid->toString();
        $item = new CartItem("1", $item_uuid_id, "1", "3", 2);
        //Cart:
        $cart_uuid = Uuid::uuid4();
        $cart_uuid_id = $cart_uuid->toString();
        $cart = new Cart("1", $cart_uuid_id, "1", []);
        array_push($cart->items, $item);
        //Cart DTO
        $cart_dto_uuid = Uuid::uuid4();
        $cart_dto_uuid_id = $cart_dto_uuid->toString();
        $cart_dto = new CartDto("1", $cart_dto_uuid_id, "1", []);
        //Dependencias:
        $cartRepository = $this->createMock(CartRepository::class);
        $cartTransform = $this->createMock(CartTransform::class);
        //Mocks:
        $cartRepository->expects($this->once())
            ->method('get')
            ->with($request->getId(), "")
            ->willReturn($cart);
        $cartRepository->expects($this->once())
            ->method('delete')
            ->with($cart);
        $cartTransform->expects($this->once())
            ->method('transform')
            ->with($cart)
            ->willReturn($cart_dto);
        //Tested Class:
        $clearCart = new ClearCart($cartRepository, $cartTransform);
        $result = $clearCart->clear($request);
        //Assert:
        $this->assertInstanceOf(CartDto::class, $result);
        $this->assertSame($cart_dto, $result);
    }
}
?>