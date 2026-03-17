</div><!-- .adm-content -->
  </div><!-- .adm-main -->
</div><!-- .adm-layout -->

<script src="<?= asset('js/krest-theme.js') ?>"></script>
<script src="<?= asset('js/krest.js') ?>"></script>
<script>
function admOpenSidebar()  { document.getElementById('adm-sidebar').classList.add('open'); document.getElementById('adm-overlay').classList.add('show'); }
function admCloseSidebar() { document.getElementById('adm-sidebar').classList.remove('open'); document.getElementById('adm-overlay').classList.remove('show'); }

// Generic AJAX delete with confirmation
async function admDelete(endpoint, id, onSuccess) {
  if (!confirm('Are you sure? This cannot be undone.')) return;
  try {
    const fd = new FormData();
    fd.append('id', id);
    fd.append('<?= CSRF_TOKEN_NAME ?>', document.querySelector('meta[name="csrf-token"]')?.content || '');
    const resp = await fetch('<?= url('') ?>' + endpoint, { method: 'POST', body: fd });
    const data = await resp.json();
    if (data.success) { window.Krest?.toast(data.message || 'Deleted.', 'success'); if (onSuccess) onSuccess(); }
    else window.Krest?.toast(data.message || 'Failed.', 'error');
  } catch(e) { window.Krest?.toast('Connection error.', 'error'); }
}

// DataTable-style search filter
function admSearchTable(inputId, tableId) {
  const q = document.getElementById(inputId)?.value.toLowerCase();
  document.querySelectorAll(`#${tableId} tbody tr`).forEach(row => {
    row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
  });
}
</script>
</body>
</html>