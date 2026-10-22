<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('is_logged_in')) {
            return redirect()->to(base_url('admin/dashboard'));
        }

        if ($this->request->is('post')) {
            $email    = trim((string) $this->request->getPost('email'));
            $password = (string) $this->request->getPost('password');

            if (empty($email) || empty($password)) {
                return redirect()->back()->withInput()->with('error', 'Email dan password wajib diisi.');
            }

            $userModel = new UserModel();
            $user = $userModel->findByEmail($email);

            if (!$user || !password_verify($password, $user['password_hash'])) {
                return redirect()->back()->withInput()->with('error', 'Email atau password yang Anda masukkan salah.');
            }

            // Set session
            session()->set([
                'user_id'      => $user['id'],
                'user_name'    => $user['nama'],
                'user_email'   => $user['email'],
                'user_role'    => $user['role'],
                'is_logged_in' => true,
            ]);

            return redirect()->to(base_url('admin/dashboard'))->with('success', 'Selamat datang kembali, ' . esc($user['nama']) . '!');
        }

        return view('admin/auth/login', [
            'title' => 'Login Admin — kosKu'
        ]);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('admin/login'))->with('success', 'Anda telah berhasil logout.');
    }
}
