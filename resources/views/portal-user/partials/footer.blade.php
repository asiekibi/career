</div>
</div>

<script>
    // hamburger menu javascript
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const sidebar = document.getElementById('sidebar');
    const mobileOverlay = document.getElementById('mobile-overlay');

    function toggleSidebar() {
        sidebar.classList.toggle('-translate-x-full');
        mobileOverlay.classList.toggle('hidden');
    }

    function closeSidebar() {
                    sidebar.classList.add('-translate-x-full');
        mobileOverlay.classList.add('hidden');
    }

    // hamburger menu button click
    mobileMenuButton.addEventListener('click', toggleSidebar);

    // overlay click (close menu)
    mobileOverlay.addEventListener('click', closeSidebar);

    // escape key click (close menu)
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeSidebar();
        }
    });

    // window resize (close menu)
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 1024) { // lg breakpoint
            closeSidebar();
        }
    });

</script>

</body>    
</html>
