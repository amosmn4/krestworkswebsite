</div><!-- .portal-content -->
</main><!-- .portal-main -->

<!-- Mobile sidebar overlay -->
<div id="sidebar-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:199;" onclick="toggleSidebar();this.style.display='none'"></div>

<script>
// Enhance mobile sidebar toggle
const origToggle = window.toggleSidebar;
window.toggleSidebar = function() {
  const sidebar = document.getElementById('portal-sidebar');
  const overlay = document.getElementById('sidebar-overlay');
  const isOpen = sidebar.classList.toggle('open');
  overlay.style.display = isOpen ? 'block' : 'none';
};
</script>
</body>
</html>