<?php
namespace Src\App\Cart\Domain;

class CartItem {
    public string $id;
    public string $uuid;
    public string $id_cart;
    public string $product_id;
    public int $quantity;
    public function __construct(string $id, string $uuid, string $id_cart, string $product_id, int $quantity) {
        $this->id = $id;
        $this->uuid = $uuid;
        $this->id_cart = $id_cart;
        $this->product_id = $product_id;
        $this->quantity = $quantity;
    }
}
?>