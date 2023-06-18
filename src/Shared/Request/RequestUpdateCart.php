<?php
namespace Src\Shared\Request;

use Illuminate\Support\Facades\Log;

class RequestUpdateCart {
    public string $id_cart;
    public array $items;
    public function __construct(string $id_cart, array $items) {
        $this->id_cart = $id_cart;
        $this->items = $items;
    }

    public function validate(): bool {
        Log::info("Data => " . $this->id_cart . " // " . json_encode($this->items));
        return isset($this->id_cart) && $this->id_cart !== ""
            && isset($this->items) && is_array($this->items) && sizeof($this->items);
    }    

}
?>