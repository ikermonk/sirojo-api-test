<?php
namespace Src\App\Cart\Application\ClearCart;

use Src\Shared\Request\RequestClearCart;
use Src\App\Cart\Infrastructure\Persitence\CartItemsRepository;

class ClearCart {
    private CartItemsRepository $cart_items_repo;
    public function __construct(private readonly CartItemsRepository $cartItemsRepo) {
        $this->cart_items_repo = $cartItemsRepo;
    }

    public function clear(RequestClearCart $request): void {
        foreach ($request->items as $item) {
            $this->cart_items_repo->delete($item);
        }
    }
}
?>