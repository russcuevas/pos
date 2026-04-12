// Auto-mini on small screens
const BREAKPOINT = 1024;
const THEME_KEY = 'naap-theme';
const sidebar = document.getElementById('sidebar');
const sidebarToggle = document.getElementById('sidebarToggle');
const themeToggle = document.getElementById('themeToggle');
const themeToggleIcon = document.getElementById('themeToggleIcon');
const userMenu = document.getElementById('userMenu');
const userMenuToggle = document.getElementById('userMenuToggle');
const sidebarLinks = document.querySelectorAll('.sidebar .nav-link');
const charts = [];

function isResponsiveViewport() {
    return window.innerWidth <= BREAKPOINT;
}

function closeResponsiveSidebar() {
    document.body.classList.remove('sidebar-open');
}

function getThemeChartColors() {
    const styles = getComputedStyle(document.body);

    return {
        text: styles.getPropertyValue('--mine-shaft').trim(),
        muted: styles.getPropertyValue('--text-muted').trim(),
        grid: document.body.classList.contains('dark-mode') ? 'rgba(147, 164, 188, 0.16)' : 'rgba(107, 114, 128, 0.14)',
        cobalt: styles.getPropertyValue('--cobalt').trim(),
        cobaltSoft: document.body.classList.contains('dark-mode') ? 'rgba(239, 68, 68, 0.75)' : 'rgba(217, 48, 37, 0.78)',
        purple: document.body.classList.contains('dark-mode') ? '#fca5a5' : '#f3b2ac',
        green: '#059669',
        amber: '#d97706'
    };
}

function destroyCharts() {
    while (charts.length) {
        charts.pop().destroy();
    }
}

function baseChartOptions(colors) {
    return {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: {
                    color: colors.text,
                    usePointStyle: true,
                    boxWidth: 8,
                    boxHeight: 8,
                    font: {
                        family: 'Inter',
                        size: 11,
                        weight: '600'
                    }
                }
            },
            tooltip: {
                backgroundColor: document.body.classList.contains('dark-mode') ? '#0f172a' : '#1f2937',
                titleFont: { family: 'Inter', weight: '700' },
                bodyFont: { family: 'Inter' },
                padding: 10,
                cornerRadius: 10
            }
        },
        scales: {
            x: {
                ticks: {
                    color: colors.muted,
                    font: { family: 'Inter', size: 11 }
                },
                grid: { display: false },
                border: { color: colors.grid }
            },
            y: {
                beginAtZero: true,
                ticks: {
                    color: colors.muted,
                    font: { family: 'Inter', size: 11 }
                },
                grid: { color: colors.grid },
                border: { color: colors.grid }
            }
        }
    };
}

function renderCharts() {
    if (typeof Chart === 'undefined') {
        return;
    }

    destroyCharts();
    const colors = getThemeChartColors();

    const trainingStageCanvas = document.getElementById('trainingStageChart');
    const flightHoursCanvas = document.getElementById('flightHoursChart');
    const schoolOverviewCanvas = document.getElementById('schoolOverviewChart');

    if (trainingStageCanvas) {
        charts.push(new Chart(trainingStageCanvas, {
            type: 'bar',
            data: {
                labels: ['Ground School', 'Pre-Solo', 'Solo', 'Cross Country', 'Check Ride'],
                datasets: [{
                    label: 'Students',
                    data: [310, 420, 275, 168, 67],
                    backgroundColor: [
                        colors.cobalt,
                        colors.cobaltSoft,
                        colors.purple,
                        colors.green,
                        colors.amber
                    ],
                    borderRadius: 8,
                    borderSkipped: false,
                    maxBarThickness: 44
                }]
            },
            options: {
                ...baseChartOptions(colors),
                plugins: {
                    ...baseChartOptions(colors).plugins,
                    legend: { display: false }
                }
            }
        }));
    }

    if (flightHoursCanvas) {
        charts.push(new Chart(flightHoursCanvas, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Flight Hours',
                    data: [1980, 2140, 2360, 2485, 2570, 2890],
                    backgroundColor: colors.cobalt,
                    borderRadius: 8,
                    borderSkipped: false,
                    maxBarThickness: 42
                }]
            },
            options: {
                ...baseChartOptions(colors),
                plugins: {
                    ...baseChartOptions(colors).plugins,
                    legend: { display: false }
                }
            }
        }));
    }

    if (schoolOverviewCanvas) {
        charts.push(new Chart(schoolOverviewCanvas, {
            type: 'bar',
            data: {
                labels: ['Villamor'],
                datasets: [
                    {
                        label: 'Instructors',
                        data: [24],
                        backgroundColor: colors.cobalt,
                        borderRadius: 8,
                        borderSkipped: false,
                        maxBarThickness: 34
                    },
                    {
                        label: 'Students',
                        data: [320],
                        backgroundColor: colors.purple,
                        borderRadius: 8,
                        borderSkipped: false,
                        maxBarThickness: 34
                    }
                ]
            },
            options: baseChartOptions(colors)
        }));
    }
}

function syncThemeButton() {
    const darkMode = document.body.classList.contains('dark-mode');
    themeToggleIcon.className = darkMode ? 'bi bi-sun' : 'bi bi-moon-stars';
    themeToggle.setAttribute('aria-label', darkMode ? 'Switch to light mode' : 'Switch to dark mode');
    themeToggle.setAttribute('title', darkMode ? 'Light mode' : 'Dark mode');
}

function applyTheme(theme) {
    document.body.classList.toggle('dark-mode', theme === 'dark');
    syncThemeButton();
    renderCharts();
}


applyTheme(localStorage.getItem(THEME_KEY) || 'light');

function applyResponsiveSidebar() {
    if (isResponsiveViewport()) {
        document.body.classList.remove('sidebar-mini');
        closeResponsiveSidebar();
        return;
    }

    closeResponsiveSidebar();
}

applyResponsiveSidebar();
window.addEventListener('resize', applyResponsiveSidebar);

// Manual toggle (overrides responsive temporarily)
sidebarToggle.addEventListener('click', (event) => {
    event.stopPropagation();

    if (isResponsiveViewport()) {
        document.body.classList.toggle('sidebar-open');
        return;
    }

    document.body.classList.toggle('sidebar-mini');
});

themeToggle.addEventListener('click', () => {
    const nextTheme = document.body.classList.contains('dark-mode') ? 'light' : 'dark';
    applyTheme(nextTheme);
    localStorage.setItem(THEME_KEY, nextTheme);
});

userMenuToggle.addEventListener('click', () => {
    const isOpen = userMenu.classList.toggle('open');
    userMenuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
});

document.addEventListener('click', (event) => {
    if (isResponsiveViewport() && document.body.classList.contains('sidebar-open') && !sidebar.contains(event.target)) {
        closeResponsiveSidebar();
    }

    if (!userMenu.contains(event.target)) {
        userMenu.classList.remove('open');
        userMenuToggle.setAttribute('aria-expanded', 'false');
    }
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && isResponsiveViewport()) {
        closeResponsiveSidebar();
    }
});

sidebarLinks.forEach((link) => {
    link.addEventListener('click', () => {
        if (isResponsiveViewport()) {
            closeResponsiveSidebar();
        }
    });
});
