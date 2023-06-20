<?php
namespace Tests;

use Src\App\Cart\Domain\Cart;
use Src\App\Cart\Domain\CartItem;
use Illuminate\Support\Facades\Log;
use Src\Shared\Request\RequestUpdateCart;
use Src\App\Cart\Application\Update\UpdateCart;
use Src\App\Cart\Infrastructure\Persitence\CartRepository;
use Src\App\Cart\Infrastructure\Persitence\CartItemsRepository;

class UpdateCartTest extends TestCase {
    public function testUpdateCart(): void {
        //Dependencies:
        $cartRepo = $this->createMock(CartRepository::class);
        $cartItemsRepo = $this->createMock(CartItemsRepository::class);
        $updateCart = new UpdateCart($cartRepo, $cartItemsRepo);
        $request = new RequestUpdateCart("1", "1", [["id" => "1", "quantity" => 3], ["id" => "2", "quantity" => 2]]);
        $cart = new Cart("1", "1", []);
        $items = [
            new CartItem("1", "1", "1", 1),
            new CartItem("2", "1", "3", 1)
        ];
        $itemsToUpdate = [
            ["id" => "1", "quantity" => 3], 
            ["id" => "2", "quantity" => 2]
        ];
        $cart->items = $items;
        $cart2 = new Cart("1", "1", []);
        $items2 = [
            new CartItem("1", "1", "1", 3),
            new CartItem("2", "1", "3", 2)
        ];
        $cart2->items = $items2;
        //Mock:
        $cartItemsRepo->expects($this->exactly(2))
            ->method('update')
            ->willReturnCallback(function ($itemId) use ($items, $itemsToUpdate) {
                Log::info("UpdateCartTest - testUpdateCart - itemsToUpdate => " . json_encode($itemsToUpdate));
                $itemQuantity = null;
                foreach ($itemsToUpdate as $itemToUpdate) {
                    if ($itemToUpdate["id"] === $itemId) {
                        $itemQuantity = $itemToUpdate;
                    }
                }
                $itemUpdated = null;
                foreach ($items as $item) {
                    if ($item->id === $itemId) {
                        $item->quantity = $itemQuantity["quantity"];
                    }
                }
            });
        $cartRepo->expects($this->once())
            ->method('get')
            ->with($request->user_id)
            ->willReturn($cart2);
        //Tested Calss:
        $result = $updateCart->update($request);
        // Assert
        $this->assertSame($cart2, $result);;
    }
}
?>