<?php
namespace Src\App\Cart\Application\RemoveItem;

use Src\App\Cart\Domain\Dto\Cart;
use Src\Shared\Request\RequestRemoveItem;
use Src\App\Cart\Domain\Transform\CartTransform;
use Src\App\Cart\Infrastructure\Persitence\CartRepository;

class RemoveItemFromCart {
    private CartRepository $cart_repo;
    private CartTransform $cart_transform;
    public function __construct(private readonly CartRepository $cartRepo, private readonly CartTransform $cartTransform) {
        $this->cart_repo = $cartRepo;
        $this->cart_transform = $cartTransform;
    }

    public function remove(RequestRemoveItem $request): Cart {
        //Get Cart:
        $cart = $this->cart_repo->get($request->id_cart, "");
        //Remove item from Cart:
        $cart->removeItemFromCart($request->id);
        //Update BBDD:
        $this->cart_repo->delete_item($request->id);
        //Return Cart Dto:
        return $this->cart_transform->transform($cart);
    }

}
?>