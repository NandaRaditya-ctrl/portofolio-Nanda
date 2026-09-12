    </main>
    <footer class="text-center py-4 mt-auto text-muted">
        <p class="mb-0">&copy; <?php echo date('Y'); ?> DevJourney. Proyek Bulan 7 &mdash; PHP Native + MySQL + Bootstrap 5.</p>
    </footer>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const body = document.body;
            const toggles = document.querySelectorAll('.theme-toggle');

            const updateToggles = () => {
                const isDark = body.classList.contains('dark-mode');
                toggles.forEach(toggle => {
                    toggle.innerHTML = isDark ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
                    toggle.setAttribute('aria-pressed', isDark ? 'true' : 'false');
                });
            };

            updateToggles();

            toggles.forEach(toggle => {
                toggle.addEventListener('click', function () {
                    const isDark = body.classList.toggle('dark-mode');
                    localStorage.setItem('bulan7-theme', isDark ? 'dark' : 'light');
                    updateToggles();
                });
            });

            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function (alert) {
                if (alert.classList.contains('alert-success')) {
                    alert.classList.add('checkout-success');
                }
            });
        });
    </script>
</body>
</html>
