<?php
namespace Src\App\Cart\Domain;

use Ramsey\Uuid\Uuid;
use Src\App\Cart\Domain\Cart;
use Src\App\Cart\Domain\CartItem;
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

    public function removeItemFromCart(): Cart {
        
    }

    public function clearCart(): Cart {

    }

    private function find_item(string $product_id): bool {
        foreach ($this->items as $item) {
            if ($item->product_id === $product_id) return true;
        }
        return false;
    }

    private function update_item(string $product_id, int $quantity): void {
        foreach ($this->items as $item) {
            if ($item->product_id === $product_id) {
                $item->quantity += $quantity;
            }
        }
    }

}
?>