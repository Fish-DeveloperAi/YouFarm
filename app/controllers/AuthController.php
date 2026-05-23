<?php
class AuthController extends Controller {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            $userModel = $this->model('User');
            $user = $userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['fname'] = $user['FirstName'];
                $_SESSION['lname'] = $user['LastName'];
                $_SESSION['cin'] = $user['National ID'];
                $_SESSION['title'] = $user['Property Title'];
                $_SESSION['age'] = $user['age'];

                header('Location: ' . BASE_URL . 'data');
                exit;
            } else {
                $error = 'Invalid email or password.';
                $this->view('login', [
                    'title' => 'Login - YouFarm',
                    'styles' => 'RL.css',
                    'error' => $error
                ]);
            }
        } else {
            $this->view('login', [
                'title' => 'Login - YouFarm',
                'styles' => 'RL.css'
            ]);
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'FirstName'     => $_POST['firstname'],
                'LastName'      => $_POST['lastname'],
                'email'         => $_POST['email'],
                'password'      => $_POST['password'],
                'NationalID'    => $_POST['cin'],
                'PropertyTitle' => $_POST['title'],
                'age'           => $_POST['age']
            ];

            if (empty($data['FirstName']) || empty($data['email']) || empty($data['password'])) {
                $error = 'Please fill all required fields.';
            } else {
                $userModel = $this->model('User');
                if ($userModel->register($data)) {
                    header('Location: ' . BASE_URL . 'login');
                    exit;
                } else {
                    $error = 'Registration failed. Email may already exist.';
                }
            }

            $this->view('register', [
                'title' => 'Register - YouFarm',
                'styles' => 'RL.css',
                'error' => $error ?? null
            ]);
        } else {
            $this->view('register', [
                'title' => 'Register - YouFarm',
                'styles' => 'RL.css'
            ]);
        }
    }

public function logout() {
    session_destroy();
    header('Location: ' . BASE_URL);
    exit;
}
}