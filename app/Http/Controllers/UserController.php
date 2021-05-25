<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $users = User::whereHas('role', function ($query) use ($request) {
      $query->where('name', $request->type);
    })->get();
    return response()->json($users);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $this->validate($request, [
      'email' => 'required|email|unique:App\Models\User',
      'phone' => 'required|unique:App\Models\User',
    ], [
      'email.unique' => 'Esta cuenta de correo ya existe, intenta con una diferente',
      'email.email' => 'Formato de email no válido',
      'phone.unique' => 'Este número de telefono ya existe, intenta con uno diferente',
    ]);

    $user = new User($request->all());

    if ($user->save()) {
      return response()->json($this->messages['create.success'], 200);
    }
    return response()->json(['errors' => [$this->messages['create.fail']]], Response::HTTP_CONFLICT);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\User  $user
   * @return \Illuminate\Http\Response
   */
  public function show(User $user)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\User  $user
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, User $user)
  {
    $this->validate($request, [
      'email' => [
        'required',
        Rule::unique('users')->ignore($user->id),
      ],
      'phone' => [
        'required',
        Rule::unique('users')->ignore($user->id),
      ]
    ], [
      'email.unique' => 'Esta cuenta de correo ya existe',
      'email.email' => 'Formato de email no válido',
      'phone.unique' => 'Este número de telefono ya existe',
    ]);

    $user->fill($request->all());
    if ($user->save()) {
      return response()->json($this->messages['update.success'], 200);
    }
    return response()->json($this->messages['update.fail'], Response::HTTP_CONFLICT);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\User  $user
   * @return \Illuminate\Http\Response
   */
  public function destroy(User $user)
  {
    if ($user->delete()) {
      return response()->json($this->messages['delete.success'], 200);
    }
    return response()->json($this->messages['delete.fail'], Response::HTTP_CONFLICT);
  }

  public function changePassword(Request $request, User $user)
  {
    $user->password = $request->password;
    if ($user->save()) {
      return response()->json('La contraseña se actualizo correctamente', 200);
    }
    return response()->json(['message' => 'No se pudo actualizar la contraseña'], Response::HTTP_CONFLICT);
  }
}
