<?php
namespace Tests;

use Src\App\Cart\Domain\Cart;
use Src\App\Cart\Domain\CartItem;
use App\Models\CartItems;
use Illuminate\Support\Facades\Log;
use Src\Shared\Request\RequestAddItem;
use Src\App\Cart\Application\AddItem\AddItemToCart;
use Src\App\Cart\Infrastructure\Persitence\CartRepository;
use Src\App\Cart\Infrastructure\Persitence\CartItemsRepository;

class AddItemToCartTest extends TestCase {
    public function testAddItemToCart_OkUpdateItem() {
        //Dependencies:
        $cartRepo = $this->createMock(CartRepository::class);
        $cartItemsRepo = $this->createMock(CartItemsRepository::class);
        $request = new RequestAddItem("1", "1", "3", 2);
        $cart = new Cart("1", "1", []);
        $items = [
            new CartItem("1", "1", "1", 2),
            new CartItem("2", "1", "3", 1)
        ];
        $cart->items = $items;
        
        $item_id = "2";
        $item = $items[1];
        
        $cart2 = new Cart("1", "1", []);
        $items2 = [
            new CartItem("1", "1", "1", 2),
            new CartItem("2", "1", "3", 3)
        ];
        $cart2->items = $items2;
        //Mock
        $cartItemsRepo->expects($this->once())
            ->method('list')
            ->with($request->id_cart)
            ->willReturn($items);
        $cartItemsRepo->expects($this->once())
            ->method('update')
            ->with($item_id, $item);
        $cartRepo->expects($this->once())
            ->method('get')
            ->with($request->user_id)
            ->willReturn($cart2);
        //Tested Class:
        $addItemToCart = new AddItemToCart($cartRepo, $cartItemsRepo);
        $result = $addItemToCart->add($request);
        //Assert:
        $this->assertInstanceOf(Cart::class, $result);
        $this->assertEquals($cart2->id, $result->id);
        $this->assertEquals($cart2->user_id, $result->user_id);
        $this->assertEquals($cart2->items[0]->id, $result->items[0]->id);
        $this->assertEquals($cart2->items[1]->id, $result->items[1]->id);
        $this->assertEquals($result->items[1]->quantity, 3);
    }
    
    public function testAddItemToCart_OkNewItem() {
        //Dependencies:
        $cartRepo = $this->createMock(CartRepository::class);
        $cartItemsRepo = $this->createMock(CartItemsRepository::class);
        $request = new RequestAddItem("1", "1", "5", 2);
        $cart = new Cart("1", "1", []);
        $items = [
            new CartItem("1", "1", "1", 2),
            new CartItem("2", "1", "3", 1)
        ];
        $cart->items = $items;

        $item = new CartItems();
        $item->id_cart = $request->id_cart;
        $item->product_id = $request->product_id;
        $item->quantity = $request->quantity;

        $item_eq = new CartItems();
        $item_eq->id_cart = "1";
        $item_eq->product_id = "5";
        $item_eq->quantity = 2;

        $cart2 = new Cart("1", "1", []);
        $items2 = [
            new CartItem("1", "1", "1", 2),
            new CartItem("2", "1", "3", 1),
            new CartItem("3", "1", "5", 2)
        ];
        $cart2->items = $items2;
        //Mock
        $cartItemsRepo->expects($this->once())
            ->method('list')
            ->with($request->id_cart)
            ->willReturn($items);
        $cartItemsRepo->expects($this->once())
            ->method('add')
            ->with($item_eq);
        $cartRepo->expects($this->once())
            ->method('get')
            ->with($request->user_id)
            ->willReturn($cart2);
        //Tested Class:
        $addItemToCart = new AddItemToCart($cartRepo, $cartItemsRepo);
        $result = $addItemToCart->add($request);
        //Assert:
        $this->assertInstanceOf(Cart::class, $result);
        $this->assertEquals($cart2->id, $result->id);
        $this->assertEquals($cart2->user_id, $result->user_id);
        $this->assertEquals($cart2->items[0]->id, $result->items[0]->id);
        $this->assertEquals($cart2->items[1]->id, $result->items[1]->id);
        $this->assertEquals($cart2->items[2]->id, $result->items[2]->id);
        $this->assertEquals($cart2->items[2]->product_id, $result->items[2]->product_id);
        $this->assertEquals($cart2->items[2]->quantity, $result->items[2]->quantity);
    }
}
?>