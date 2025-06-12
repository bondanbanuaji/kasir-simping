
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const texts = sidebar.querySelectorAll('.sidebar-text');
    const title = document.getElementById('sidebar-title');

    if (sidebar.style.width === '4rem') {
        sidebar.style.width = '12rem';
        title.classList.remove('hidden');
        texts.forEach(el => el.classList.remove('hidden'));
    } else {
        sidebar.style.width = '4rem';
        title.classList.add('hidden');
        texts.forEach(el => el.classList.add('hidden'));
    }
}
