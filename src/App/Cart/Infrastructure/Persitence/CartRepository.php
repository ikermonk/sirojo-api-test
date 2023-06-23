<?php
namespace Src\App\Cart\Infrastructure\Persitence;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
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
    
    public function get(string $id, string $by = null): mixed {
        //Get Cart Info:
        $cart_eq = $this->cart_eq_service->get($id, $by);
        //If User not have a Cart, we create one.
        if (!isset($cart_eq)) {
            $uuid = Uuid::uuid4();
            $cart_uuid = $uuid->toString();
            $cart_obj = new Cart("", $cart_uuid, $id, []);
            $cart = $this->add($cart_obj);
        } else {
            $cart = new Cart($cart_eq["id"], $cart_eq["uuid"], $id, []);
        }
        //Get Items:
        $items = $this->cart_items_repo->list($cart->uuid);
        $cart->items = $items;
        return $cart;
    }

    public function add(Cart $cart): Cart {
        $cart_eq = new CartEq();
        if (isset($cart->id) && $cart->id !== "") $cart_eq->id = $cart->id;
        $cart_eq->uuid = $cart->uuid;
        $cart_eq->user_id = $cart->user_id;
        $cart_eq->created_at = Carbon::now();
        $cart_eq->updated_at = Carbon::now();
        $cart_eq_new = $this->cart_eq_service->add($cart_eq);
        $cart = new Cart($cart_eq_new->id, $cart_eq_new->uuid, $cart_eq_new->user_id, []);
        return $cart;
    } 
}
?>