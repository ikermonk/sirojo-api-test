<?php
namespace Src\Shared\Request;

use Illuminate\Support\Facades\Log;

class RequestId {
    public string $id;
    public string $by;
    public function __construct(string $id, string $by) {
        $this->id = $id;
        $this->by = $by;
    }

    public function getId(): string {
        return $this->id;
    }

    public function getBy(): string {
        return $this->by;
    }

    public function validate(): bool {
        return isset($this->id) && $this->id !== "";
    }    
}
?>