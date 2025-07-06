<?php

namespace App\Core\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface CRUDActionInterface
{
    /** List Management */
    public function index(Request $request): Response;
    /** Add Item */
    public function add(Request $request): Response;

    /** Edit Item */
    public function edit(Request $request, int $id): Response;
    /** Remove Item */
    public function delete(Request $request, int $id): Response;
}