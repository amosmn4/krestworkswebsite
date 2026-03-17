<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title = 'Form Builder — Innovation Lab — ' . APP_NAME;


$fieldTypes = [
  ['text','fa-font','Text Input'],
  ['email','fa-at','Email'],
  ['tel','fa-phone','Phone'],
  ['number','fa-hashtag','Number'],
  ['textarea','fa-align-left','Textarea'],
  ['select','fa-list','Dropdown'],
  ['checkbox','fa-check-square','Checkbox'],
  ['radio','fa-circle-dot','Radio Group'],
  ['date','fa-calendar','Date'],
  ['file','fa-upload','File Upload'],
];
?>

<section class="kw-page-hero">
  <div class="kw-container">
    <div class="kw-breadcrumb"><a href="<?= url() ?>">Home</a><i class="fa-solid fa-chevron-right"></i><a href="<?= url('innovation-lab') ?>">Innovation Lab</a><i class="fa-solid fa-chevron-right"></i><span class="current">Form Builder</span></div>
    <div data-aos="fade-up" style="padding-bottom:2rem;">
      <span class="label"><i class="fa-solid fa-wpforms"></i> Builder Tool</span>
      <h1>Interactive Form Builder</h1>
      <p>Drag fields onto the canvas to build forms. Preview live and export as HTML or JSON schema.</p>
    </div>
  </div>
</section>

<section style="background:var(--kw-bg);padding:1.5rem 0 4rem;">
  <div class="kw-container">
    <div style="display:grid;grid-template-columns:200px 1fr 320px;gap:1.25rem;align-items:flex-start;">

      <!-- Field Palette -->
      <div class="kw-card" style="padding:1.25rem;">
        <h5 style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);margin-bottom:1rem;">Field Types</h5>
        <div id="field-palette">
          <?php foreach ($fieldTypes as $ft): ?>
          <div class="field-type-item" data-type="<?= $ft[0] ?>"
               style="display:flex;align-items:center;gap:0.6rem;padding:0.55rem 0.75rem;border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);margin-bottom:0.4rem;cursor:grab;transition:all 0.2s;font-size:0.8rem;"
               draggable="true"
               onmouseover="this.style.borderColor='var(--kw-primary)';this.style.background='var(--kw-primary)10'"
               onmouseout="this.style.borderColor='';this.style.background=''">
            <i class="fa-solid <?= $ft[1] ?>" style="color:var(--kw-primary);width:14px;font-size:0.75rem;"></i>
            <?= $ft[2] ?>
          </div>
          <?php endforeach; ?>
        </div>
        <hr style="border-color:var(--kw-border);margin:1rem 0;">
        <button onclick="FB.generateAIForm()" class="kw-btn kw-btn-sm" style="width:100%;justify-content:center;background:linear-gradient(135deg,#7C3AED,#A855F7);color:#fff;border:none;font-size:0.75rem;">
          <i class="fa-solid fa-wand-magic-sparkles"></i> AI Generate Form
        </button>
        <input id="ai-form-prompt" placeholder="e.g. Job application form..." style="width:100%;margin-top:0.5rem;padding:0.45rem 0.65rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:6px;font-size:0.75rem;color:var(--kw-text-primary);outline:none;" onfocus="this.style.borderColor='var(--kw-primary)'" onblur="this.style.borderColor=''">
      </div>

      <!-- Canvas -->
      <div>
        <div style="display:flex;align-items:center;gap:0.65rem;margin-bottom:0.75rem;flex-wrap:wrap;">
          <input id="form-title" value="My Form" style="flex:1;padding:0.5rem 0.75rem;background:var(--kw-bg-card);border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);font-size:0.9rem;font-weight:700;color:var(--kw-text-primary);outline:none;" onfocus="this.style.borderColor='var(--kw-primary)'" onblur="this.style.borderColor=''">
          <button class="kw-btn kw-btn-sm kw-btn-primary" onclick="FB.setTab('preview')"><i class="fa-solid fa-eye"></i> Preview</button>
          <button class="kw-btn kw-btn-sm" onclick="FB.exportHTML()" style="background:var(--kw-bg-card);border-color:var(--kw-border);font-size:0.75rem;"><i class="fa-solid fa-code"></i> Export HTML</button>
          <button class="kw-btn kw-btn-sm" onclick="FB.exportJSON()" style="background:var(--kw-bg-card);border-color:var(--kw-border);font-size:0.75rem;"><i class="fa-solid fa-download"></i> JSON Schema</button>
        </div>

        <!-- Builder / Preview tabs -->
        <div style="display:flex;gap:0;border-bottom:2px solid var(--kw-border);margin-bottom:1rem;">
          <button class="form-tab-btn active" data-tab="builder" onclick="FB.setTab('builder')" style="padding:0.5rem 1.25rem;font-size:0.82rem;font-weight:600;border:none;background:none;cursor:pointer;border-bottom:2px solid var(--kw-primary);margin-bottom:-2px;color:var(--kw-primary);">Builder</button>
          <button class="form-tab-btn" data-tab="preview" onclick="FB.setTab('preview')" style="padding:0.5rem 1.25rem;font-size:0.82rem;font-weight:600;border:none;background:none;cursor:pointer;border-bottom:2px solid transparent;margin-bottom:-2px;color:var(--kw-text-muted);">Preview</button>
          <button class="form-tab-btn" data-tab="code" onclick="FB.setTab('code')" style="padding:0.5rem 1.25rem;font-size:0.82rem;font-weight:600;border:none;background:none;cursor:pointer;border-bottom:2px solid transparent;margin-bottom:-2px;color:var(--kw-text-muted);">HTML Code</button>
        </div>

        <!-- Builder canvas -->
        <div id="form-builder-canvas" class="kw-card" style="min-height:400px;padding:1.25rem;">
          <div id="fields-list" style="display:flex;flex-direction:column;gap:0.65rem;"></div>
          <div id="drop-zone" style="border:2px dashed var(--kw-border);border-radius:var(--kw-radius-md);padding:2rem;text-align:center;color:var(--kw-text-muted);font-size:0.85rem;margin-top:0.75rem;transition:all 0.2s;">
            <i class="fa-solid fa-plus-circle" style="font-size:1.5rem;margin-bottom:0.5rem;display:block;opacity:0.4;"></i>
            Drag fields here or click to add
          </div>
          <button class="kw-btn kw-btn-ghost kw-btn-sm" style="margin-top:0.65rem;" onclick="FB.addField('text')">
            <i class="fa-solid fa-plus"></i> Add Text Field
          </button>
        </div>

        <!-- Preview -->
        <div id="form-preview-tab" style="display:none;" class="kw-card" style="padding:1.25rem;"></div>

        <!-- Code tab -->
        <div id="form-code-tab" style="display:none;" class="kw-card" style="padding:1.25rem;">
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.75rem;">
            <span style="font-size:0.8rem;font-weight:600;">Generated HTML</span>
            <button onclick="navigator.clipboard.writeText(document.getElementById('html-code-output').value);window.Krest?.toast('Copied!','success')" class="kw-btn kw-btn-primary kw-btn-sm"><i class="fa-solid fa-copy"></i> Copy</button>
          </div>
          <textarea id="html-code-output" rows="16" readonly style="width:100%;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);padding:1rem;font-family:monospace;font-size:0.75rem;color:var(--kw-text-secondary);resize:vertical;"></textarea>
        </div>
      </div>

      <!-- Properties -->
      <div style="position:sticky;top:calc(var(--kw-nav-height)+1rem);">
        <div class="kw-card" style="padding:1.25rem;" id="field-props-panel">
          <h5 style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);margin-bottom:1rem;">
            <i class="fa-solid fa-sliders" style="color:var(--kw-primary);"></i> Field Properties
          </h5>
          <p style="font-size:0.8rem;color:var(--kw-text-muted);">Click a field to edit its properties.</p>
        </div>
      </div>

    </div>
  </div>
</section>

<script>
const FB = (() => {
  let fields = [], fieldId = 0, selectedId = null;

  const DEFAULTS = {
    text:{label:'Text Field',placeholder:'Enter text...',required:false},
    email:{label:'Email Address',placeholder:'Enter email...',required:true},
    tel:{label:'Phone Number',placeholder:'+254 700 000 000',required:false},
    number:{label:'Number',placeholder:'0',required:false},
    textarea:{label:'Message',placeholder:'Write here...',required:false,rows:4},
    select:{label:'Select Option',options:'Option 1\nOption 2\nOption 3',required:false},
    checkbox:{label:'I agree to the terms',required:false},
    radio:{label:'Choose one',options:'Option A\nOption B\nOption C',required:false},
    date:{label:'Date',required:false},
    file:{label:'Upload File',accept:'*',required:false},
  };

  function addField(type, extra={}) {
    const id = ++fieldId;
    fields.push({id, type, ...DEFAULTS[type], ...extra});
    document.getElementById('drop-zone').style.display = fields.length ? 'none' : '';
    render(); selectField(id);
  }

  function render() {
    const list = document.getElementById('fields-list');
    list.innerHTML = fields.map((f,i) => `
      <div class="form-field-item" data-id="${f.id}"
           style="display:flex;align-items:center;gap:0.6rem;padding:0.65rem 0.85rem;background:var(--kw-bg-alt);border:1.5px solid ${selectedId===f.id?'var(--kw-primary)':'var(--kw-border)'};border-radius:var(--kw-radius-md);cursor:pointer;transition:all 0.15s;"
           onclick="FB.selectField(${f.id})">
        <i class="fa-solid fa-grip-vertical" style="color:var(--kw-text-muted);font-size:0.75rem;cursor:grab;"></i>
        <div style="flex:1;min-width:0;">
          <div style="font-size:0.82rem;font-weight:600;truncate;">${escHtml(f.label)}</div>
          <div style="font-size:0.68rem;color:var(--kw-text-muted);">${f.type}${f.required?' · required':''}</div>
        </div>
        <div style="display:flex;gap:0.3rem;">
          ${i>0?`<button onclick="event.stopPropagation();FB.moveField(${f.id},-1)" class="kw-btn kw-btn-sm" style="padding:0.2rem 0.4rem;font-size:0.65rem;"><i class="fa-solid fa-chevron-up"></i></button>`:''}
          ${i<fields.length-1?`<button onclick="event.stopPropagation();FB.moveField(${f.id},1)" class="kw-btn kw-btn-sm" style="padding:0.2rem 0.4rem;font-size:0.65rem;"><i class="fa-solid fa-chevron-down"></i></button>`:''}
          <button onclick="event.stopPropagation();FB.removeField(${f.id})" class="kw-btn kw-btn-sm" style="padding:0.2rem 0.4rem;font-size:0.65rem;color:#EF4444;border-color:#EF444430;"><i class="fa-solid fa-trash"></i></button>
        </div>
      </div>
    `).join('');
  }

  function selectField(id) {
    selectedId = id;
    render();
    const f = fields.find(x=>x.id===id); if (!f) return;
    const panel = document.getElementById('field-props-panel');
    const hasOpts = ['select','radio'].includes(f.type);
    panel.innerHTML = `
      <h5 style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-primary);margin-bottom:1rem;">
        <i class="fa-solid fa-sliders"></i> ${f.type} Field
      </h5>
      <label style="font-size:0.75rem;color:var(--kw-text-muted);display:block;margin-bottom:0.2rem;">Label</label>
      <input value="${escHtml(f.label)}" oninput="FB.updateProp(${id},'label',this.value)"
             style="width:100%;padding:0.45rem 0.65rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:6px;font-size:0.82rem;color:var(--kw-text-primary);margin-bottom:0.75rem;outline:none;">
      ${f.type!=='checkbox'&&f.type!=='radio'&&f.type!=='file'?`
        <label style="font-size:0.75rem;color:var(--kw-text-muted);display:block;margin-bottom:0.2rem;">Placeholder</label>
        <input value="${escHtml(f.placeholder||'')}" oninput="FB.updateProp(${id},'placeholder',this.value)"
               style="width:100%;padding:0.45rem 0.65rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:6px;font-size:0.82rem;color:var(--kw-text-primary);margin-bottom:0.75rem;outline:none;">
      `:''}
      ${hasOpts?`
        <label style="font-size:0.75rem;color:var(--kw-text-muted);display:block;margin-bottom:0.2rem;">Options (one per line)</label>
        <textarea rows="4" oninput="FB.updateProp(${id},'options',this.value)"
                  style="width:100%;padding:0.45rem 0.65rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:6px;font-size:0.82rem;color:var(--kw-text-primary);margin-bottom:0.75rem;outline:none;resize:vertical;">${escHtml(f.options||'')}</textarea>
      `:''}
      <label style="display:flex;align-items:center;gap:0.5rem;font-size:0.82rem;cursor:pointer;margin-bottom:0.85rem;">
        <input type="checkbox" ${f.required?'checked':''} onchange="FB.updateProp(${id},'required',this.checked)">
        Required field
      </label>
      <button onclick="FB.removeField(${id})" class="kw-btn kw-btn-sm" style="width:100%;justify-content:center;background:#EF444415;color:#EF4444;border-color:#EF444430;font-size:0.75rem;">
        <i class="fa-solid fa-trash"></i> Remove Field
      </button>
    `;
  }

  function buildPreviewHTML(forExport=false) {
    const title = document.getElementById('form-title').value;
    const wrapClass = forExport ? '' : 'style="display:flex;flex-direction:column;gap:1rem;"';
    let html = forExport
      ? `<!DOCTYPE html>\n<html lang="en">\n<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>${escHtml(title)}</title><style>body{font-family:sans-serif;max-width:600px;margin:2rem auto;padding:1rem;}label{display:block;font-weight:600;margin-bottom:4px;font-size:14px;}input,textarea,select{width:100%;padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:14px;margin-bottom:12px;box-sizing:border-box;}button{background:#F5A800;color:#0A0F1A;padding:12px 24px;border:none;border-radius:999px;font-weight:700;cursor:pointer;font-size:14px;}</style></head>\n<body>\n<h2>${escHtml(title)}</h2>\n<form>\n`
      : `<h3 style="margin-bottom:1rem;">${escHtml(title)}</h3><div ${wrapClass}>`;

    fields.forEach(f => {
      const req = f.required ? ' required' : '';
      const reqBadge = f.required ? ' <span style="color:#EF4444;">*</span>' : '';
      const lblStyle = forExport ? '' : 'style="font-size:0.82rem;font-weight:600;color:var(--kw-text-secondary);display:block;margin-bottom:0.35rem;"';
      const inputStyle = forExport ? '' : 'style="width:100%;padding:0.55rem 0.85rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);font-size:0.875rem;color:var(--kw-text-primary);"';

      if (!forExport) html += '<div>';
      html += `<label ${lblStyle}>${escHtml(f.label)}${reqBadge}</label>\n`;
      switch(f.type) {
        case 'textarea':
          html += `<textarea rows="${f.rows||4}" placeholder="${escHtml(f.placeholder||'')}" ${inputStyle}${req}></textarea>\n`; break;
        case 'select':
          const opts = (f.options||'').split('\n').filter(Boolean);
          html += `<select ${inputStyle}${req}><option value="">-- Select --</option>${opts.map(o=>`<option>${escHtml(o.trim())}</option>`).join('')}</select>\n`; break;
        case 'checkbox':
          html += `<label><input type="checkbox"${req}> ${escHtml(f.label)}</label>\n`; break;
        case 'radio':
          const ropts = (f.options||'').split('\n').filter(Boolean);
          html += ropts.map(o=>`<label><input type="radio" name="r_${f.id}"${req}> ${escHtml(o.trim())}</label>`).join('<br>\n')+'\n'; break;
        default:
          html += `<input type="${f.type}" placeholder="${escHtml(f.placeholder||'')}" ${inputStyle}${req}>\n`;
      }
      if (!forExport) html += '</div>';
    });

    html += forExport
      ? `<button type="submit">Submit</button>\n</form>\n</body>\n</html>`
      : `</div><button class="kw-btn kw-btn-primary" style="margin-top:0.5rem;" type="button">Submit</button>`;

    return html;
  }

  function escHtml(s){ const d=document.createElement('div');d.textContent=String(s||'');return d.innerHTML; }

  // Drag and drop from palette
  document.querySelectorAll('.field-type-item').forEach(el => {
    el.addEventListener('dragstart', e => e.dataTransfer.setData('fieldType', el.dataset.type));
  });
  const dz = document.getElementById('drop-zone');
  dz.addEventListener('dragover', e => { e.preventDefault(); dz.style.borderColor='var(--kw-primary)'; dz.style.background='var(--kw-primary)08'; });
  dz.addEventListener('dragleave', () => { dz.style.borderColor=''; dz.style.background=''; });
  dz.addEventListener('drop', e => { e.preventDefault(); dz.style.borderColor=''; dz.style.background=''; addField(e.dataTransfer.getData('fieldType')||'text'); });
  dz.addEventListener('click', () => addField('text'));

  return {
    addField,
    removeField(id) { fields=fields.filter(f=>f.id!==id); selectedId=null; document.getElementById('field-props-panel').innerHTML='<h5 style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);margin-bottom:1rem;"><i class="fa-solid fa-sliders" style="color:var(--kw-primary);"></i> Field Properties</h5><p style="font-size:0.8rem;color:var(--kw-text-muted);">Click a field to edit.</p>'; render(); if(!fields.length) document.getElementById('drop-zone').style.display=''; },
    moveField(id,dir) { const i=fields.findIndex(f=>f.id===id); const ni=i+dir; if(ni<0||ni>=fields.length)return; [fields[i],fields[ni]]=[fields[ni],fields[i]]; render(); },
    updateProp(id,key,val) { const f=fields.find(x=>x.id===id); if(f){ f[key]=val; render(); } },
    selectField,
    setTab(tab) {
      document.getElementById('form-builder-canvas').style.display = tab==='builder'?'':'none';
      document.getElementById('form-preview-tab').style.display = tab==='preview'?'':'none';
      document.getElementById('form-code-tab').style.display = tab==='code'?'':'none';
      document.querySelectorAll('.form-tab-btn').forEach(b => {
        const active = b.dataset.tab===tab;
        b.style.borderBottomColor = active?'var(--kw-primary)':'transparent';
        b.style.color = active?'var(--kw-primary)':'var(--kw-text-muted)';
      });
      if(tab==='preview') document.getElementById('form-preview-tab').innerHTML = buildPreviewHTML(false);
      if(tab==='code') document.getElementById('html-code-output').value = buildPreviewHTML(true);
    },
    exportHTML() { const b=new Blob([buildPreviewHTML(true)],{type:'text/html'}); const a=document.createElement('a'); a.href=URL.createObjectURL(b); a.download='form.html'; a.click(); },
    exportJSON() {
      const j=JSON.stringify({title:document.getElementById('form-title').value,fields:fields.map(({id,...f})=>f)},null,2);
      const b=new Blob([j],{type:'application/json'}); const a=document.createElement('a'); a.href=URL.createObjectURL(b); a.download='form-schema.json'; a.click();
    },
    async generateAIForm() {
      const prompt = document.getElementById('ai-form-prompt').value.trim() || 'Contact form';
      window.Krest?.toast('AI generating form fields...','info');
      try {
        const resp = await fetch('<?= url('api/ai-assistant') ?>', {
          method:'POST', headers:{'Content-Type':'application/json','X-CSRF-Token':document.querySelector('meta[name="csrf-token"]')?.content||''},
          body:JSON.stringify({messages:[{role:'user',content:`Generate form fields for: "${prompt}". Respond ONLY with a JSON array like: [{"type":"text","label":"Full Name","placeholder":"...","required":true},{"type":"email",...}]. 6-10 fields. No explanation.`}], system:'You are a form design expert. Respond only with valid JSON array of form fields.'})
        });
        const data = await resp.json();
        const text = data.data?.reply || '[]';
        const clean = text.replace(/```json|```/g,'').trim();
        const fieldDefs = JSON.parse(clean);
        if (Array.isArray(fieldDefs)) {
          fields=[]; fieldId=0;
          fieldDefs.forEach(f => addField(f.type||'text', f));
          window.Krest?.toast(`Generated ${fields.length} fields!`,'success');
        }
      } catch(e) { window.Krest?.toast('Could not generate form. Try a different prompt.','error'); }
    }
  };
})();
</script>
<style>
@media(max-width:1024px){ .kw-container > div[style*="200px 1fr 320px"]{grid-template-columns:1fr!important;} }
</style>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>