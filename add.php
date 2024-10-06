<?php
include 'koneksi.php';
include 'enkripsi.php';
require_once 'session.php';

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $website = $_POST['website'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password = vigenereEncrypt($password, "akubahkanlupadengannya");
    $notes = $_POST['notes'];

    $sql = "INSERT INTO passwords (website, username, password, notes) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ssss', $website, $username, $password, $notes);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Password Baru - Password Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
        }

        body {
            background: #f8f9fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            padding: 1rem;
            margin-bottom: 2rem;
        }

        .navbar-brand {
            color: white !important;
            font-weight: 700;
            font-size: 1.5rem;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #e3e6f0;
            padding: 1.5rem;
            border-radius: 15px 15px 0 0 !important;
        }

        .card-body {
            padding: 1.5rem;
        }

        .form-control {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            border: 2px solid #e3e6f0;
            transition: all 0.2s ease-in-out;
        }

        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        .form-label {
            font-weight: 600;
            color: #5a5c69;
            margin-bottom: 0.5rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.2s ease-in-out;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            border: none;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #858796 0%, #60616f 100%);
            border: none;
            color: white;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .input-group-text {
            background: transparent;
            border: 2px solid #e3e6f0;
            border-left: none;
        }

        .password-toggle {
            cursor: pointer;
            padding: 0.75rem 1rem;
            color: #4e73df;
        }

        .generate-password {
            cursor: pointer;
            color: #4e73df;
            font-size: 0.9rem;
            text-decoration: none;
        }

        .generate-password:hover {
            text-decoration: underline;
        }

        .password-strength {
            height: 5px;
            border-radius: 5px;
            margin-top: 0.5rem;
            background: #e3e6f0;
        }

        .password-strength-bar {
            height: 100%;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .strength-weak { width: 33.33%; background: #e74a3b; }
        .strength-medium { width: 66.66%; background: #f6c23e; }
        .strength-strong { width: 100%; background: #1cc88a; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-lock me-2"></i>Password Manager
            </a>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Password Baru
                </h4>
            </div>
            <div class="card-body">
                <form action="add.php" method="post" id="passwordForm">
                    <div class="mb-4">
                        <label for="website" class="form-label">
                            <i class="fas fa-globe me-2"></i>Situs
                        </label>
                        <input type="text" class="form-control" id="website" name="website" required 
                               placeholder="Contoh: example.com">
                    </div>
                    <div class="mb-4">
                        <label for="username" class="form-label">
                            <i class="fas fa-user me-2"></i>Username
                        </label>
                        <input type="text" class="form-control" id="username" name="username" required
                               placeholder="Masukkan username atau email">
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">
                            <i class="fas fa-key me-2"></i>Password
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required
                                   placeholder="Masukkan password">
                            <span class="input-group-text">
                                <i class="fas fa-eye password-toggle"></i>
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <a href="#" class="generate-password">
                                <i class="fas fa-random me-1"></i>Generate Password
                            </a>
                            <small class="text-muted">Kekuatan Password: <span id="strengthText">-</span></small>
                        </div>
                        <div class="password-strength">
                            <div class="password-strength-bar" id="strengthBar"></div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="notes" class="form-label">
                            <i class="fas fa-sticky-note me-2"></i>Catatan
                        </label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" 
                                 placeholder="Tambahkan catatan tambahan (opsional)"></textarea>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="dashboard.php" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password Toggle
            const passwordToggle = document.querySelector('.password-toggle');
            const passwordInput = document.querySelector('#password');
            
            passwordToggle.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });

            // Password Generator
            const generateButton = document.querySelector('.generate-password');
            generateButton.addEventListener('click', function(e) {
                e.preventDefault();
                const length = 16;
                const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+";
                let password = "";
                for (let i = 0; i < length; i++) {
                    password += charset.charAt(Math.floor(Math.random() * charset.length));
                }
                passwordInput.value = password;
                checkPasswordStrength(password);
            });

            // Password Strength Checker
            function checkPasswordStrength(password) {
                const strengthBar = document.querySelector('#strengthBar');
                const strengthText = document.querySelector('#strengthText');
                
                let strength = 0;
                if (password.length >= 8) strength++;
                if (password.match(/[A-Z]/)) strength++;
                if (password.match(/[0-9]/)) strength++;
                if (password.match(/[^A-Za-z0-9]/)) strength++;

                switch(strength) {
                    case 0:
                    case 1:
                        strengthBar.className = 'password-strength-bar strength-weak';
                        strengthText.textContent = 'Lemah';
                        break;
                    case 2:
                    case 3:
                        strengthBar.className = 'password-strength-bar strength-medium';
                        strengthText.textContent = 'Sedang';
                        break;
                    case 4:
                        strengthBar.className = 'password-strength-bar strength-strong';
                        strengthText.textContent = 'Kuat';
                        break;
                }
            }

            passwordInput.addEventListener('input', function() {
                checkPasswordStrength(this.value);
            });
        });
    </script>
</body>
</html>