<?php 
require_once 'config.php'; 
requireLogin(); // Requires login
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

    <script>
        let students = [];
        let allStudents = []; // Store all students for filtering
        let currentSectionFilter = '';
        let currentTeacherFilter = '';

        // Load students
        async function loadStudents() {
            try {
                const response = await fetch('get_students.php');
                if (!response.ok) throw new Error('Failed to fetch students');
                allStudents = await response.json();
                students = [...allStudents];
                displayStudents();
                populateSectionFilter();
                populateTeacherFilter();
                applyCurrentFilters();
            } catch (error) {
                console.error('Error loading students:', error);
                alert('Error loading students. Please refresh the page.');
            }
        }

        function populateSectionFilter() {
            const sectionFilter = document.getElementById('sectionFilter');
            const sections = [...new Set(allStudents.map(student => student.section_code).filter(code => code && code.trim() !== ''))].sort();
            
            // Clear existing options except first one
            sectionFilter.innerHTML = '<option value="">All Sections</option>';
            
            // Add section options
            sections.forEach(section => {
                const option = document.createElement('option');
                option.value = section;
                option.textContent = section;
                sectionFilter.appendChild(option);
            });
        }

        function populateTeacherFilter() {
            const teacherFilter = document.getElementById('teacherFilter');
            const teachers = [...new Set(allStudents.map(student => student.teacher_name).filter(name => name && name.trim() !== ''))].sort();

            teacherFilter.innerHTML = '<option value="">All Teachers</option>';

            teachers.forEach(teacher => {
                const option = document.createElement('option');
                option.value = teacher;
                option.textContent = teacher;
                teacherFilter.appendChild(option);
            });
        }

        function displayStudents(filteredStudents = students) {
            const tbody = document.getElementById('studentsTableBody');
            if (filteredStudents.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="table-empty-message">No students found</td></tr>';
                return;
            }
            
            tbody.innerHTML = filteredStudents.map(student => `
                <tr>
                    <td>${student.id || ''}</td>
                    <td>${student.student_id || ''}</td>
                    <td>${student.name || ''}</td>
                    <td>${student.section_code || ''}</td>
                    <td>${student.participation || 0}</td>
                    <td>${student.teacher_name || ''}</td>
                    <td>
                        <button class="btn btn-primary btn-sm" onclick="editStudent(${student.id})" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-secondary btn-sm" onclick="deleteStudent(${student.id})" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        // Filter students by section and teacher
        function filterStudents() {
            const selectedSection = document.getElementById('sectionFilter').value;
            const selectedTeacher = document.getElementById('teacherFilter').value;
            currentSectionFilter = selectedSection;
            currentTeacherFilter = selectedTeacher;

            students = allStudents.filter(student =>
                (selectedSection === '' || student.section_code === selectedSection) &&
                (selectedTeacher === '' || student.teacher_name === selectedTeacher)
            );

            displayStudents(students);
        }

        function applyCurrentFilters() {
            const sectionFilter = document.getElementById('sectionFilter');
            const teacherFilter = document.getElementById('teacherFilter');

            if (currentSectionFilter && sectionFilter.querySelector(`option[value="${currentSectionFilter}"]`)) {
                sectionFilter.value = currentSectionFilter;
            }
            if (currentTeacherFilter && teacherFilter.querySelector(`option[value="${currentTeacherFilter}"]`)) {
                teacherFilter.value = currentTeacherFilter;
            }
            filterStudents();
        }

        // Add student
        document.getElementById('addStudentForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData();
            formData.append('student_id', document.getElementById('studentId').value.trim());
            formData.append('name', document.getElementById('studentName').value.trim());
            formData.append('section_code', document.getElementById('sectionCode').value.trim().toUpperCase());

            try {
                const response = await fetch('add_student.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (response.ok && result.success) {
                    closeAddModal();
                    loadStudents(); // Refresh data and filter options
                } else {
                    alert(result.error || 'Error adding student');
                }
            } catch (error) {
                console.error('Error adding student:', error);
                alert('Error adding student. Please try again.');
            }
        });

        // Edit student
        window.editStudent = function(id) {
            const student = allStudents.find(s => s.id == id); // Use allStudents to ensure we find it
            if (student) {
                document.getElementById('editStudentId').value = student.id;
                document.getElementById('editStudentName').value = student.name || '';
                document.getElementById('editStudentIdDisplay').value = student.student_id || '';
                document.getElementById('editSectionCode').value = student.section_code || '';
                document.getElementById('editParticipation').value = student.participation || 0;
                document.getElementById('editModal').style.display = 'block';
            }
        }

        document.getElementById('editStudentForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData();
            formData.append('id', document.getElementById('editStudentId').value);
            formData.append('participation', document.getElementById('editParticipation').value);

            try {
                const response = await fetch('update_student.php', {
                    method: 'POST',
                    body: formData
                });
                
                if (response.ok) {
                    closeEditModal();
                    loadStudents();
                } else {
                    alert('Error updating student');
                }
            } catch (error) {
                console.error('Error updating student:', error);
                alert('Error updating student');
            }
        });

        // Delete student
        window.deleteStudent = async function(id) {
            if (confirm('Are you sure you want to delete this student?')) {
                try {
                    const response = await fetch(`delete_student.php?id=${id}`, { 
                        method: 'DELETE' 
                    });
                    if (response.ok) {
                        loadStudents(); // Refresh data and filter options
                    } else {
                        alert('Error deleting student');
                    }
                } catch (error) {
                    console.error('Error deleting student:', error);
                    alert('Error deleting student');
                }
            }
        }

        // Modal functions
        function openAddModal() {
            document.getElementById('addModal').style.display = 'block';
        }

        function closeAddModal() {
            document.getElementById('addModal').style.display = 'none';
            document.getElementById('studentId').value = '';
            document.getElementById('studentName').value = '';
            document.getElementById('sectionCode').value = '';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const addModal = document.getElementById('addModal');
            const editModal = document.getElementById('editModal');
            if (event.target == addModal) closeAddModal();
            if (event.target == editModal) closeEditModal();
        }

        // Navigation
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.stopPropagation();
                const page = this.dataset.page;
                document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');
                
                if (page === 'dashboard') {
                    window.location.href = 'dashboard.php';
                } else if (page === 'logout') {
                    if (confirm('Are you sure you want to log out?')) {
                        window.location.href = 'logout.php';
                    }
                }
            });
        });

        // Sidebar toggle
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('change', function() {
                if (this.checked) {
                    sidebar.classList.add('open');
                    mainContent.classList.add('expanded');
                } else {
                    sidebar.classList.remove('open');
                    mainContent.classList.remove('expanded');
                }
            });
        }

        // Initial load
        loadStudents();
    </script>
</body>
</html>