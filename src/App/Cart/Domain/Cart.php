<?php
namespace Src\App\Cart\Domain;

class Cart {
    public string $id;
    public string $user_id;
    public array $items;
    public function __construct(string $id, string $user_id, array $items) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->items = $items;
    }
}
?>