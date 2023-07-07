<?php
namespace Tests\Usecases;

use Tests\TestCase;
use Ramsey\Uuid\Uuid;
use Src\App\Cart\Domain\Cart;
use Src\Shared\Request\RequestId;
use Src\App\Cart\Domain\Dto\Cart as CartDto;
use Src\App\Cart\Application\Find\FindUserCart;
use Src\App\Cart\Domain\Transform\CartTransform;
use Src\App\Cart\Infrastructure\Persitence\CartRepository;

class FindCartTest extends TestCase {
    public function testFindCart_Ok() {
        // Crear los objetos necesarios para la prueba
        //Request:
        $requestId = new RequestId("1", "user");
        //Cart:
        $cart_uuid = Uuid::uuid4();
        $cart_uuid_id = $cart_uuid->toString();
        $cart = new Cart("1", $cart_uuid_id, "1", []);
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
            ->with($requestId->getId(), $requestId->getBy())
            ->willReturn($cart);
        $cartTransform->expects($this->once())
            ->method('transform')
            ->with($cart)
            ->willReturn($cart_dto);
        //Create Tested Class:
        $findCartService = new FindUserCart($cartRepository, $cartTransform);
        $new_cart = $findCartService->find($requestId);
        //Assert:
        $this->assertInstanceOf(CartDto::class, $new_cart);
        $this->assertSame($cart_dto, $new_cart);
    }
}
?>