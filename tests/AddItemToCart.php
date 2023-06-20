<?php
namespace Tests;

class AddItemToCart extends TestCase {
    public function testAddItemToCart_Ok() {
        //Dependencies:
        $cart = new Cart("1", "1", []);
        $items = [
            new CartItem("1", "1", "1", 2),
            new CartItem("2", "1", "3", 1)
        ];
        $cartRepo = $this->createMock(CartRepository::class);
        $cartItemsRepo = $this->createMock(CartItemsRepository::class);
        
    }
}
?>