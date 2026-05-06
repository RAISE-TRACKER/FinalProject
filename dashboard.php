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

    

    <script>
        let chart;
        let allStudents = [];

        // Load students and initialize
        async function loadChart() {
            try {
                const response = await fetch('get_students.php');
                allStudents = await response.json();
                populateSectionFilter();
                populateTeacherFilter();
                filterChart(); // Initial chart with all sections
            } catch (error) {
                console.error('Error loading chart:', error);
            }
        }

        // Populate filter dropdown
        function populateSectionFilter() {
            const filter = document.getElementById('sectionFilter');
            const sections = [...new Set(allStudents.map(s => s.section_code).filter(Boolean))];
            
            filter.innerHTML = '<option value="">All Sections</option>';
            sections.forEach(section => {
                const option = new Option(section, section);
                filter.add(option);
            });
        }

        function populateTeacherFilter() {
            const filter = document.getElementById('teacherFilter');
            const teachers = [...new Set(allStudents.map(s => s.teacher_name).filter(Boolean))];

            filter.innerHTML = '<option value="">All Teachers</option>';
            teachers.forEach(teacher => {
                const option = new Option(teacher, teacher);
                filter.add(option);
            });
        }

        // Filter and update chart
        window.filterChart = function() {
            const selectedSection = document.getElementById('sectionFilter').value;
            const selectedTeacher = document.getElementById('teacherFilter').value;
            const filteredStudents = allStudents.filter(s =>
                (selectedSection === '' || s.section_code === selectedSection) &&
                (selectedTeacher === '' || s.teacher_name === selectedTeacher)
            );

            const ctx = document.getElementById('participationChart').getContext('2d');
            if (chart) chart.destroy();

            chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: filteredStudents.map(s => s.name),
                    datasets: [{
                        label: 'Participation Score',
                        data: filteredStudents.map(s => s.participation),
                        backgroundColor: '#FAC846',
                        borderColor: 'rgba(255, 125, 45, 0)',
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { display: false } 
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: { color: '#233C4B' },
                            grid: { color: 'rgba(95, 155, 140, 0.25)' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#233C4B' }
                        }
                    }
                }
            });
        }

        // Initial load
        loadChart();

        // Navigation
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function() {
                const page = this.dataset.page;
                document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');
                
                if (page === 'students') {
                    window.location.href = 'students.php';
                } 
            });
        });

        // Sidebar toggle
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');

        sidebarToggle.addEventListener('change', function() {
            if (this.checked) {
                sidebar.classList.add('open');
                mainContent.classList.add('expanded');
            } else {
                sidebar.classList.remove('open');
                mainContent.classList.remove('expanded');
            }
        });

        // Logout
        document.getElementById('logoutBtn').addEventListener('click', function(event) {
            event.stopPropagation();
            let isConfirmed = confirm("Are you sure you want to log out?");
            if (isConfirmed) {
                window.location.href = "logout.php";
            }
        });
    </script>
</body>
</html>