<?php
namespace Src\App\Cart\Application\ClearCart;

use Src\App\Cart\Domain\Dto\Cart;
use Src\Shared\Request\RequestId;
use Illuminate\Support\Facades\Log;
use Src\App\Cart\Domain\Transform\CartTransform;
use Src\App\Cart\Infrastructure\Persitence\CartRepository;

class ClearCart {
    private CartRepository $cart_repo;
    private CartTransform $cart_transform;
    public function __construct(private readonly CartRepository $cartRepo, private readonly CartTransform $cartTransform) {
        $this->cart_repo = $cartRepo;
        $this->cart_transform = $cartTransform;
    }

    public function clear(RequestId $request): Cart {
        //Get Cart:
        Log::info("ClearCart - clear - Request => " . json_encode($request));
        $cart = $this->cart_repo->get($request->getId(), "");
        Log::info("ClearCart - clear - Cart => " . json_encode($cart));
        //Clear Cart:
        $cart->clearCart();
        //Update BBDD, removing all Cart Items:
        $this->cart_repo->delete($cart);
        Log::info("ClearCart - clear - Cart Cleared => " . json_encode($cart));
        //Return Cart:
        return $this->cart_transform->transform($cart);
    }
}
?>