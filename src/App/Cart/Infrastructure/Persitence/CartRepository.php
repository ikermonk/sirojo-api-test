<?php
namespace Src\App\Cart\Infrastructure\Persitence;

use Carbon\Carbon;
use App\Services\CartService;
use Src\App\Cart\Domain\Cart;
use App\Models\Cart as CartEq;
use Illuminate\Support\Facades\Log;
use Src\Shared\Crud\GetServiceInterface;

class CartRepository implements GetServiceInterface {
    private CartService $cart_eq_service;
    public function __construct(private readonly CartService $cartEqService) {
        $this->cart_eq_service = $cartEqService;
    }
    
    public function get(string $id): mixed {
        //Get Cart Info:
        $cart_eq = $this->cart_eq_service->get($id);
        if (!isset($cart_eq)) return null;
        $cart = new Cart($cart_eq["id"], $id, []);
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