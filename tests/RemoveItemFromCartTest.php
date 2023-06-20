<?php
namespace Tests;

use Src\App\Cart\Domain\Cart;
use Src\App\Cart\Domain\CartItem;
use Illuminate\Support\Facades\Log;
use Src\Shared\Request\RequestRemoveItem;
use Src\App\Cart\Infrastructure\Persitence\CartRepository;
use Src\App\Cart\Application\RemoveItem\RemoveItemFromCart;
use Src\App\Cart\Infrastructure\Persitence\CartItemsRepository;

class RemoveItemFromCartTest extends TestCase {
    public function testRemoveItemFromCart(): void {
        //Dependencies:
        $cartRepo = $this->createMock(CartRepository::class);
        $cartItemsRepo = $this->createMock(CartItemsRepository::class);
        $removeItemFromCart = new RemoveItemFromCart($cartRepo, $cartItemsRepo);
        $request = new RequestRemoveItem("1", "1");
        $cart = new Cart("1", "1", []);
        $items = [
            new CartItem("1", "1", "1", 2),
            new CartItem("2", "1", "3", 1)
        ];
        $cart->items = $items;
        $cart2 = new Cart("1", "1", []);
        $items2 = [
            new CartItem("2", "1", "3", 1)
        ];
        $cart2->items = $items2;
        //Mock:
        $cartItemsRepo->expects($this->once())
            ->method('delete')
            ->with("1");
        $cartRepo->expects($this->once())
            ->method('get')
            ->with("1")
            ->willReturn($cart2);
        //Test Class:
        $result = $removeItemFromCart->remove($request);
        Log::info(json_encode($result));
        // Assert
        $this->assertSame($cart2, $result);
    }
}
?>