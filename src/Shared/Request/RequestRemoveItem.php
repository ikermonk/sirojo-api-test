<?php
namespace Src\Shared\Request;

class RequestRemoveItem {
    public string $id;
    public string $user_id;
    public function __construct(string $id, string $user_id) {
        $this->id = $id;
        $this->user_id = $user_id;
    }

    public function validate(): bool {
        return isset($this->id) && $this->id !== "" 
            && isset($this->user_id) && $this->user_id !== "";
    }    

}
?>