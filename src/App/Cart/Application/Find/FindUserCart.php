<?php
namespace Src\App\Cart\Application\Find;

use Src\App\Cart\Domain\Cart;
use Src\Shared\Request\RequestId;
use Illuminate\Support\Facades\Log;
use Src\Shared\Request\RequestNewCart;
use Src\App\Cart\Infrastructure\Persitence\CartRepository;
use Src\App\Cart\Infrastructure\Persitence\CartItemsRepository;

class FindUserCart {
    private CartRepository $cart_repo;
    private CartItemsRepository $cart_items_repo;
    public function __construct(private readonly CartRepository $cartRepo, private readonly CartItemsRepository $cartItemsRepo) {
        $this->cart_repo = $cartRepo;
        $this->cart_items_repo = $cartItemsRepo;
    }

    public function find(RequestId $request): Cart {
        //Get Cart;
        $cart = $this->cart_repo->get($request->getId());
        Log::info("FindUserCart - find - Cart: " . json_encode($cart));
        //If User not have a Cart, we create one.
        if (!isset($cart)) {
            $cart_obj = new Cart("", $request->getId(), []);
            $cart = $this->cart_repo->add($cart_obj);
            Log::info("FindUserCart - find - Empty Cart: " . json_encode($cart));
        }
        //Get Items:
        $items = $this->cart_items_repo->list($cart->id);
        Log::info("FindUserCart - find - Items: " . json_encode($items));
        $cart->items = $items;
        Log::info("FindUserCart - find - Final Cart: " . json_encode($cart));
        return $cart;
    }
}
?>