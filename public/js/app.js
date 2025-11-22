// Simple dropdown toggle for user menu
document.addEventListener('DOMContentLoaded', function() {
    const menuBtn = document.getElementById('user-menu-btn');
    const dropdown = document.getElementById('user-dropdown');
    
    if (menuBtn && dropdown) {
        let isOpen = false;
        
        menuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            isOpen = !isOpen;
            dropdown.style.display = isOpen ? 'block' : 'none';
            menuBtn.setAttribute('aria-expanded', isOpen);
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!menuBtn.contains(e.target) && !dropdown.contains(e.target)) {
                isOpen = false;
                dropdown.style.display = 'none';
                menuBtn.setAttribute('aria-expanded', 'false');
            }
        });
        
        // Close dropdown on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && isOpen) {
                isOpen = false;
                dropdown.style.display = 'none';
                menuBtn.setAttribute('aria-expanded', 'false');
            }
        });
    }
});

