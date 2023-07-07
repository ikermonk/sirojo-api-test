<?php
namespace Tests\Usecases;

use Tests\TestCase;
use Ramsey\Uuid\Uuid;
use Src\App\Cart\Domain\Cart;
use Src\App\Cart\Domain\CartItem;
use Src\Shared\Request\RequestAddItem;
use Src\App\Cart\Domain\Dto\Cart as CartDto;
use Src\App\Cart\Domain\Transform\CartTransform;
use Src\App\Cart\Application\AddItem\AddItemToCart;
use Src\App\Cart\Infrastructure\Persitence\CartRepository;

class AddItemToCartTest extends TestCase {
    public function testAddItemToCart_OkNewItem() {
        // Crear los objetos necesarios para la prueba
        //Request:
        $request = new RequestAddItem("1", "1", "3", 2);
        //Item:
        $item_uuid = Uuid::uuid4();
        $item_uuid_id = $item_uuid->toString();
        $item = new CartItem("1", $item_uuid_id, "1", "3", 2);
        //Cart:
        $cart_uuid = Uuid::uuid4();
        $cart_uuid_id = $cart_uuid->toString();
        $cart = new Cart("1", $cart_uuid_id, "1", []);
        //Cart DTO
        $cart_dto_uuid = Uuid::uuid4();
        $cart_dto_uuid_id = $cart_dto_uuid->toString();
        $cart_dto = new CartDto("1", $cart_dto_uuid_id, "1", []);
        array_push($cart_dto->items, $item);
        //Cart Added Item:
        $cart_added_uuid = Uuid::uuid4();
        $cart_added_uuid_id = $cart_uuid->toString();
        $cart_added = new Cart("1", $cart_added_uuid_id, "1", []);
        array_push($cart_added->items, $item);
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
            ->with($cart_uuid_id, $cart);
        $cartTransform->expects($this->once())
            ->method('transform')
            ->with($cart)
            ->willReturn($cart_dto);
        //Tested Class:
        $addItemToCart = new AddItemToCart($cartRepository, $cartTransform);
        $new_cart = $addItemToCart->add($request);
        //Assert:
        $this->assertInstanceOf(CartDto::class, $new_cart);
        $this->assertSame($cart_dto, $new_cart);
    }
    
    public function testAddItemToCart_OkUpdateItem() {
        // Crear los objetos necesarios para la prueba
        //Request:
        $request = new RequestAddItem("1", "1", "3", 2);
        //Old Item:
        $old_item_uuid = Uuid::uuid4();
        $old_item_uuid_id = $old_item_uuid->toString();
        $old_item = new CartItem("1", $old_item_uuid_id, "1", "3", 1);
        //Cart:
        $cart_uuid = Uuid::uuid4();
        $cart_uuid_id = $cart_uuid->toString();
        $cart = new Cart("1", $cart_uuid_id, "1", []);
        array_push($cart->items, $old_item);
        //New Item:
        $new_item_uuid = Uuid::uuid4();
        $new_item_uuid_id = $new_item_uuid->toString();
        $new_item = new CartItem("1", $new_item_uuid_id, "1", "3", 3);
        //Cart Added Item:
        $cart_added_uuid = Uuid::uuid4();
        $cart_added_uuid_id = $cart_uuid->toString();
        $cart_added = new Cart("1", $cart_added_uuid_id, "1", []);
        array_push($cart_added->items, $new_item);
        //Cart DTO
        $cart_dto_uuid = Uuid::uuid4();
        $cart_dto_uuid_id = $cart_dto_uuid->toString();
        $cart_dto = new CartDto("1", $cart_dto_uuid_id, "1", []);
        array_push($cart_dto->items, $new_item);
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
            ->with($cart_uuid_id, $cart);
        $cartTransform->expects($this->once())
            ->method('transform')
            ->with($cart)
            ->willReturn($cart_dto);
        //Tested Class:
        $addItemToCart = new AddItemToCart($cartRepository, $cartTransform);
        $new_cart = $addItemToCart->add($request);
        //Assert:
        $this->assertInstanceOf(CartDto::class, $new_cart);
        $this->assertSame($cart_dto, $new_cart);
    }
}
?>