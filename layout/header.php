<?php
if (!isset($judul)) {
    $judul = 'Aplikasi Sekolah';
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($judul); ?></title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f4f4;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 230px;
            background: #2c3e50;
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            overflow-y: auto;
        }

        .sidebar h3 {
            text-align: center;
            padding: 20px 10px;
            border-bottom: 1px solid #34495e;
            font-size: 18px;
        }

        .sidebar ul {
            list-style: none;
            padding: 10px 0;
        }

        .sidebar ul li a {
            display: block;
            padding: 11px 20px;
            color: #ecf0f1;
            text-decoration: none;
            font-size: 15px;
        }

        .sidebar ul li a:hover,
        .sidebar ul li a.active {
            background: #34495e;
        }

        .sidebar ul li.sub a {
            padding: 8px 20px 8px 40px;
            font-size: 13px;
            color: #bdc3c7;
        }

        .content {
            margin-left: 230px;
            padding: 30px;
            flex: 1;
        }

        .card {
            background: #fff;
            padding: 25px;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .12);
        }

        h2 {
            margin-bottom: 20px;
            color: #2c3e50;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            background: #fff;
            margin-bottom: 15px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 9px 12px;
            text-align: left;
            font-size: 14px;
        }

        table th {
            background: #34495e;
            color: #fff;
        }

        label {
            font-weight: bold;
            font-size: 14px;
        }

        input[type=text],
        input[type=date],
        input[type=number],
        input[type=file],
        select {
            padding: 8px;
            width: 100%;
            max-width: 400px;
            margin: 5px 0 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        .btn {
            display: inline-block;
            background: #3498db;
            color: #fff;
            border: 0;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .btn:hover {
            background: #2980b9;
        }

        .btn-danger {
            background: #e74c3c;
        }

        .btn-danger:hover {
            background: #c0392b;
        }
    </style>
</head>

<body>
    <aside class="sidebar">
        <h3>Aplikasi Sekolah</h3>
        <ul>
            <li><a href="<?= BASE_URL; ?>/index.php">Dashboard</a></li>
            <li><a href="<?= BASE_URL; ?>/siswa/tampil.php">Data Siswa</a></li>
            <li class="sub"><a href="<?= BASE_URL; ?>/siswa/tampil.php">Lihat Data Siswa</a></li>
            <li class="sub"><a href="<?= BASE_URL; ?>/siswa/index.php">Tambah Data Siswa</a></li>
            <li><a href="<?= BASE_URL; ?>/guru/tampil.php">Data Guru</a></li>
            <li class="sub"><a href="<?= BASE_URL; ?>/guru/tampil.php">Lihat Data Guru</a></li>
            <li class="sub"><a href="<?= BASE_URL; ?>/guru/index.php">Tambah Data Guru</a></li>
        </ul>
    </aside>
    <main class="content">
        <div class="card">
