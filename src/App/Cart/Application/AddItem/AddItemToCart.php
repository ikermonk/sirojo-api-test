<?php
namespace Src\App\Cart\Application\AddItem;

use Ramsey\Uuid\Uuid;
use Src\App\Cart\Domain\Dto\Cart;
use Illuminate\Support\Facades\Log;
use Src\Shared\Request\RequestAddItem;
use App\Models\CartItems as CartItemEq;
use Src\App\Cart\Domain\Transform\CartTransform;
use Src\App\Cart\Infrastructure\Persitence\CartRepository;
use Src\App\Cart\Infrastructure\Persitence\CartItemsRepository;

class AddItemToCart {
    private CartRepository $cart_repo;
    private CartItemsRepository $cart_items_repo;
    private CartTransform $cart_transform;
    public function __construct(private readonly CartRepository $cartRepo, private readonly CartItemsRepository $cartItemsRepo, 
    private readonly CartTransform $cartTransform) {
        $this->cart_repo = $cartRepo;
        $this->cart_items_repo = $cartItemsRepo;
        $this->cart_transform = $cartTransform;
    }

    public function add(RequestAddItem $request): Cart {
        //Get Cart:
        Log::info("AddItemToCart - add - Request => " . json_encode($request));
        $cart = $this->cart_repo->get($request->user_id, "user");
        Log::info("AddItemToCart - add - Cart => " . json_encode($cart));
        //Add Item or Update Item in Cart:
        $cart->addItemToCart($request->product_id, $request->quantity);
        Log::info("AddItemToCart - add - Cart Item Added => " . json_encode($cart));
        //Update Cart and Items in BBDD:
        $this->cart_repo->update($cart->uuid, $cart);
        Log::info("AddItemToCart - add - Cart Update => " . json_encode($cart));
        //Return Cart:
        return $this->cart_transform->transform($cart);
    }
}
?>