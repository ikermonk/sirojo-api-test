<?php
namespace Src\App\Cart\Infrastructure\Persitence;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Models\CartItems;
use App\Services\CartService;
use Src\App\Cart\Domain\Cart;
use App\Models\Cart as CartEq;
use Src\App\Cart\Domain\CartItem;
use App\Services\CartItemsService;
use Illuminate\Support\Facades\Log;
use Src\Shared\Crud\GetServiceInterface;
use Src\Shared\Crud\UpdateServiceInterface;

class CartRepository implements GetServiceInterface, UpdateServiceInterface {
    private CartService $cart_eq_service;
    private CartItemsService $cart_items_eq_service;
    public function __construct(private readonly CartService $cartEqService, private readonly CartItemsService $cartItemsEqService) {
        $this->cart_eq_service = $cartEqService;
        $this->cart_items_eq_service = $cartItemsEqService;
    }
    
    public function get(string $id, string $by = null): mixed {
        //Get Cart Info:
        $cart_eq = $this->cart_eq_service->get($id, $by);
        //If User not have a Cart, we create one.
        if (!isset($cart_eq)) {
            $uuid = Uuid::uuid4();
            $cart_uuid = $uuid->toString();
            $cart_obj = new Cart("", $cart_uuid, $id, []);
            $cart = $this->add($cart_obj);
        } else {
            $cart = new Cart($cart_eq["id"], $cart_eq["uuid"], $id, []);
        }
        //Get Items:
        $items = $this->cart_items_eq_service->list($cart->uuid);
        $cart_items = [];
        if (isset($items) && is_array($items) && sizeof($items) > 0) {
            foreach ($items as $item) {
                $cart_item = new CartItem($item["id"], $item["uuid"], $item["id_cart"], $item["product_id"], $item["quantity"]);
                array_push($cart_items, $cart_item);
            }
        }
        $cart->items = $cart_items;
        return $cart;
    }

    public function list(string $id = null): array {
        $cart_items_eq = $this->cart_items_eq_service->list($id);
        $cart_items = [];
        if (isset($cart_items_eq) && is_array($cart_items_eq) && sizeof($cart_items_eq) > 0) {
            foreach ($cart_items_eq as $item) {
                $cart_item = new CartItem($item["id"], $item["uuid"], $item["id_cart"], $item["product_id"], $item["quantity"]);
                array_push($cart_items, $cart_item);
            }
        }
        return $cart_items;
    }

    public function add(Cart $cart): Cart {
        $cart_eq = new CartEq();
        if (isset($cart->id) && $cart->id !== "") $cart_eq->id = $cart->id;
        $cart_eq->uuid = $cart->uuid;
        $cart_eq->user_id = $cart->user_id;
        $cart_eq->created_at = Carbon::now();
        $cart_eq->updated_at = Carbon::now();
        $cart_eq_new = $this->cart_eq_service->add($cart_eq);
        $cart = new Cart($cart_eq_new->id, $cart_eq_new->uuid, $cart_eq_new->user_id, []);
        return $cart;
    }    

    public function update(string $id, mixed $object): void {
        //Update Cart:
        Log::info("CartRepository - update - Params => " . $id . " // " . json_encode($object));
        $cart_eq = new CartEq();
        $cart_eq->id = $object->id;
        $cart_eq->uuid = $id;
        $cart_eq->user_id = $object->user_id;
        $cart_eq->updated_at = Carbon::now();
        Log::info("CartRepository - update - Cart => " . json_encode($cart_eq));
        $this->cart_eq_service->update($id, $cart_eq);
        //Update Items:
        Log::info("CartRepository - update - Cart Items => " . json_encode($object->items));
        if (isset($object->items) && is_array($object->items) && sizeof($object->items) > 0) {
            foreach ($object->items as $item) {
                $item_eq = new CartItems();
                if (isset($item->id) && $item->id !== "") $item_eq->id =  $item->id;
                $item_eq->uuid = $item->uuid;
                $item_eq->id_cart = $id;
                $item_eq->product_id = $item->product_id;
                $item_eq->quantity = $item->quantity;
                if (isset($item->id) && $item->id !== "") {
                    $item = $this->cart_items_eq_service->update($item->uuid, $item_eq);
                } else {
                    $item_eq->created_at = Carbon::now();
                    $item_eq->updated_at = Carbon::now();
                    $item = $this->cart_items_eq_service->add($item_eq);
                }
            }
        }
    } 

    public function delete(Cart $cart): void {
        //Get Cart Items:
        $cart_items = $this->list($cart->uuid);
        //Remover all cart items:
        if (isset($cart_items) && is_array($cart_items) && sizeof($cart_items) > 0) {
            foreach ($cart_items as $item) {
                $this->delete_item($item->uuid);
            }
        }
    }

    public function delete_item(string $id): void {
        $this->cart_items_eq_service->delete($id);
    }
}
?>