</div>
</div>
</body>
    <script>
        // hamburger menu javascript
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobile-overlay');
        
        // toggle sidebar
        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            mobileOverlay.classList.toggle('hidden');
        }
        
        // close sidebar
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
        
        // dropdown menu javascript
        function toggleDropdown(studentId) {
            // Diğer tüm dropdown'ları kapat
            document.querySelectorAll('[id^="dropdown-menu-"]').forEach(menu => {
                if (menu.id !== `dropdown-menu-${studentId}`) {
                    menu.classList.add('hidden');
                }
            });
            
            // Seçilen dropdown'ı aç/kapat
            const dropdown = document.getElementById(`dropdown-menu-${studentId}`);
            dropdown.classList.toggle('hidden');
        }
        
        // other click (close dropdown)
        document.addEventListener('click', function(event) {
            if (!event.target.closest('[id^="dropdown-button-"]') && !event.target.closest('[id^="dropdown-menu-"]')) {
                document.querySelectorAll('[id^="dropdown-menu-"]').forEach(menu => {
                    menu.classList.add('hidden');
                });
            }
        });
    </script>
        
    </body>
</html>