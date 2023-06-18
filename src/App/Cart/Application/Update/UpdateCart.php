<?php
namespace Src\App\Cart\Application\Update;

use stdClass;
use Illuminate\Support\Facades\Log;
use Src\Shared\Request\RequestUpdateCart;
use Src\App\Cart\Infrastructure\Persitence\CartItemsRepository;

class UpdateCart {
    private CartItemsRepository $cart_items_repo;
    public function __construct(private readonly CartItemsRepository $cartItemsRepo) {
        $this->cart_items_repo = $cartItemsRepo;
    }

    public function update(RequestUpdateCart $request): void {
        foreach ($request->items as $item) {
            $item_obj = new stdClass();
            $item_obj->id = $item["id"];
            $item_obj->quantity = $item["quantity"];
            $this->cart_items_repo->update($item_obj->id, $item_obj);
        }
    }
}
?>