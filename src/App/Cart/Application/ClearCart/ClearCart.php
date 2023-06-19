<?php
namespace Src\App\Cart\Application\ClearCart;

use Src\App\Cart\Domain\Cart;
use Src\Shared\Request\RequestClearCart;
use Src\App\Cart\Infrastructure\Persitence\CartRepository;
use Src\App\Cart\Infrastructure\Persitence\CartItemsRepository;

class ClearCart {
    private CartRepository $cart_repo;
    private CartItemsRepository $cart_items_repo;
    public function __construct(private readonly CartRepository $cartRepo, private readonly CartItemsRepository $cartItemsRepo) {
        $this->cart_repo = $cartRepo;
        $this->cart_items_repo = $cartItemsRepo;
    }

    public function clear(RequestClearCart $request): Cart {
        foreach ($request->items as $item) {
            $this->cart_items_repo->delete($item);
        }
        return $this->cart_repo->get($request->user_id);
    }
}
?>