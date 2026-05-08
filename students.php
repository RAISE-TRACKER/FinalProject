<?php 
require_once 'config.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students List - RaiseTrack</title>
    <link rel="stylesheet" href="style.css?v=<?php echo filemtime(__DIR__ . '/style.css'); ?>">
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
                <li class="nav-item" data-page="dashboard">
                    <span class="nav-icon"><i class="fas fa-chart-bar"></i></span>
                    Dashboard
                </li>
                <li class="nav-item active" data-page="students">
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
                <h1><i class="fas fa-users"></i> Students Management</h1>
                <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                    <a href="#" class="btn btn-primary" onclick="openAddModal()">
                        <i class="fas fa-plus"></i> Add New Student
                    </a>
                    <span class="header-meta">
                        Welcome, <strong><?php echo htmlspecialchars($_SESSION['name']); ?></strong>
                    </span>
                </div>
            </div>

            <div class="content-card">
                <div class="card-title">
                    <div>All Students</div>
                    <div class="filter-container">
                        <label for="sectionFilter">Filter by Section:</label>
                        <select id="sectionFilter" onchange="filterStudents()">
                            <option value="">All Sections</option>
                            <!-- Options populated by JavaScript -->
                        </select>
                        <label for="teacherFilter">Filter by Teacher:</label>
                        <select id="teacherFilter" onchange="filterStudents()">
                            <option value="">All Teachers</option>
                            <!-- Options populated by JavaScript -->
                        </select>
                    </div>
                </div>
                <div class="table-container">
                    <table id="studentsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Section Code</th>
                                <th>Participation</th>
                                <th>Teacher Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="studentsTableBody">
                            <!-- Students loaded via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
    <!-- Add Student Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddModal()">&times;</span>
            <h2>Add New Student</h2>
            <form id="addStudentForm">
                <div class="form-group">
                    <label for="studentId">Student ID:</label>
                    <input type="text" id="studentId" name="student_id" required maxlength="50">
                </div>
                <div class="form-group">
                    <label for="studentName">Student Name:</label>
                    <input type="text" id="studentName" name="name" required>
                </div>
                <div class="form-group">
                    <label for="sectionCode">Section Code:</label>
                    <input type="text" id="sectionCode" name="section_code" required placeholder="e.g., ICS20, ICS30" maxlength="20">
                </div>
                <button type="submit" class="btn btn-primary">Add Student</button>
            </form>
        </div>
    </div>

    <!-- Edit Participation Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h2>Edit Participation</h2>
            <form id="editStudentForm">
                <input type="hidden" id="editStudentId">
                <div class="form-group">
                    <label>Student Name:</label>
                    <input type="text" id="editStudentName" readonly>
                </div>
                <div class="form-group">
                    <label>Student ID:</label>
                    <input type="text" id="editStudentIdDisplay" readonly>
                </div>
                <div class="form-group">
                    <label>Section Code:</label>
                    <input type="text" id="editSectionCode" readonly>
                </div>
                <div class="form-group">
                    <label for="editParticipation">Participation Score (0-100):</label>
                    <input type="number" id="editParticipation" name="participation" min="0" max="100" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Participation</button>
            </form>
        </div>
    </div>
