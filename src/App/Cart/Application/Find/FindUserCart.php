<?php
namespace Src\App\Cart\Application\Find;

use Src\App\Cart\Domain\Dto\Cart;
use Src\Shared\Request\RequestId;
use Illuminate\Support\Facades\Log;
use Src\App\Cart\Domain\Transform\CartTransform;
use Src\App\Cart\Infrastructure\Persitence\CartRepository;

class FindUserCart {
    private CartRepository $cart_repo;
    private CartTransform $cart_transform;
    public function __construct(private readonly CartRepository $cartRepo, private readonly CartTransform $cartTransform) {
        $this->cart_repo = $cartRepo;
        $this->cart_transform = $cartTransform;
    }

    public function find(RequestId $request): Cart {
        //Get Cart:
        Log::info("FindUserCart - find - Request => " . json_encode($request));
        $cart = $this->cart_repo->get($request->getId(), $request->getBy());
        Log::info("FindUserCart - find - Cart => " . json_encode($cart));
        //Return Cart DTO:
        return $this->cart_transform->transform($cart);
    }
}
?>