<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
  public function login(Request $request)
  {
    $credentials = $request->only('phone', 'password');
    if (!auth()->attempt($credentials)) {
      throw new AuthenticationException();
    }

    /**
     * Optional force regenerate de session Id
     */
    // $request->session()->regenerate();
    return response()->json(null, 201);
  }

  public function adminLogin(Request $request)
  {
    $found = User::where([
      ['role_id', '=', 1],
      ['phone', '=', $request->phone],
    ])->exists();
    $credentials = $request->only('phone', 'password');
    if (!$found || !auth()->attempt($credentials)) {
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

  public function username()
  {
    return 'phone';
  }

  /**
   * Verifies current admin password
   */
  public function adminVerify(Request $request)
  {
    return Hash::check($request->password, auth()->user()->password)
      ? response()->json(['success' => true], 200)
      : response()->json(['message' => 'Contraseña incorrecta'], Response::HTTP_FORBIDDEN);
  }
}
