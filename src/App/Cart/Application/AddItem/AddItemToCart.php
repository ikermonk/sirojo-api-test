<?php
namespace Src\App\Cart\Application\AddItem;

use App\Models\CartItems as CartItemEq;
use Src\App\Cart\Domain\Cart;
use Src\Shared\Request\RequestAddItem;
use Src\App\Cart\Infrastructure\Persitence\CartItemsRepository;

class AddItemToCart {
    private CartItemsRepository $cart_items_repo;
    public function __construct(private readonly CartItemsRepository $cartItemsRepo) {
        $this->cart_items_repo = $cartItemsRepo;
    }

    public function add(RequestAddItem $request): void {
        $cart_items = $this->cart_items_repo->list($request->id_cart);
        $finded_item = $this->find_line($cart_items, $request->product_id);
        if(isset($finded_item)) {
            $finded_item->quantity += $request->quantity;
            $item = $finded_item;
            $this->cart_items_repo->update($item->id, $item);
        } else {
            $item = new CartItemEq();
            $item->id_cart = $request->id_cart;
            $item->product_id = $request->product_id;
            $item->quantity = $request->quantity;
            $this->cart_items_repo->add($item);
        }
    }

    private function find_line(array $items, string $product_id): mixed {
        if (isset($items) && is_array($items) && sizeof($items) > 0) {
            foreach ($items as $item) {
                if ($item->product_id === $product_id) return $item;
            }
        }
        return null;
    }

}
?>