<?php
namespace Src\App\Cart\Application\Update;

use stdClass;
use Src\App\Cart\Domain\Cart;
use Illuminate\Support\Facades\Log;
use Src\Shared\Request\RequestUpdateCart;
use Src\App\Cart\Infrastructure\Persitence\CartRepository;
use Src\App\Cart\Infrastructure\Persitence\CartItemsRepository;

class UpdateCart {
    private CartRepository $cart_repo;
    private CartItemsRepository $cart_items_repo;
    public function __construct(private readonly CartRepository $cartRepo, private readonly CartItemsRepository $cartItemsRepo) {
        $this->cart_repo = $cartRepo;
        $this->cart_items_repo = $cartItemsRepo;
    }

    public function update(RequestUpdateCart $request): Cart {
        foreach ($request->items as $item) {
            $item_obj = new stdClass();
            $item_obj->id = $item["id"];
            $item_obj->quantity = $item["quantity"];
            $this->cart_items_repo->update($item_obj->id, $item_obj);
        }
        return $this->cart_repo->get($request->user_id, "user");
    }
}
?>