<?php
namespace Src\App\Cart\Domain;

use Ramsey\Uuid\Uuid;
use Src\App\Cart\Domain\Cart;
use Src\App\Cart\Domain\CartItem;
use Illuminate\Support\Facades\Log;
use Src\App\Cart\Domain\Dto\Cart as CartDto;

class Cart {
    public string $id;
    public string $uuid;
    public string $user_id;
    public array $items;
    public function __construct(string $id, string $uuid, string $user_id, array $items) {
        $this->id = $id;
        $this->uuid = $uuid;
        $this->user_id = $user_id;
        $this->items = $items;
    }

    public function find_item(string $product_id): bool {
        foreach ($this->items as $item) {
            if ($item->product_id === $product_id) return true;
        }
        return false;
    }

    public function addItemToCart(string $product_id, int $quantity): void {
        if($this->find_item($product_id)) {
            $this->update_item($product_id, $quantity);
        } else {
            $uuid = Uuid::uuid4();
            $item_uuid = $uuid->toString();
            $item = new CartItem("", $item_uuid, $this->id, $product_id, $quantity);
            array_push($this->items, $item);
        }
    }

    public function update(array $items): void {
        foreach ($items as $item) {
            Log::info("Cart - update - Item to Update => " . json_encode($item));
            foreach ($this->items as $cart_item) {
                Log::info("Cart - update - Cart Item => " . json_encode($cart_item));
                if ($item["id"] === $cart_item->uuid) {
                    $cart_item->quantity = $item["quantity"];
                }
            }
        }
    }

    public function update_item(string $product_id, int $quantity): void {
        foreach ($this->items as $item) {
            if ($item->product_id === $product_id) {
                $item->quantity += $quantity;
            }
        }
    }    

    public function removeItemFromCart(string $id_item): void {
        foreach($this->items as $i => $item) {
            if($item->uuid === $id_item) {
                unset($this->items[$i]);
                break;
            }
        }
    }

    public function clearCart(): void {
        $this->items = [];
    }

}
?>