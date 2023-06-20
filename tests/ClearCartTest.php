<?php
namespace Tests;

use Src\App\Cart\Domain\Cart;
use Src\App\Cart\Domain\CartItem;
use Illuminate\Support\Facades\Log;
use Src\Shared\Request\RequestClearCart;
use Src\App\Cart\Application\ClearCart\ClearCart;
use Src\App\Cart\Infrastructure\Persitence\CartRepository;
use Src\App\Cart\Infrastructure\Persitence\CartItemsRepository;

class ClearCartTest extends TestCase {
    public function testClearCart(): void {
        //Dependencies:
        $cartRepo = $this->createMock(CartRepository::class);
        $cartItemsRepo = $this->createMock(CartItemsRepository::class);
        $clearCart = new ClearCart($cartRepo, $cartItemsRepo);
        $request = new RequestClearCart("1", ["1", "2"]);
        $cart = new Cart("1", "1", []);
        $items = ["1", "2"];
        $cart->items = $items;
        $cart2 = new Cart("1", "1", []);
        //Mocks:
        $cartItemsRepo->expects($this->exactly(2))
            ->method('delete')
            ->willReturnCallback(function ($itemId) use ($items) {
                foreach ($items as $item) {
                    if ($item === $itemId) {
                        unset($item);
                    }
                }
            });
        $cartRepo->expects($this->once())
            ->method('get')
            ->with($request->user_id)
            ->willReturn($cart2);
        //Tested Calss:
        $result = $clearCart->clear($request);
        // Assert
        $this->assertInstanceOf(Cart::class, $result);
        $this->assertEquals(sizeof($result->items), 0);
    }
}
?>