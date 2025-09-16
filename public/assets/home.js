// Toggle Sidebar
const menuToggle = document.getElementById('menuToggle');
const sidebar = document.getElementById('sidebar');
const mainContent = document.getElementById('mainContent');
const overlay = document.getElementById('overlay');

// Check if mobile view
function isMobile() {
    return window.innerWidth <= 768;
}

// Initialize sidebar state based on screen size
function initializeSidebar() {
    if (isMobile()) {
        sidebar.classList.remove('active');
        sidebar.classList.add('hidden');
        mainContent.classList.remove('sidebar-hidden');
        overlay.classList.remove('active');
    } else {
        sidebar.classList.remove('hidden');
        sidebar.classList.remove('active');
        mainContent.classList.remove('sidebar-hidden');
        overlay.classList.remove('active');
    }
}

menuToggle.addEventListener('click', () => {
    if (isMobile()) {
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
    } else {
        sidebar.classList.toggle('hidden');
        mainContent.classList.toggle('sidebar-hidden');
    }
});

overlay.addEventListener('click', () => {
    sidebar.classList.remove('active');
    overlay.classList.remove('active');
});

// Handle window resize
window.addEventListener('resize', () => {
    initializeSidebar();
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    initializeSidebar();
});

// Navigation function
function navigateTo(section) {
    alert(`Navegando a: ${section}\n\nEsta funcionalidad se implementará en las páginas específicas.`);
}

// Update current time
function updateTime() {
    const now = new Date();
    const timeString = now.toLocaleString('es-ES');
    document.title = `Sistema Escolar - ${timeString}`;
}

setInterval(updateTime, 60000);
updateTime();

// Menu item click handler
document.querySelectorAll('.menu-item').forEach(item => {
    item.addEventListener('click', (e) => {
        e.preventDefault();
        
        // Remove active class from all items
        document.querySelectorAll('.menu-item').forEach(i => i.classList.remove('active'));
        
        // Add active class to clicked item
        item.classList.add('active');
        
        // Update header title
        const headerTitle = document.querySelector('.header-title');
        headerTitle.textContent = item.textContent.trim();
    });
});