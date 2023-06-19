<?php
namespace Tests;

use Src\App\Cart\Domain\Cart;
use Src\App\Cart\Domain\CartItem;
use Src\Shared\Request\RequestId;
use Src\App\Cart\Application\Find\FindUserCart;
use Src\App\Cart\Infrastructure\Persitence\CartRepository;
use Src\App\Cart\Infrastructure\Persitence\CartItemsRepository;

class FindCartTest extends TestCase {
    public function testFindCart_Ok() {
        //Dependencies:
        $cart = new Cart("1", "1", []);
        $items = [
            new CartItem("1", "1", "1", 2),
            new CartItem("2", "1", "3", 1)
        ];
        $cartRepo = $this->createMock(CartRepository::class);
        $cartItemsRepo = $this->createMock(CartItemsRepository::class);
        //Mock:
        $cartRepo
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo("1"))
            ->willReturn($cart);
        $cartItemsRepo
            ->expects($this->once())
            ->method('list')
            ->with($this->equalTo("1"))
            ->willReturn($items);
        //Create Tested Class:
        $requestId = new RequestId("1");
        $findCartService = new FindUserCart($cartRepo, $cartItemsRepo);
        $new_cart = $findCartService->find($requestId);
        //Assert:
        $this->assertEquals($cart->id, $new_cart->id);
        $this->assertEquals($cart->user_id, $new_cart->user_id);
        $this->assertEquals($cart->items[0]->id, $new_cart->items[0]->id);
        $this->assertEquals($cart->items[1]->id, $new_cart->items[1]->id);
    }

    public function testFindCart_EmptyCart() {
        //Dependencies:
        $cart = new Cart("", "1", []);
        $cart2 = new Cart("1", "1", []);
        $items = [
            new CartItem("1", "1", "1", 2),
            new CartItem("2", "1", "3", 1)
        ];
        $cartRepo = $this->createMock(CartRepository::class);
        $cartItemsRepo = $this->createMock(CartItemsRepository::class);
        //Mock:
        $cartRepo
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo("1"))
            ->willReturn(null);
        $cartRepo
            ->expects($this->once())
            ->method('add')
            ->with($this->equalTo($cart))
            ->willReturn($cart2);
        $cartItemsRepo
            ->expects($this->once())
            ->method('list')
            ->with($this->equalTo("1"))
            ->willReturn([]);
        //Create Tested Class:
        $requestId = new RequestId("1");
        $findCartService = new FindUserCart($cartRepo, $cartItemsRepo);
        $new_cart = $findCartService->find($requestId);
        //Assert:
        $this->assertEquals($cart2->id, $new_cart->id);
        $this->assertEquals($cart2->user_id, $new_cart->user_id);
    }

}
?>