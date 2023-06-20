<?php
namespace Src\App\Cart\Infrastructure\Persitence;

use Carbon\Carbon;
use App\Services\CartService;
use Src\App\Cart\Domain\Cart;
use App\Models\Cart as CartEq;
use Illuminate\Support\Facades\Log;
use Src\Shared\Crud\GetServiceInterface;
use Src\App\Cart\Infrastructure\Persitence\CartItemsRepository;

class CartRepository implements GetServiceInterface {
    private CartService $cart_eq_service;
    private CartItemsRepository $cart_items_repo;
    public function __construct(private readonly CartService $cartEqService, private readonly CartItemsRepository $cartItemsRepo) {
        $this->cart_eq_service = $cartEqService;
        $this->cart_items_repo = $cartItemsRepo;
    }
    
    public function get(string $id): mixed {
        //Get Cart Info:
        $cart_eq = $this->cart_eq_service->get($id);
        //If User not have a Cart, we create one.
        if (!isset($cart_eq)) {
            $cart_obj = new Cart("", $id, []);
            $cart = $this->add($cart_obj);
        } else {
            $cart = new Cart($cart_eq["id"], $id, []);
        }
        //Get Items:
        $items = $this->cart_items_repo->list($cart->id);
        Log::info("FindUserCart - find - Items: " . json_encode($items));
        $cart->items = $items;
        Log::info("FindUserCart - find - Final Cart: " . json_encode($cart));
        return $cart;
    }

    public function add(Cart $cart): Cart {
        $cart_eq = new CartEq();
        if (isset($cart->id) && $cart->id !== "") $cart_eq->id = $cart->id;
        $cart_eq->user_id = $cart->user_id;
        $cart_eq->created_at = Carbon::now();
        $cart_eq->updated_at = Carbon::now();
        $cart_eq_new = $this->cart_eq_service->add($cart_eq);
        $cart = new Cart($cart_eq_new->id, $cart_eq_new->user_id, []);
        return $cart;
    } 
}
?>