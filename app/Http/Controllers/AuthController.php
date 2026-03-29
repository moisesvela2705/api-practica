<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // ================================================================
    // MÉTODO: register()
    // Registra un nuevo usuario en la base de datos
    // Endpoint: POST /api/register
    // ================================================================
    public function register(Request $request)
    {
        // Validamos los datos recibidos.
        // Si alguno falla, Laravel devuelve automáticamente un error 422
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            // 'email' debe ser único en la tabla 'users'
            'email'    => 'required|string|email|max:255|unique:users',
            // 'confirmed' exige que el campo 'password_confirmation' coincida
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Creamos el usuario en la base de datos.
        // El campo 'password' se encripta automáticamente
        // gracias al cast 'hashed' que pusimos en el modelo User.
        $user = User::create($validated);

        // Generamos un token de acceso para el usuario recién creado.
        // 'auth_token' es solo el nombre que le damos al token (puede ser cualquiera).
        // Analogía: es como imprimir la tarjeta de acceso del nuevo empleado.
        $token = $user->createToken('auth_token')->plainTextToken;

        // Devolvemos el usuario y su token.
        // El código 201 significa 'Creado exitosamente'.
        return response()->json([
            'success' => true,
            'message' => 'Usuario registrado exitosamente',
            'data'    => [
                'user'         => $user,
                'access_token' => $token,
                'token_type'   => 'Bearer',
            ],
        ], 201);
    }

    // ================================================================
    // MÉTODO: login()
    // Verifica credenciales y devuelve un token si son correctas
    // Endpoint: POST /api/login
    // ================================================================
    public function login(Request $request)
    {
        // Validamos que vengan email y contraseña
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Buscamos el usuario por su email en la base de datos
        $user = User::where('email', $request->email)->first();

        // Verificamos que el usuario exista Y que la contraseña sea correcta.
        // Hash::check() compara la contraseña ingresada con el hash guardado.
        // NUNCA comparamos contraseñas en texto plano por seguridad.
        if (! $user || ! Hash::check($request->password, $user->password)) {
            // Si las credenciales son incorrectas, lanzamos un error de validación.
            // Este error devuelve automáticamente un status HTTP 422.
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        // Creamos un nuevo token para esta sesión.
        // Analogía: el usuario llega a la recepción, muestra su credencial
        // (email + password). El portero la verifica y le entrega una tarjeta
        // de acceso temporal (token).
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Inicio de sesión exitoso',
            'data'    => [
                'user'         => $user,
                'access_token' => $token,
                'token_type'   => 'Bearer',
            ],
        ]);
    }

    // ================================================================
    // MÉTODO: logout()
    // Revoca el token actual del usuario (cierra la sesión)
    // Endpoint: POST /api/logout
    // Esta ruta sí está protegida: requiere un token válido para acceder
    // ================================================================
    public function logout(Request $request)
    {
        // currentAccessToken() obtiene el token que el usuario envió en la petición.
        // delete() lo elimina de la tabla personal_access_tokens.
        // Analogía: el usuario devuelve su tarjeta de acceso al portero.
        // A partir de ese momento, esa tarjeta ya no funciona.
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada exitosamente. El token ha sido revocado.',
        ]);
    }

    // ================================================================
    // MÉTODO: me()
    // Devuelve la información del usuario autenticado actualmente
    // Endpoint: GET /api/me
    // Ruta protegida: requiere token válido
    // ================================================================
    public function me(Request $request)
    {
        // $request->user() devuelve automáticamente el usuario dueño del token.
        // No necesitamos buscar en la BD; Laravel lo hace por nosotros.
        return response()->json([
            'success' => true,
            'data'    => $request->user(),
        ]);
    }
}
