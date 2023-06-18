<?php
namespace Src\App\Cart\Application\RemoveItem;

use Src\Shared\Request\RequestId;
use Src\App\Cart\Infrastructure\Persitence\CartItemsRepository;

class RemoveItemFromCart {
    private CartItemsRepository $cart_items_repo;
    public function __construct(private readonly CartItemsRepository $cartItemsRepo) {
        $this->cart_items_repo = $cartItemsRepo;
    }

    public function remove(RequestId $request): void {
        $this->cart_items_repo->delete($request->getId());
    }

}
?>