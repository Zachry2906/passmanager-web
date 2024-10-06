<?php
include 'koneksi.php';
include 'enkripsi.php';
require_once 'session.php';

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: logout.php');
    exit();
}

if (isset($_GET['export'])) {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="password_manager_export.xls"');
    header('Cache-Control: max-age=0');
    
    echo "ID\tSitus\tUsername\tPassword\tNotes\tDibuat pada\tDiupdate pada\n";
    
    $sql = "SELECT * FROM passwords";
    $result = mysqli_query($conn, $sql);
    
    while ($row = mysqli_fetch_assoc($result)) {
        echo $row['id'] . "\t";
        echo $row['website'] . "\t";
        echo $row['username'] . "\t";
        echo vigenereDecrypt($row['password'], "akubahkanlupadengannya") . "\t";
        echo $row['notes'] . "\t";
        echo $row['created_at'] . "\t";
        echo $row['updated_at'] . "\n";
    }
    
    exit();
}

$sql = "SELECT * FROM passwords ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Password Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --danger-color: #e74a3b;
        }

        body {
            background: #f8f9fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            padding: 1rem;
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
            margin-bottom: 1.5rem;
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem 1.25rem;
            border-radius: 15px 15px 0 0 !important;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            border-bottom: 2px solid #e3e6f0;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            color: #4e73df;
            background: #f8f9fc;
        }

        .btn {
            border-radius: 10px;
            padding: 0.5rem 1rem;
            font-weight: 600;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            border: none;
        }

        .btn-success {
            background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
            border: none;
        }

        .btn-danger {
            background: linear-gradient(135deg, #e74a3b 0%, #be2617 100%);
            border: none;
        }

        .btn-warning {
            background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);
            border: none;
            color: white;
        }

        .stats-card {
            border-left: 4px solid #4e73df;
            margin-bottom: 1.5rem;
        }

        .stats-card.primary {
            border-left-color: #4e73df;
        }

        .stats-card.success {
            border-left-color: #1cc88a;
        }

        .stats-card.warning {
            border-left-color: #f6c23e;
        }

        .stats-card .h5 {
            color: #5a5c69;
            text-transform: uppercase;
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }

        .stats-card .stat-value {
            color: #5a5c69;
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0;
        }

        .password-cell {
            position: relative;
        }

        .password-toggle {
            cursor: pointer;
            color: #4e73df;
        }

        .search-input {
            border-radius: 10px;
            border: 1px solid #e3e6f0;
            padding: 0.5rem 1rem;
            margin-bottom: 1rem;
        }

        .table-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="fas fa-lock me-2"></i>Password Manager</a>
            <div class="ms-auto">
                <a href="?logout" class="btn btn-light"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card stats-card primary h-100 py-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="h5 mb-0 font-weight-bold text-gray-800">Total Passwords</div>
                                <div class="stat-value"><?php echo mysqli_num_rows($result); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-key fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Add more stats cards as needed -->
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-table me-2"></i>Password Database</h5>
            </div>
            <div class="card-body">
                <div class="table-controls">
                    <div>
                        <a href="add.php" class="btn btn-primary me-2">
                            <i class="fas fa-plus me-2"></i>Tambah Password
                        </a>
                        <a href="?export" class="btn btn-success">
                            <i class="fas fa-file-export me-2"></i>Export Excel
                        </a>
                    </div>
                    <div class="search-box">
                        <input type="text" id="searchInput" class="form-control search-input" placeholder="Cari...">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover" id="passwordTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Situs</th>
                                <th>Username</th>
                                <th>Password</th>
                                <th>Notes</th>
                                <th>Dibuat pada</th>
                                <th>Diupdate pada</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td>
                                        <i class="fas fa-globe me-2"></i>
                                        <?php echo $row['website']; ?>
                                    </td>
                                    <td>
                                        <i class="fas fa-user me-2"></i>
                                        <?php echo $row['username']; ?>
                                    </td>
                                    <td class="password-cell">
                                        <span class="password-hidden">••••••••</span>
                                        <span class="password-visible d-none">
                                            <?php echo vigenereDecrypt($row['password'], "akubahkanlupadengannya"); ?>
                                        </span>
                                        <i class="fas fa-eye password-toggle ms-2"></i>
                                    </td>
                                    <td><?php echo $row['notes']; ?></td>
                                    <td><i class="fas fa-calendar me-2"></i><?php echo $row['created_at']; ?></td>
                                    <td><i class="fas fa-clock me-2"></i><?php echo $row['updated_at']; ?></td>
                                    <td>
                                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning me-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger delete-btn" data-id="<?php echo $row['id']; ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password Toggle
            document.querySelectorAll('.password-toggle').forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const cell = this.closest('.password-cell');
                    const hidden = cell.querySelector('.password-hidden');
                    const visible = cell.querySelector('.password-visible');
                    
                    hidden.classList.toggle('d-none');
                    visible.classList.toggle('d-none');
                    this.classList.toggle('fa-eye');
                    this.classList.toggle('fa-eye-slash');
                });
            });

            // Search Functionality
            document.getElementById('searchInput').addEventListener('keyup', function() {
                const searchText = this.value.toLowerCase();
                const table = document.getElementById('passwordTable');
                const rows = table.getElementsByTagName('tr');

                for (let i = 1; i < rows.length; i++) {
                    const row = rows[i];
                    const cells = row.getElementsByTagName('td');
                    let found = false;

                    for (let j = 0; j < cells.length; j++) {
                        const cellText = cells[j].textContent.toLowerCase();
                        if (cellText.includes(searchText)) {
                            found = true;
                            break;
                        }
                    }

                    row.style.display = found ? '' : 'none';
                }
            });

            // Delete Confirmation
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e74a3b',
                        cancelButtonColor: '#858796',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = `hapus.php?id=${id}`;
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>