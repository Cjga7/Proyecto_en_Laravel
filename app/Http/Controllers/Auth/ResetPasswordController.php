<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Validation\Rules\Password as PasswordRule;

class ResetPasswordController extends Controller
{
    /**
     * Mostrar el formulario de restablecimiento de contraseña.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * Redirigir al panel después de restablecer la contraseña.
     *
     * @return string
     */
    protected function redirectPath()
    {
        return redirect()->route('panel');
    }

    /**
     * Enviar el enlace para restablecer la contraseña.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        // Validar que el email esté presente y sea válido
        $request->validate([
            'email' => 'required|email',
        ]);

        // Verificar si el correo existe en la base de datos
        if (!User::where('email', $request->email)->exists()) {
            return back()->withErrors(['email' => 'El correo electrónico no está registrado en nuestro sistema.']);
        }

        // Enviar el enlace de restablecimiento de contraseña
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Restablecer la contraseña del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request)
    {
        // Validar los campos del formulario de restablecimiento
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', PasswordRule::min(8)->letters()->numbers()],
        ]);

        // Intentar restablecer la contraseña
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                // Guardar la nueva contraseña del usuario
                $user->password = Hash::make($password);
                $user->save();

                // Autenticar al usuario automáticamente después de restablecer la contraseña
                Auth::login($user);
            }
        );

        // Redirigir en función del resultado
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('panel')->with('status', __($status))  // Redirigir al panel en caso de éxito
            : back()->withErrors(['email' => [__($status)]]);
    }
}
