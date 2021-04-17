<?php

namespace App\Http\Controllers;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
  public function login(Request $request)
  {
    $credentials = $request->only('email', 'password');
    if (!auth()->attempt($credentials)) {
      throw new AuthenticationException();
    }

    /**
     * Optional force regenerate de session Id
     */
    // $request->session()->regenerate();
    return response()->json(null, 201);
  }

  public function logout()
  {
    auth()->logout();
    if (!auth()->check()) {
      return response()->json('Sesión cerrada correctamente', 200);
    }
    return response()->json(['message' => 'No se pudo cerrar la sesión'], Response::HTTP_CONFLICT);
  }
}
