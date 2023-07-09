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

    public function test_AddItemToCart_FindItem() {
        // Crear los objetos necesarios para la prueba:
        //Item:
        $item_uuid = Uuid::uuid4();
        $item_uuid_id = $item_uuid->toString();
        $item = new CartItem("1", $item_uuid_id, "1", "3", 1);        
        //Cart:
        $cart_uuid = Uuid::uuid4();
        $cart_uuid_id = $cart_uuid->toString();
        $cart = new Cart("1", $cart_uuid_id, "1", []);
        array_push($cart->items, $item);
        //Tested Class
        $product_id = "3";
        $quantity = 1;
        $cart->addItemToCart($product_id, $quantity);
        //Assert:
        $this->assertEquals(1, sizeof($cart->items));
        $this->assertEquals($product_id, $cart->items[0]->product_id);
        $this->assertEquals(2, ($cart->items[0]->quantity));
    }

    public function test_AddItemToCart_NotFindItem() {
        // Crear los objetos necesarios para la prueba:
        //Item:
        $item_uuid = Uuid::uuid4();
        $item_uuid_id = $item_uuid->toString();
        $item = new CartItem("1", $item_uuid_id, "1", "3", 1);
        //Cart:
        $cart_uuid = Uuid::uuid4();
        $cart_uuid_id = $cart_uuid->toString();
        $cart = new Cart("1", $cart_uuid_id, "1", []);
        array_push($cart->items, $item);
        //Tested Class
        $product_id = "1";
        $quantity = 2;
        $cart->addItemToCart($product_id, $quantity);
        //Assert:
        $this->assertEquals(2, sizeof($cart->items));
        $this->assertEquals($product_id, $cart->items[1]->product_id);
        $this->assertEquals($quantity, ($cart->items[1]->quantity));
    }

    public function test_Update_UpdatesCartItems() {
        // Crear los objetos necesarios para la prueba:
        //Items:
        $item_uuid = Uuid::uuid4();
        $item_uuid_id = $item_uuid->toString();
        $item = new CartItem("1", $item_uuid_id, "1", "1", 1);
        $item2_uuid = Uuid::uuid4();
        $item2_uuid_id = $item2_uuid->toString();
        $item2 = new CartItem("1", $item2_uuid_id, "1", "2", 2);
        //Cart:
        $cart_uuid = Uuid::uuid4();
        $cart_uuid_id = $cart_uuid->toString();
        $cart = new Cart("1", $cart_uuid_id, "1", []);
        array_push($cart->items, $item);
        array_push($cart->items, $item2);
        //Tested Class:
        $updatedItems = [
            ["id" => $item_uuid_id, "quantity" => 5],
            ["id" => $item2_uuid_id, "quantity" => 3]
        ];
        $cart->update($updatedItems);
        //Assert:
        $this->assertEquals(5, $cart->items[0]->quantity);
        $this->assertEquals(3, $cart->items[1]->quantity);
    }

    public function test_Update_NotExistProducts() {
        // Crear los objetos necesarios para la prueba:
        //Items:
        $item_uuid = Uuid::uuid4();
        $item_uuid_id = $item_uuid->toString();
        $item = new CartItem("1", $item_uuid_id, "1", "1", 1);
        $item2_uuid = Uuid::uuid4();
        $item2_uuid_id = $item2_uuid->toString();
        $item2 = new CartItem("1", $item2_uuid_id, "1", "2", 2);
        //Cart:
        $cart_uuid = Uuid::uuid4();
        $cart_uuid_id = $cart_uuid->toString();
        $cart = new Cart("1", $cart_uuid_id, "1", []);
        array_push($cart->items, $item);
        array_push($cart->items, $item2);
        //Tested Class:
        $updatedItems = [
            ["id" => "1", "quantity" => 5],
            ["id" => "2", "quantity" => 3]
        ];
        $cart->update($updatedItems);
        //Assert:
        $this->assertEquals(1, $cart->items[0]->quantity);
        $this->assertEquals(2, $cart->items[1]->quantity);
    }
    
    public function test_UpdateItem_AddsQuantityToExistingItem() {
        // Crear los objetos necesarios para la prueba:
        //Items:
        $item_uuid = Uuid::uuid4();
        $item_uuid_id = $item_uuid->toString();
        $item = new CartItem("1", $item_uuid_id, "1", "1", 1);
        $item2_uuid = Uuid::uuid4();
        $item2_uuid_id = $item2_uuid->toString();
        $item2 = new CartItem("1", $item2_uuid_id, "1", "2", 2);
        //Cart:
        $cart_uuid = Uuid::uuid4();
        $cart_uuid_id = $cart_uuid->toString();
        $cart = new Cart("1", $cart_uuid_id, "1", []);
        array_push($cart->items, $item);
        array_push($cart->items, $item2);
        //Tested Class:
        $product_id = "1";
        $quantity = 2;
        $cart->update_item($product_id, $quantity);
        //Assert:
        $this->assertEquals(3, $cart->items[0]->quantity);
    }

    public function test_UpdateItem_AddsQuantityToExistingItem2() {
        // Crear los objetos necesarios para la prueba:
        //Items:
        $item_uuid = Uuid::uuid4();
        $item_uuid_id = $item_uuid->toString();
        $item = new CartItem("1", $item_uuid_id, "1", "1", 1);
        $item2_uuid = Uuid::uuid4();
        $item2_uuid_id = $item2_uuid->toString();
        $item2 = new CartItem("1", $item2_uuid_id, "1", "2", 2);
        //Cart:
        $cart_uuid = Uuid::uuid4();
        $cart_uuid_id = $cart_uuid->toString();
        $cart = new Cart("1", $cart_uuid_id, "1", []);
        array_push($cart->items, $item);
        array_push($cart->items, $item2);
        //Tested Class:
        $product_id = "2";
        $quantity = 3;
        $cart->update_item($product_id, $quantity);
        //Assert:
        $this->assertEquals(5, $cart->items[1]->quantity);
    }

    public function test_UpdateItem_NotExistProduct() {
        // Crear los objetos necesarios para la prueba:
        //Items:
        $item_uuid = Uuid::uuid4();
        $item_uuid_id = $item_uuid->toString();
        $item = new CartItem("1", $item_uuid_id, "1", "1", 1);
        $item2_uuid = Uuid::uuid4();
        $item2_uuid_id = $item2_uuid->toString();
        $item2 = new CartItem("1", $item2_uuid_id, "1", "2", 2);
        //Cart:
        $cart_uuid = Uuid::uuid4();
        $cart_uuid_id = $cart_uuid->toString();
        $cart = new Cart("1", $cart_uuid_id, "1", []);
        array_push($cart->items, $item);
        array_push($cart->items, $item2);
        //Tested Class:
        $product_id = "3";
        $quantity = 3;
        $cart->update_item($product_id, $quantity);
        //Assert:
        $this->assertEquals(1, $cart->items[0]->quantity);
        $this->assertEquals(2, $cart->items[1]->quantity);
    }

    public function test_RemoveItemFromCart_RemovesExistingItem() {
        // Crear los objetos necesarios para la prueba:
        //Item:
        $item_uuid = Uuid::uuid4();
        $item_uuid_id = $item_uuid->toString();
        $item = new CartItem("1", $item_uuid_id, "1", "3", 1);
        //Cart:
        $cart_uuid = Uuid::uuid4();
        $cart_uuid_id = $cart_uuid->toString();
        $cart = new Cart("1", $cart_uuid_id, "1", []);
        array_push($cart->items, $item);
        //Tested Class
        $id_item = $item_uuid_id;
        $cart->removeItemFromCart($id_item);
        //Assert
        $this->assertCount(0, $cart->items);
    }

    public function test_RemoveItemFromCart_RemovesExistingItem2() {
        // Crear los objetos necesarios para la prueba:
        //Items:
        $item_uuid = Uuid::uuid4();
        $item_uuid_id = $item_uuid->toString();
        $item = new CartItem("1", $item_uuid_id, "1", "1", 1);
        $item2_uuid = Uuid::uuid4();
        $item2_uuid_id = $item2_uuid->toString();
        $item2 = new CartItem("1", $item2_uuid_id, "1", "2", 2);
        //Cart:
        $cart_uuid = Uuid::uuid4();
        $cart_uuid_id = $cart_uuid->toString();
        $cart = new Cart("1", $cart_uuid_id, "1", []);
        array_push($cart->items, $item);
        array_push($cart->items, $item2);
        //Tested Class:
        $id_item = $item_uuid_id;
        $cart->removeItemFromCart($id_item);
        //Assert
        $this->assertCount(1, $cart->items);
        $this->assertSame($item2, $cart->items[1]);
    }

    public function test_RemoveItemFromCart_ItemNotExist() {
        // Crear los objetos necesarios para la prueba:
        //Item:
        $item_uuid = Uuid::uuid4();
        $item_uuid_id = $item_uuid->toString();
        $item = new CartItem("1", $item_uuid_id, "1", "3", 1);
        //Cart:
        $cart_uuid = Uuid::uuid4();
        $cart_uuid_id = $cart_uuid->toString();
        $cart = new Cart("1", $cart_uuid_id, "1", []);
        array_push($cart->items, $item);
        //Tested Class
        $id_item = "1";
        $cart->removeItemFromCart($id_item);
        //Assert
        $this->assertCount(1, $cart->items);
        $this->assertSame($item, $cart->items[0]);
    }

    public function test_ClearCart() {
        // Crear los objetos necesarios para la prueba:
        //Items:
        $item_uuid = Uuid::uuid4();
        $item_uuid_id = $item_uuid->toString();
        $item = new CartItem("1", $item_uuid_id, "1", "1", 1);
        $item2_uuid = Uuid::uuid4();
        $item2_uuid_id = $item2_uuid->toString();
        $item2 = new CartItem("1", $item2_uuid_id, "1", "2", 2);
        //Cart:
        $cart_uuid = Uuid::uuid4();
        $cart_uuid_id = $cart_uuid->toString();
        $cart = new Cart("1", $cart_uuid_id, "1", []);
        array_push($cart->items, $item);
        array_push($cart->items, $item2);
        //Tested Class:
        $cart->clearCart();
        //Assert:
        $this->assertCount(0, $cart->items);
    }
}
?>