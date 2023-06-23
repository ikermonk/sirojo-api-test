<?php
namespace Src\App\Cart\Application\RemoveItem;

use Src\App\Cart\Domain\Cart;
use Src\Shared\Request\RequestRemoveItem;
use Src\App\Cart\Infrastructure\Persitence\CartRepository;
use Src\App\Cart\Infrastructure\Persitence\CartItemsRepository;

class RemoveItemFromCart {
    private CartRepository $cart_repo;
    private CartItemsRepository $cart_items_repo;
    public function __construct(private readonly CartRepository $cartRepo, private readonly CartItemsRepository $cartItemsRepo) {
        $this->cart_repo = $cartRepo;
        $this->cart_items_repo = $cartItemsRepo;
    }

    public function remove(RequestRemoveItem $request): Cart {
        $this->cart_items_repo->delete($request->id);
        return $this->cart_repo->get($request->user_id, "user");
    }

}
?>