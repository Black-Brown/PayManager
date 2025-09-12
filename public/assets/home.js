// Toggle Sidebar
const menuToggle = document.getElementById('menuToggle');
const sidebar = document.getElementById('sidebar');
const mainContent = document.getElementById('mainContent');
const overlay = document.getElementById('overlay');

menuToggle.addEventListener('click', () => {
    sidebar.classList.toggle('active');
    mainContent.classList.toggle('sidebar-open');
    overlay.classList.toggle('active');
});

overlay.addEventListener('click', () => {
    sidebar.classList.remove('active');
    mainContent.classList.remove('sidebar-open');
    overlay.classList.remove('active');
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