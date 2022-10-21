<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OmieController;

Route::get('/api/ListaContasReceber/{key}/{secret}', [OmieController::class, 'ListAccountsReceivable']);