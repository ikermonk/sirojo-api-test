<?php
namespace Tests;

use Src\App\Cart\Domain\Cart;
use Src\App\Cart\Domain\CartItem;
use Src\Shared\Request\RequestId;
use Src\App\Cart\Application\Find\FindUserCart;
use Src\App\Cart\Infrastructure\Persitence\CartRepository;

class FindCartTest extends TestCase {
    public function testFindCart_Ok() {
        //Dependencies:
        $cart = new Cart("1", "1", []);
        $items = [
            new CartItem("1", "1", "1", 2),
            new CartItem("2", "1", "3", 1)
        ];
        $cart->items = $items;
        $cartRepo = $this->createMock(CartRepository::class);
        //Mock:
        $cartRepo
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo("1"))
            ->willReturn($cart);
        //Create Tested Class:
        $requestId = new RequestId("1");
        $findCartService = new FindUserCart($cartRepo);
        $new_cart = $findCartService->find($requestId);
        //Assert:
        $this->assertEquals($cart->id, $new_cart->id);
        $this->assertEquals($cart->user_id, $new_cart->user_id);
        $this->assertEquals($cart->items[0]->id, $new_cart->items[0]->id);
        $this->assertEquals($cart->items[1]->id, $new_cart->items[1]->id);
    }

}
?>