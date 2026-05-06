<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - RaiseTrack</title>
    <link rel="stylesheet" href="style.css?v=<?php echo filemtime(__DIR__ . '/style.css'); ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <input type="checkbox" id="sidebar-toggle" class="sidebar-toggle">
        <nav class="sidebar" id="sidebar">
            <div class="logo">
                <i class="fas fa-graduation-cap"></i>
                Menu
            </div>
            <ul class="nav-menu">
                <li class="nav-item active" data-page="dashboard">
                    <span class="nav-icon"><i class="fas fa-chart-bar"></i></span>
                    Dashboard
                </li>
                <li class="nav-item" data-page="students">
                    <span class="nav-icon"><i class="fas fa-users"></i></span>
                    Students
                </li>
                <li class="nav-item" data-page="logout" id="logoutBtn">
                    <span class="nav-icon"><i class="fas fa-sign-out-alt"></i></span>
                    Logout
                </li>
            </ul>
        </nav>

        <!-- Hamburger Menu -->
        <label for="sidebar-toggle" class="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </label>

        
        <!-- Main Content -->
        <main class="main-content" id="main-content">
            <div class="header">
                <h1><i class="fas fa-chart-bar"></i> RaiseTrack: Student Participation Tracker</h1>
                <div>
                    Welcome, <strong><?php echo htmlspecialchars($_SESSION['name']); ?></strong> 
                </div>
            </div>

            <div class="content-card">
                <h2 class="card-title">
                    Participation Overview
                    <div class="dashboard-card-toolbar">
                        <select id="sectionFilter" class="filter-select" onchange="filterChart()" aria-label="Filter by section">
                            <option value="">Loading sections...</option>
                        </select>
                        <select id="teacherFilter" class="filter-select" onchange="filterChart()" aria-label="Filter by teacher">
                            <option value="">Loading teachers...</option>
                        </select>
                    </div>
                </h2>
                <div class="chart-container">
                    <canvas id="participationChart"></canvas>
                </div>
            </div>
        </main>
    </div>