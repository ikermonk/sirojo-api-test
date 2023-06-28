<?php
namespace Src\App\Cart\Application\Update;

use stdClass;
use Src\App\Cart\Domain\Dto\Cart;
use Illuminate\Support\Facades\Log;
use Src\Shared\Request\RequestUpdateCart;
use Src\App\Cart\Domain\Transform\CartTransform;
use Src\App\Cart\Infrastructure\Persitence\CartRepository;
use Src\App\Cart\Infrastructure\Persitence\CartItemsRepository;

class UpdateCart {
    private CartRepository $cart_repo;
    private CartTransform $cart_transform;
    public function __construct(private readonly CartRepository $cartRepo, private readonly CartTransform $cartTransform) {
        $this->cart_repo = $cartRepo;
        $this->cart_transform = $cartTransform;
    }

    public function update(RequestUpdateCart $request): Cart {
        //Get Cart:
        $cart = $this->cart_repo->get($request->user_id, "user");
        //Update Cart:
        $cart->update($request->items);
        //Update Items Cart in BBDD:
        $this->cart_repo->update($cart->uuid, $cart);
        //Return CarT Dto:
        return $this->cart_transform->transform($cart);        
    }
}
?>