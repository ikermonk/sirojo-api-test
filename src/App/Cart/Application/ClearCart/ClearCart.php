<?php
namespace Src\App\Cart\Application\ClearCart;

use Src\App\Cart\Domain\Dto\Cart;
use Src\Shared\Request\RequestId;
use Illuminate\Support\Facades\Log;
use Src\App\Cart\Domain\Transform\CartTransform;
use Src\App\Cart\Infrastructure\Persitence\CartRepository;
use Src\App\Cart\Infrastructure\Persitence\CartItemsRepository;

class ClearCart {
    private CartRepository $cart_repo;
    private CartItemsRepository $cart_items_repo;
    private CartTransform $cart_transform;
    public function __construct(private readonly CartRepository $cartRepo, private readonly CartItemsRepository $cartItemsRepo, 
    private readonly CartTransform $cartTransform) {
        $this->cart_repo = $cartRepo;
        $this->cart_items_repo = $cartItemsRepo;
        $this->cart_transform = $cartTransform;
    }

    public function clear(RequestId $request): Cart {
        //Get Cart:
        Log::info("ClearCart - clear - Request => " . json_encode($request));
        $cart = $this->cart_repo->get($request->getId(), "");
        Log::info("ClearCart - clear - Cart => " . json_encode($cart));
        //Update BBDD, removing all Cart Items:
        foreach ($cart->items as $item) {
            $this->cart_items_repo->delete($item->uuid);
        }
        //Clear Cart:
        $cart->clearCart();
        Log::info("ClearCart - clear - Cart Cleared => " . json_encode($cart));
        //Return Cart:
        return $this->cart_transform->transform($cart);
    }
}
?>