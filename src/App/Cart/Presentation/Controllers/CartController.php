<?php
namespace Src\App\Cart\Presentation\Controllers;

use Illuminate\Http\Request;
use Src\Shared\Request\RequestId;
use Illuminate\Support\Facades\Log;
use Src\Shared\Request\RequestAddItem;
use Src\App\Cart\Application\Find\FindUserCart;
use Src\App\Cart\Application\AddItem\AddItemToCart;

class CartController {
    private FindUserCart $find_cart_service;
    private AddItemToCart $add_item_to_cart_service;
    public function __construct(private readonly FindUserCart $findCartService, private readonly AddItemToCart $addItemToCartService) {
        $this->find_cart_service = $findCartService;
        $this->add_item_to_cart_service = $addItemToCartService;
    }

    public function find(string $user_id) {
        try {
            if (isset($user_id) && $user_id !== "") {
                $requestId = new RequestId($user_id);
                $cart = $this->find_cart_service->find($requestId);
                return response()->json($cart);
            }
            return response()->json([
                'message' => 'Hay un error al tratar de obtener el carrito.'
            ], 409);
        } catch (\Exception $e) {
            Log::error("CartController - find - Exception: " . $e->getMessage());
            return response()->json([
                'message' => 'Ha ocurrido un error obteniendo el carrito del usuario.'
            ], 500);
        }
    }

    public function add_item(Request $request) {
        try {
            $data = $request->all();
            Log::info("CartController - add_item - POST => " . json_encode($data));
            Log::info("CartController - add_item - POST - ID Cart => " . $data["id_cart"]);
            $requestAddItem = new RequestAddItem($data["id_cart"], $data["user_id"], $data["product_id"], $data["quantity"]);
            if ($requestAddItem->validate()) {
                $cart = $this->add_item_to_cart_service->add($requestAddItem);
                return response()->json("Baaaaaiiiiii");
            }
            return response()->json([
                'message' => 'Hay un error al tratar de añadir un producto al carrito.'
            ], 409);
        } catch (\Exception $e) {
            Log::error("CartController - add_item - Exception: " . $e->getMessage());
            return response()->json([
                'message' => 'Ha ocurrido un error al tratar de añadir una linea nueva al carrito.'
            ], 500);
        }

    }
}
?>