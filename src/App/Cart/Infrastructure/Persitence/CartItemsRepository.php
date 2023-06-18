<?php
namespace Src\App\Cart\Infrastructure\Persitence;

use Src\App\Cart\Domain\CartItem;
use App\Services\CartItemsService;
use Illuminate\Support\Facades\Log;
use Src\Shared\Crud\AddServiceInterface;
use Src\Shared\Crud\ListServiceInterface;
use Src\Shared\Crud\DeleteServiceInterface;
use Src\Shared\Crud\UpdateServiceInterface;

class CartItemsRepository implements ListServiceInterface, AddServiceInterface, UpdateServiceInterface, DeleteServiceInterface {

    private CartItemsService $cart_items_eq_service;
    public function __construct(private readonly CartItemsService $cartItemsEqService) {
        $this->cart_items_eq_service = $cartItemsEqService;
    }

    public function list(string $id = null): array {
        $cart_items_eq = $this->cart_items_eq_service->list($id);
        Log::info("CartItemsService - list - Items: " . json_encode($cart_items_eq));
        $cart_items = [];
        if (isset($cart_items_eq) && is_array($cart_items_eq) && sizeof($cart_items_eq) > 0) {
            foreach ($cart_items_eq as $item) {
                $cart_item = new CartItem($item["id"], $item["id_cart"], $item["product_id"], $item["quantity"]);
                array_push($cart_items, $cart_item);
            }
        }
        Log::info("CartItemsService - list - Result: " . json_encode($cart_items));
        return $cart_items;
    }

    public function add(mixed $object): mixed {
        return $this->cart_items_eq_service->add($object);
    }

    public function update(string $id, mixed $object): mixed {
        return $this->cart_items_eq_service->update($id, $object);
    }

    public function delete(string $id): void {
        $this->cart_items_eq_service->delete($id);
    }

}
?>