<?php
namespace Src\Shared\Crud;

use Src\Shared\Crud\AddServiceInterface;
use Src\Shared\Crud\GetServiceInterface;
use Src\Shared\Crud\ListServiceInterface;
use Src\Shared\Crud\DeleteServiceInterface;
use Src\Shared\Crud\UpdateServiceInterface;

interface CrudServiceInterface extends GetServiceInterface, ListServiceInterface, AddServiceInterface, UpdateServiceInterface, DeleteServiceInterface {
}

?> 