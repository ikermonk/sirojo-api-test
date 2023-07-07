<?php

use Tests\TestCase;
use Ramsey\Uuid\Uuid;
use Src\App\Cart\Domain\Cart;
use Src\App\Cart\Domain\CartItem;

class CartTest extends TestCase {
    public function test_FindItem_ReturnTrueItems() {
        // Crear los objetos necesarios para la prueba:
        //Cart:
        $cart_uuid = Uuid::uuid4();
        $cart_uuid_id = $cart_uuid->toString();
        $cart = new Cart("1", $cart_uuid_id, "1", []);
        //Item:
        $item_uuid = Uuid::uuid4();
        $item_uuid_id = $item_uuid->toString();
        $item = new CartItem("1", $item_uuid_id, "1", "3", 1);
        array_push($cart->items, $item);
        //Assert:
        $this->assertTrue($cart->find_item("3"));
    }

    public function test_FindItem_ReturnFalseItems() {
        // Crear los objetos necesarios para la prueba:
        //Cart:
        $cart_uuid = Uuid::uuid4();
        $cart_uuid_id = $cart_uuid->toString();
        $cart = new Cart("1", $cart_uuid_id, "1", []);
        //Item:
        $item_uuid = Uuid::uuid4();
        $item_uuid_id = $item_uuid->toString();
        $item = new CartItem("1", $item_uuid_id, "1", "3", 1);
        array_push($cart->items, $item);
        //Assert:
        $this->assertFalse($cart->find_item("1"));
    }

    public function test_FindItem_ReturnFalseNoItems() {
        // Crear los objetos necesarios para la prueba:
        //Cart:
        $cart_uuid = Uuid::uuid4();
        $cart_uuid_id = $cart_uuid->toString();
        $cart = new Cart("1", $cart_uuid_id, "1", []);
        //Assert:
        $this->assertFalse($cart->find_item("1"));
    }

/*
    public function testFindItemReturnsFalseIfItemDoesNotExist() {
        $item = new CartItem("", Uuid::uuid4()->toString(), "cart_id", "product_id", 1);
        $cart = new Cart("cart_id", Uuid::uuid4()->toString(), "user_id", [$item]);

        $this->assertFalse($cart->find_item("non_existing_product_id"));
    }

    public function testAddItemToCartAddsNewItemIfItemDoesNotExist() {
        $cart = new Cart("cart_id", Uuid::uuid4()->toString(), "user_id", []);
        $product_id = "product_id";
        $quantity = 1;

        $cart->addItemToCart($product_id, $quantity);
        $items = $cart->items;

        $this->assertCount(1, $items);
        $this->assertEquals($product_id, $items[0]->product_id);
        $this->assertEquals($quantity, $items[0]->quantity);
    }

    public function testAddItemToCartUpdatesExistingItemIfItemExists() {
        $item = new CartItem("", Uuid::uuid4()->toString(), "cart_id", "product_id", 1);
        $cart = new Cart("cart_id", Uuid::uuid4()->toString(), "user_id", [$item]);
        $product_id = "product_id";
        $quantity = 2;

        $cart->addItemToCart($product_id, $quantity);
        $items = $cart->items;

        $this->assertCount(1, $items);
        $this->assertEquals($product_id, $items[0]->product_id);
        $this->assertEquals(3, $items[0]->quantity);
    }

    public function testUpdateUpdatesCartItems() {
        $item1 = new CartItem("", Uuid::uuid4()->toString(), "cart_id", "product_id1", 1);
        $item2 = new CartItem("", Uuid::uuid4()->toString(), "cart_id", "product_id2", 2);
        $cart = new Cart("cart_id", Uuid::uuid4()->toString(), "user_id", [$item1, $item2]);

        $updatedItems = [
            ["id" => $item1->uuid, "quantity" => 5],
            ["id" => $item2->uuid, "quantity" => 3]
        ];

        $cart->update($updatedItems);

        $this->assertEquals(5, $cart->items[0]->quantity);
        $this->assertEquals(3, $cart->items[1]->quantity);
    }

    public function testUpdateItemAddsQuantityToExistingItem() {
        $item = new CartItem("", Uuid::uuid4()->toString(), "cart_id", "product_id", 1);
        $cart = new Cart("cart_id", Uuid::uuid4()->toString(), "user_id", [$item]);
        $product_id = "product_id";
        $quantity = 2;

        $cart->update_item($product_id, $quantity);

        $this->assertEquals(3, $cart->items[0]->quantity);
    }

    public function testRemoveItemFromCartRemovesItemFromItemsArray() {
        $item1 = new CartItem("", Uuid::uuid4()->toString(), "cart_id", "product_id1", 1);
        $item2 = new CartItem("", Uuid::uuid4()->toString(), "cart_id", "product_id2", 2);
        $cart = new Cart("cart_id", Uuid::uuid4()->toString(), "user_id", [$item1, $item2]);
        $id_item = $item1->uuid;

        $cart->removeItemFromCart($id_item);

        $this->assertCount(1, $cart->items);
        $this->assertEquals($item2, $cart->items[0]);
    }

    public function testClearCartRemovesAllItemsFromItemsArray() {
        $item1 = new CartItem("", Uuid::uuid4()->toString(), "cart_id", "product_id1", 1);
        $item2 = new CartItem("", Uuid::uuid4()->toString(), "cart_id", "product_id2", 2);
        $cart = new Cart("cart_id", Uuid::uuid4()->toString(), "user_id", [$item1, $item2]);

        $cart->clearCart();

        $this->assertCount(0, $cart->items);
    }
    */
}
?>