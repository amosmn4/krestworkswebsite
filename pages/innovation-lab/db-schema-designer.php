<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title = 'Database Schema Designer — Innovation Lab — ' . APP_NAME;

?>

<section class="kw-page-hero">
  <div class="kw-container">
    <div class="kw-breadcrumb"><a href="<?= url() ?>">Home</a><i class="fa-solid fa-chevron-right"></i><a href="<?= url('innovation-lab') ?>">Innovation Lab</a><i class="fa-solid fa-chevron-right"></i><span class="current">DB Schema Designer</span></div>
    <div data-aos="fade-up" style="padding-bottom:2rem;">
      <span class="label"><i class="fa-solid fa-database"></i> Designer Tool</span>
      <h1>Database Schema Designer</h1>
      <p>Design database tables visually, define columns and relationships, then export as SQL or JSON. Ask AI to generate schemas from descriptions.</p>
    </div>
  </div>
</section>

<section style="background:var(--kw-bg);padding:1.5rem 0 4rem;">
  <div class="kw-container">
    <div style="display:grid;grid-template-columns:260px 1fr;gap:1.25rem;align-items:flex-start;">

      <!-- Left panel -->
      <div style="display:flex;flex-direction:column;gap:1rem;">
        <div class="kw-card" style="padding:1.25rem;">
          <h5 style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);margin-bottom:0.85rem;">Add Table</h5>
          <input id="new-table-name" placeholder="Table name (e.g. users)"
                 style="width:100%;padding:0.5rem 0.75rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:6px;font-size:0.82rem;color:var(--kw-text-primary);margin-bottom:0.5rem;outline:none;"
                 onfocus="this.style.borderColor='var(--kw-primary)'" onblur="this.style.borderColor=''"
                 onkeydown="if(event.key==='Enter')DSD.addTable()">
          <button onclick="DSD.addTable()" class="kw-btn kw-btn-primary kw-btn-sm" style="width:100%;justify-content:center;">
            <i class="fa-solid fa-plus"></i> Add Table
          </button>
        </div>

        <div class="kw-card" style="padding:1.25rem;">
          <h5 style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);margin-bottom:0.85rem;">Quick Templates</h5>
          <?php foreach ([
            ['Blog','users,posts,categories,comments,tags'],
            ['E-Commerce','users,products,orders,order_items,categories,payments'],
            ['HR System','employees,departments,positions,payroll,leave_requests'],
            ['LMS','users,courses,enrollments,lessons,assignments,grades'],
          ] as $tmpl): ?>
          <button onclick="DSD.loadTemplate('<?= $tmpl[0] ?>','<?= $tmpl[1] ?>')" class="kw-btn kw-btn-ghost kw-btn-sm" style="width:100%;justify-content:flex-start;margin-bottom:0.35rem;font-size:0.78rem;">
            <i class="fa-solid fa-table" style="color:var(--kw-primary);width:14px;"></i> <?= $tmpl[0] ?>
          </button>
          <?php endforeach; ?>
        </div>

        <div class="kw-card" style="padding:1.25rem;">
          <h5 style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);margin-bottom:0.75rem;">
            <i class="fa-solid fa-wand-magic-sparkles" style="color:#A855F7;"></i> AI Generate
          </h5>
          <textarea id="ai-schema-prompt" rows="3" placeholder="Describe your system, e.g. A hospital management system with patients, doctors, appointments..."
                    style="width:100%;resize:vertical;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:6px;padding:0.5rem 0.75rem;font-size:0.78rem;color:var(--kw-text-secondary);outline:none;margin-bottom:0.5rem;"
                    onfocus="this.style.borderColor='#A855F7'" onblur="this.style.borderColor=''"></textarea>
          <button onclick="DSD.aiGenerate()" class="kw-btn kw-btn-sm" style="width:100%;justify-content:center;background:linear-gradient(135deg,#7C3AED,#A855F7);color:#fff;border:none;font-size:0.75rem;">
            <i class="fa-solid fa-wand-magic-sparkles"></i> Generate Schema
          </button>
        </div>

        <div style="display:flex;gap:0.5rem;">
          <button onclick="DSD.exportSQL()" class="kw-btn kw-btn-primary kw-btn-sm" style="flex:1;justify-content:center;font-size:0.75rem;"><i class="fa-solid fa-code"></i> Export SQL</button>
          <button onclick="DSD.clearAll()" class="kw-btn kw-btn-ghost kw-btn-sm" style="flex:1;justify-content:center;font-size:0.75rem;"><i class="fa-solid fa-trash"></i> Clear</button>
        </div>
      </div>

      <!-- Main area -->
      <div>
        <div id="tables-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:1rem;"></div>
        <div id="dsd-empty" class="kw-card" style="padding:3rem;text-align:center;">
          <i class="fa-solid fa-database" style="font-size:2.5rem;color:var(--kw-text-muted);opacity:0.3;margin-bottom:1rem;display:block;"></i>
          <p style="color:var(--kw-text-muted);">Add a table or use a template to start designing your schema.</p>
        </div>
        <div id="sql-output-section" style="display:none;margin-top:1.25rem;" class="kw-card" style="padding:1.25rem;">
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.75rem;">
            <h4 style="font-size:0.9rem;">Generated SQL</h4>
            <button onclick="navigator.clipboard.writeText(document.getElementById('sql-output').value);window.Krest?.toast('Copied!','success')" class="kw-btn kw-btn-primary kw-btn-sm"><i class="fa-solid fa-copy"></i> Copy</button>
          </div>
          <textarea id="sql-output" rows="16" readonly style="width:100%;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);padding:1rem;font-family:monospace;font-size:0.78rem;color:var(--kw-text-secondary);resize:vertical;"></textarea>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
const DSD = (() => {
  const FIELD_TYPES = ['INT','VARCHAR(255)','TEXT','LONGTEXT','TINYINT','DECIMAL(10,2)','DATE','DATETIME','TIMESTAMP','BOOLEAN','JSON','ENUM'];
  let tables = {}, tableOrder = [];

  function addTable(name) {
    name = (name || document.getElementById('new-table-name').value.trim().toLowerCase().replace(/\s+/g,'_'));
    if (!name) { window.Krest?.toast('Enter a table name','warning'); return; }
    if (tables[name]) { window.Krest?.toast('Table already exists','warning'); return; }
    tables[name] = [
      {name:'id',type:'INT',pk:true,nn:true,ai:true},
      {name:'created_at',type:'TIMESTAMP',pk:false,nn:false,ai:false,default:'CURRENT_TIMESTAMP'},
    ];
    tableOrder.push(name);
    document.getElementById('new-table-name').value = '';
    render();
  }

  function render() {
    const grid = document.getElementById('tables-grid');
    const empty= document.getElementById('dsd-empty');
    empty.style.display = tableOrder.length ? 'none' : '';
    grid.innerHTML = tableOrder.map(tname => {
      const cols = tables[tname];
      return `
        <div class="kw-card" style="padding:0;overflow:hidden;">
          <div style="display:flex;align-items:center;justify-content:space-between;padding:0.75rem 1rem;background:var(--kw-primary);color:#0A0F1A;">
            <div style="display:flex;align-items:center;gap:0.5rem;">
              <i class="fa-solid fa-table" style="font-size:0.8rem;"></i>
              <strong style="font-size:0.85rem;font-family:monospace;">${tname}</strong>
            </div>
            <div style="display:flex;gap:0.3rem;">
              <button onclick="DSD.addColumn('${tname}')" style="background:rgba(0,0,0,0.2);border:none;color:#0A0F1A;padding:0.2rem 0.5rem;border-radius:4px;cursor:pointer;font-size:0.7rem;"><i class="fa-solid fa-plus"></i></button>
              <button onclick="DSD.removeTable('${tname}')" style="background:rgba(255,0,0,0.2);border:none;color:#fff;padding:0.2rem 0.5rem;border-radius:4px;cursor:pointer;font-size:0.7rem;"><i class="fa-solid fa-trash"></i></button>
            </div>
          </div>
          <div style="padding:0.5rem;">
            ${cols.map((c,i) => `
              <div style="display:flex;align-items:center;gap:0.4rem;padding:0.3rem 0.4rem;border-bottom:1px solid var(--kw-border);font-size:0.72rem;">
                ${c.pk ? '<i class="fa-solid fa-key" style="color:#F5A800;width:10px;font-size:0.6rem;"></i>' : '<span style="width:10px;display:inline-block;"></span>'}
                <input value="${c.name}" oninput="DSD.updateCol('${tname}',${i},'name',this.value)"
                       style="flex:1;min-width:0;background:none;border:none;font-family:monospace;font-size:0.72rem;color:var(--kw-text-primary);outline:none;padding:0.1rem;">
                <select onchange="DSD.updateCol('${tname}',${i},'type',this.value)"
                        style="background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:4px;font-size:0.65rem;color:var(--kw-text-muted);padding:0.1rem 0.25rem;cursor:pointer;">
                  ${FIELD_TYPES.map(t=>`<option ${c.type===t?'selected':''}>${t}</option>`).join('')}
                </select>
                <label style="display:flex;align-items:center;gap:0.15rem;font-size:0.65rem;color:var(--kw-text-muted);cursor:pointer;">
                  <input type="checkbox" ${c.nn?'checked':''} onchange="DSD.updateCol('${tname}',${i},'nn',this.checked)" style="width:10px;height:10px;">NN
                </label>
                ${!c.pk?`<button onclick="DSD.removeCol('${tname}',${i})" style="background:none;border:none;color:#EF4444;cursor:pointer;font-size:0.65rem;padding:0;">✕</button>`:''}
              </div>
            `).join('')}
            <button onclick="DSD.addColumn('${tname}')" style="width:100%;margin-top:0.35rem;background:none;border:1px dashed var(--kw-border);border-radius:4px;padding:0.35rem;font-size:0.72rem;color:var(--kw-text-muted);cursor:pointer;transition:all 0.2s;" onmouseover="this.style.borderColor='var(--kw-primary)'" onmouseout="this.style.borderColor=''">
              <i class="fa-solid fa-plus"></i> Add Column
            </button>
          </div>
        </div>
      `;
    }).join('');
  }

  function generateSQL() {
    return tableOrder.map(tname => {
      const cols = tables[tname];
      const colDefs = cols.map(c => {
        let def = `  \`${c.name}\` ${c.type}`;
        if (c.nn||c.pk) def += ' NOT NULL';
        if (c.ai) def += ' AUTO_INCREMENT';
        if (c.default && !c.ai) def += ` DEFAULT ${c.default}`;
        return def;
      });
      const pks = cols.filter(c=>c.pk).map(c=>`\`${c.name}\``);
      if (pks.length) colDefs.push(`  PRIMARY KEY (${pks.join(', ')})`);
      return `CREATE TABLE \`${tname}\` (\n${colDefs.join(',\n')}\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;`;
    }).join('\n\n');
  }

  return {
    addTable,
    removeTable(name) { delete tables[name]; tableOrder=tableOrder.filter(t=>t!==name); render(); },
    addColumn(tname) {
      tables[tname].push({name:'column_'+(tables[tname].length+1),type:'VARCHAR(255)',pk:false,nn:false,ai:false});
      render();
    },
    removeCol(tname,i) { tables[tname].splice(i,1); render(); },
    updateCol(tname,i,key,val) { tables[tname][i][key]=val; },
    loadTemplate(name, tableList) {
      tables={}; tableOrder=[];
      tableList.split(',').forEach(t => {
        tables[t]=[{name:'id',type:'INT',pk:true,nn:true,ai:true},{name:'created_at',type:'TIMESTAMP',pk:false,nn:false,ai:false,default:'CURRENT_TIMESTAMP'}];
        tableOrder.push(t);
      });
      render(); window.Krest?.toast(`Loaded ${name} template`,'success');
    },
    clearAll() { tables={}; tableOrder=[]; render(); document.getElementById('sql-output-section').style.display='none'; },
    exportSQL() {
      const sql = generateSQL();
      document.getElementById('sql-output-section').style.display='';
      document.getElementById('sql-output').value = sql;
      document.getElementById('sql-output-section').scrollIntoView({behavior:'smooth'});
    },
    async aiGenerate() {
      const prompt = document.getElementById('ai-schema-prompt').value.trim();
      if (!prompt) { window.Krest?.toast('Describe your system first','warning'); return; }
      window.Krest?.toast('AI designing your schema...','info');
      try {
        const resp = await fetch('<?= url('api/ai-assistant') ?>', {
          method:'POST', headers:{'Content-Type':'application/json','X-CSRF-Token':document.querySelector('meta[name="csrf-token"]')?.content||''},
          body:JSON.stringify({messages:[{role:'user',content:`Design a database schema for: "${prompt}". Respond ONLY with JSON: {"tables":{"table_name":[{"name":"col","type":"INT","pk":true,"nn":true,"ai":true},...],...}}. Include standard columns (id, created_at). 4-8 tables.`}],system:'You are a database architect. Respond ONLY with valid JSON.'})
        });
        const data = await resp.json();
        const text = (data.data?.reply||'').replace(/```json|```/g,'').trim();
        const schema = JSON.parse(text);
        if (schema.tables) {
          tables=schema.tables; tableOrder=Object.keys(schema.tables);
          render(); window.Krest?.toast('Schema generated!','success');
        }
      } catch(e) { window.Krest?.toast('Could not parse AI response. Try rephrasing.','error'); }
    }
  };
})();
</script>
<style>@media(max-width:768px){.kw-container>div[style*="260px 1fr"]{grid-template-columns:1fr!important;}}</style>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>