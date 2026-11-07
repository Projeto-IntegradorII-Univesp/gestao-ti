<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors(['email' => 'E-mail ou senha incorretos.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function perfil()
    {
        $user = Auth::user();
        return view('auth.perfil', compact('user'));
    }

    public function updatePerfil(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:usuarios,email,' . $user->id,
            'cargo' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        if ($request->hasFile('foto')) {
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            $user->foto = $request->file('foto')->store('perfis', 'public');
        }

        $user->nome = $request->name;
        $user->email = $request->email;
        $user->cargo = $request->cargo;
        $user->nivel_acesso = ($request->cargo === 'Técnico de TI / Administrador') ? 'admin' : 'funcionario';

        if ($request->filled('password')) {
            $user->senha = Hash::make($request->password);
        }

        $user->save();

        return back()->with('sucesso', 'Dados do perfil atualizados com sucesso!');
    }

    // --- PAINEL DE GESTÃO DE USUÁRIOS (SOMENTE ADMIN) ---

    public function usuariosIndex()
    {
        $user = Auth::user();
        if ($user->tipo !== 'admin' && $user->nivel_acesso !== 'admin') {
            return redirect()->route('dashboard')->with('erro', 'Acesso negado. Apenas administradores podem acessar esta área.');
        }

        $usuarios = User::orderBy('nome', 'asc')->get();
        return view('auth.usuarios', compact('usuarios'));
    }

    public function usuarioStore(Request $request)
    {
        $user = Auth::user();
        if ($user->tipo !== 'admin' && $user->nivel_acesso !== 'admin') {
            return redirect()->route('dashboard')->with('erro', 'Acesso negado.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|string|min:3',
            'cargo' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $caminhoFoto = null;
        if ($request->hasFile('foto')) {
            $caminhoFoto = $request->file('foto')->store('perfis', 'public');
        }

        User::create([
            'nome' => $request->name,
            'email' => $request->email,
            'senha' => Hash::make($request->password),
            'cargo' => $request->cargo,
            'nivel_acesso' => ($request->cargo === 'Técnico de TI / Administrador') ? 'admin' : 'funcionario',
            'foto' => $caminhoFoto
        ]);

        return back()->with('sucesso', 'Novo usuário cadastrado com sucesso!');
    }

    public function usuarioUpdate(Request $request, $id)
    {
        $admin = Auth::user();
        if ($admin->tipo !== 'admin' && $admin->nivel_acesso !== 'admin') {
            return back()->with('erro', 'Acesso negado. Apenas administradores podem editar usuários.');
        }

        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:usuarios,email,' . $id,
            'cargo' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'password' => 'nullable|string|min:3'
        ]);

        if ($request->hasFile('foto')) {
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            $user->foto = $request->file('foto')->store('perfis', 'public');
        }

        $user->nome = $request->name;
        $user->email = $request->email;
        $user->cargo = $request->cargo;
        $user->nivel_acesso = ($request->cargo === 'Técnico de TI / Administrador') ? 'admin' : 'funcionario';

        if ($request->filled('password')) {
            $user->senha = Hash::make($request->password);
        }

        $user->save();

        return back()->with('sucesso', "Usuário {$user->nome} atualizado com sucesso!");
    }

    public function usuarioDestroy($id)
    {
        $admin = Auth::user();
        if ($admin->tipo !== 'admin' && $admin->nivel_acesso !== 'admin') {
            return redirect()->route('dashboard')->with('erro', 'Acesso negado.');
        }

        if (Auth::id() == $id) {
            return back()->with('erro', 'Você não pode excluir a sua própria conta!');
        }

        $user = User::findOrFail($id);
        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }

        $user->delete();

        return back()->with('sucesso', 'Usuário removido com sucesso!');
    }

    public function usuarioResetSenha($id)
    {
        $admin = Auth::user();
        if ($admin->tipo !== 'admin' && $admin->nivel_acesso !== 'admin') {
            return redirect()->route('dashboard')->with('erro', 'Acesso negado.');
        }

        $user = User::findOrFail($id);
        $user->senha = Hash::make('123456');
        $user->save();

        return back()->with('sucesso', "A senha do usuário {$user->nome} foi resetada para '123456' com sucesso!");
    }
}