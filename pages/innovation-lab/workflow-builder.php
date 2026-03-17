<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title       = 'Workflow Automation Builder — Innovation Lab — ' . APP_NAME;
$page_description = 'Drag-and-drop workflow automation builder. Design, visualize, and export business process automation flows interactively.';

?>

<section class="kw-page-hero">
  <div class="kw-container">
    <div class="kw-breadcrumb"><a href="<?= url() ?>">Home</a><i class="fa-solid fa-chevron-right"></i><a href="<?= url('innovation-lab') ?>">Innovation Lab</a><i class="fa-solid fa-chevron-right"></i><span class="current">Workflow Builder</span></div>
    <div data-aos="fade-up" style="padding-bottom:2rem;">
      <span class="label"><i class="fa-solid fa-diagram-project"></i> Builder Tool</span>
      <h1>Workflow Automation Builder</h1>
      <p>Design process automation flows visually. Add triggers, actions, conditions, and connectors — then export as JSON or share with your team.</p>
    </div>
  </div>
</section>

<section style="background:var(--kw-bg);padding:1.5rem 0 4rem;">
  <div class="kw-container" style="max-width:100%;padding:0 1.5rem;">

    <!-- Toolbar -->
    <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1rem;flex-wrap:wrap;background:var(--kw-bg-card);border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);padding:0.75rem 1rem;">
      <span style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--kw-text-muted);margin-right:0.5rem;">Add Node:</span>
      <?php foreach ([
        ['trigger','fa-bolt','#F5A800','Trigger'],
        ['action','fa-play','#3B82F6','Action'],
        ['condition','fa-code-branch','#22C55E','Condition'],
        ['delay','fa-clock','#A855F7','Delay'],
        ['notification','fa-bell','#EF4444','Notify'],
        ['end','fa-flag-checkered','#6B7280','End'],
      ] as $n): ?>
      <button class="add-node-btn kw-btn kw-btn-sm" data-type="<?= $n[0] ?>" data-color="<?= $n[1] ?>" data-icon="<?= $n[2] ?>"
              style="font-size:0.75rem;border-color:<?= $n[1] ?>30;color:<?= $n[1] ?>;">
        <i class="fa-solid <?= $n[2] ?>" style="font-size:0.7rem;"></i> <?= $n[3] ?>
      </button>
      <?php endforeach; ?>
      <div style="margin-left:auto;display:flex;gap:0.5rem;">
        <button id="clear-canvas" class="kw-btn kw-btn-ghost kw-btn-sm"><i class="fa-solid fa-trash"></i> Clear</button>
        <button id="export-json" class="kw-btn kw-btn-sm" style="background:var(--kw-primary);color:#0A0F1A;border-color:var(--kw-primary);font-size:0.75rem;"><i class="fa-solid fa-download"></i> Export JSON</button>
        <button id="ai-suggest" class="kw-btn kw-btn-sm" style="background:linear-gradient(135deg,#7C3AED,#A855F7);color:#fff;border:none;font-size:0.75rem;"><i class="fa-solid fa-wand-magic-sparkles"></i> AI Suggest</button>
      </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 280px;gap:1rem;align-items:flex-start;">

      <!-- Canvas -->
      <div style="position:relative;">
        <canvas id="workflow-canvas" width="900" height="540"
                style="background:var(--kw-bg-card);border:1px solid var(--kw-border);border-radius:var(--kw-radius-lg);cursor:default;width:100%;display:block;"></canvas>
        <div id="canvas-hint" style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;pointer-events:none;">
          <i class="fa-solid fa-diagram-project" style="font-size:2.5rem;color:var(--kw-text-muted);opacity:0.3;margin-bottom:0.75rem;display:block;"></i>
          <p style="color:var(--kw-text-muted);opacity:0.5;font-size:0.875rem;">Click a node button above to add it to the canvas<br>Then drag to connect nodes</p>
        </div>
      </div>

      <!-- Properties panel -->
      <div style="display:flex;flex-direction:column;gap:1rem;">
        <div class="kw-card" style="padding:1.25rem;" id="properties-panel">
          <h5 style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);margin-bottom:1rem;">
            <i class="fa-solid fa-sliders" style="color:var(--kw-primary);"></i> Node Properties
          </h5>
          <p style="font-size:0.8rem;color:var(--kw-text-muted);">Click any node on the canvas to edit its properties.</p>
        </div>

        <div class="kw-card" style="padding:1.25rem;">
          <h5 style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);margin-bottom:0.85rem;">
            <i class="fa-solid fa-lightbulb" style="color:var(--kw-primary);"></i> Quick Templates
          </h5>
          <?php foreach ([
            ['Employee Onboarding','fa-user-plus','trigger→action→condition→action→notify→end'],
            ['Invoice Approval','fa-file-invoice','trigger→condition→action→notify→end'],
            ['Support Ticket','fa-headset','trigger→action→condition→delay→notify→end'],
            ['Sales Pipeline','fa-funnel-dollar','trigger→action→action→condition→end'],
          ] as $t): ?>
          <button class="load-template kw-btn kw-btn-ghost kw-btn-sm" data-flow="<?= $t[2] ?>"
                  style="width:100%;justify-content:flex-start;font-size:0.78rem;margin-bottom:0.35rem;">
            <i class="fa-solid <?= $t[1] ?>" style="color:var(--kw-primary);width:16px;"></i> <?= $t[0] ?>
          </button>
          <?php endforeach; ?>
        </div>

        <div class="kw-card" style="padding:1.25rem;" id="flow-stats">
          <h5 style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);margin-bottom:0.85rem;">Flow Stats</h5>
          <div id="stats-content" style="font-size:0.8rem;color:var(--kw-text-muted);">No nodes added yet.</div>
        </div>

        <div class="kw-card" style="padding:1.25rem;background:linear-gradient(135deg,#7C3AED15,#A855F715);border-color:#A855F730;" id="ai-suggestion-panel" style="display:none;">
          <h5 style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.08em;color:#A855F7;margin-bottom:0.75rem;">
            <i class="fa-solid fa-wand-magic-sparkles"></i> AI Suggestion
          </h5>
          <div id="ai-suggestion-text" style="font-size:0.8rem;color:var(--kw-text-secondary);line-height:1.6;"></div>
        </div>
      </div>

    </div>

    <!-- Export modal -->
    <div id="export-modal" style="display:none;margin-top:1rem;">
      <div class="kw-card" style="padding:1.5rem;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
          <h4 style="font-size:0.95rem;">Workflow JSON Export</h4>
          <button onclick="document.getElementById('export-modal').style.display='none'" class="kw-btn kw-btn-ghost kw-btn-sm"><i class="fa-solid fa-times"></i></button>
        </div>
        <textarea id="export-output" rows="10" readonly
                  style="width:100%;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);padding:1rem;font-family:monospace;font-size:0.78rem;color:var(--kw-text-secondary);resize:vertical;"></textarea>
        <button onclick="navigator.clipboard.writeText(document.getElementById('export-output').value);window.Krest?.toast('Copied to clipboard!','success')" class="kw-btn kw-btn-primary kw-btn-sm" style="margin-top:0.75rem;">
          <i class="fa-solid fa-copy"></i> Copy to Clipboard
        </button>
      </div>
    </div>
  </div>
</section>

<script>
const WF = (() => {
  const canvas = document.getElementById('workflow-canvas');
  const ctx    = canvas.getContext('2d');
  const hint   = document.getElementById('canvas-hint');

  const TYPE_COLORS = {
    trigger:      '#F5A800',
    action:       '#3B82F6',
    condition:    '#22C55E',
    delay:        '#A855F7',
    notification: '#EF4444',
    end:          '#6B7280',
  };
  const TYPE_ICONS = {
    trigger:'⚡', action:'▶', condition:'⑂', delay:'⏱', notification:'🔔', end:'⚑'
  };

  let nodes = [], connections = [], nodeId = 0;
  let dragging = null, dragOffX = 0, dragOffY = 0;
  let connecting = null, mouseX = 0, mouseY = 0;
  let selectedNode = null;

  function addNode(type) {
    hint.style.display = 'none';
    const x = 80 + (nodes.length % 4) * 190;
    const y = 80 + Math.floor(nodes.length / 4) * 140;
    nodes.push({ id: ++nodeId, type, x, y, label: ucfirst(type) + ' ' + nodeId, color: TYPE_COLORS[type] });
    draw(); updateStats();
  }

  function draw() {
    // Get actual pixel size
    const W = canvas.offsetWidth, H = canvas.height;
    canvas.width  = canvas.offsetWidth;
    canvas.height = 540;
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Grid dots
    ctx.fillStyle = getComputedStyle(document.documentElement).getPropertyValue('--kw-border') || '#1F2937';
    for (let x = 20; x < canvas.width; x += 30)
      for (let y = 20; y < canvas.height; y += 30)
        { ctx.beginPath(); ctx.arc(x,y,1,0,Math.PI*2); ctx.fill(); }

    // Connections
    connections.forEach(c => {
      const from = nodes.find(n => n.id === c.from);
      const to   = nodes.find(n => n.id === c.to);
      if (!from || !to) return;
      drawArrow(from.x+80, from.y+22, to.x, to.y+22, from.color);
    });

    // Live connection line
    if (connecting) {
      const from = nodes.find(n => n.id === connecting);
      if (from) {
        ctx.strokeStyle = '#F5A800';
        ctx.lineWidth = 2;
        ctx.setLineDash([6,3]);
        ctx.beginPath();
        ctx.moveTo(from.x+80, from.y+22);
        ctx.lineTo(mouseX, mouseY);
        ctx.stroke();
        ctx.setLineDash([]);
      }
    }

    // Nodes
    nodes.forEach(n => {
      const sel = selectedNode?.id === n.id;
      // Shadow
      ctx.shadowColor = n.color + '50';
      ctx.shadowBlur  = sel ? 16 : 8;

      // Node body
      ctx.fillStyle   = getComputedStyle(document.documentElement).getPropertyValue('--kw-bg-card') || '#111827';
      roundRect(n.x, n.y, 160, 44, 8);
      ctx.fill();

      // Left color bar
      ctx.fillStyle = n.color;
      roundRect(n.x, n.y, 6, 44, [8,0,0,8]);
      ctx.fill();

      // Border
      ctx.strokeStyle = sel ? n.color : (n.color + '50');
      ctx.lineWidth   = sel ? 2 : 1;
      roundRect(n.x, n.y, 160, 44, 8);
      ctx.stroke();
      ctx.shadowBlur  = 0;

      // Icon
      ctx.font        = '16px sans-serif';
      ctx.fillStyle   = n.color;
      ctx.fillText(TYPE_ICONS[n.type] || '●', n.x+14, n.y+27);

      // Label
      ctx.font        = '600 11px Inter, sans-serif';
      ctx.fillStyle   = getComputedStyle(document.documentElement).getPropertyValue('--kw-text-primary') || '#F9FAFB';
      ctx.fillText(n.label.substring(0,18), n.x+34, n.y+18);

      // Type sub
      ctx.font        = '10px Inter, sans-serif';
      ctx.fillStyle   = n.color + 'AA';
      ctx.fillText(n.type.toUpperCase(), n.x+34, n.y+32);

      // Connect dot (right side)
      ctx.beginPath();
      ctx.arc(n.x+162, n.y+22, 5, 0, Math.PI*2);
      ctx.fillStyle = n.color;
      ctx.fill();
    });
  }

  function drawArrow(x1,y1,x2,y2,color) {
    const dx = x2-x1, dy = y2-y1;
    const angle = Math.atan2(dy,dx);
    const cp1x = x1 + dx*0.4, cp1y = y1;
    const cp2x = x2 - dx*0.4, cp2y = y2;

    ctx.strokeStyle = color || '#F5A800';
    ctx.lineWidth   = 2;
    ctx.setLineDash([]);
    ctx.beginPath();
    ctx.moveTo(x1,y1);
    ctx.bezierCurveTo(cp1x,cp1y,cp2x,cp2y,x2,y2);
    ctx.stroke();

    // Arrowhead
    ctx.fillStyle = color;
    ctx.beginPath();
    ctx.moveTo(x2, y2);
    ctx.lineTo(x2 - 10*Math.cos(angle-0.4), y2 - 10*Math.sin(angle-0.4));
    ctx.lineTo(x2 - 10*Math.cos(angle+0.4), y2 - 10*Math.sin(angle+0.4));
    ctx.closePath();
    ctx.fill();
  }

  function roundRect(x,y,w,h,r) {
    const rad = typeof r === 'number' ? [r,r,r,r] : r;
    ctx.beginPath();
    ctx.moveTo(x+rad[0],y);
    ctx.lineTo(x+w-rad[1],y); ctx.arcTo(x+w,y,x+w,y+rad[1],rad[1]);
    ctx.lineTo(x+w,y+h-rad[2]); ctx.arcTo(x+w,y+h,x+w-rad[2],y+h,rad[2]);
    ctx.lineTo(x+rad[3],y+h); ctx.arcTo(x,y+h,x,y+h-rad[3],rad[3]);
    ctx.lineTo(x,y+rad[0]); ctx.arcTo(x,y,x+rad[0],y,rad[0]);
    ctx.closePath();
  }

  function nodeAt(x,y) {
    return nodes.slice().reverse().find(n => x>=n.x && x<=n.x+160 && y>=n.y && y<=n.y+44);
  }

  function getCanvasXY(e) {
    const rect = canvas.getBoundingClientRect();
    const scaleX = canvas.width / rect.width;
    const scaleY = canvas.height / rect.height;
    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
    const clientY = e.touches ? e.touches[0].clientY : e.clientY;
    return [(clientX - rect.left) * scaleX, (clientY - rect.top) * scaleY];
  }

  canvas.addEventListener('mousedown', e => {
    const [x,y] = getCanvasXY(e);
    const n = nodeAt(x,y);
    if (!n) { selectedNode = null; showProperties(null); draw(); return; }

    // Check connect dot
    if (Math.abs(x - (n.x+162)) < 10 && Math.abs(y - (n.y+22)) < 10) {
      connecting = n.id; return;
    }

    selectedNode = n; showProperties(n);
    dragging = n; dragOffX = x - n.x; dragOffY = y - n.y;
    draw();
  });

  canvas.addEventListener('mousemove', e => {
    const [x,y] = getCanvasXY(e);
    mouseX = x; mouseY = y;
    if (dragging) { dragging.x = x - dragOffX; dragging.y = y - dragOffY; draw(); }
    if (connecting) draw();
    canvas.style.cursor = nodeAt(x,y) ? 'grab' : 'default';
  });

  canvas.addEventListener('mouseup', e => {
    const [x,y] = getCanvasXY(e);
    if (connecting !== null) {
      const target = nodeAt(x,y);
      if (target && target.id !== connecting) {
        // avoid dupes
        if (!connections.find(c => c.from===connecting && c.to===target.id)) {
          connections.push({ from: connecting, to: target.id });
        }
      }
      connecting = null;
    }
    dragging = null; draw();
  });

  canvas.addEventListener('dblclick', e => {
    const [x,y] = getCanvasXY(e);
    const n = nodeAt(x,y);
    if (!n) return;
    const newLabel = prompt('Edit node label:', n.label);
    if (newLabel !== null) { n.label = newLabel.trim() || n.label; draw(); }
  });

  function showProperties(n) {
    const panel = document.getElementById('properties-panel');
    if (!n) {
      panel.innerHTML = '<h5 style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);margin-bottom:1rem;"><i class="fa-solid fa-sliders" style="color:var(--kw-primary);"></i> Node Properties</h5><p style="font-size:0.8rem;color:var(--kw-text-muted);">Click any node on the canvas to edit its properties.</p>';
      return;
    }
    panel.innerHTML = `
      <h5 style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.08em;color:${n.color};margin-bottom:1rem;">
        <i class="fa-solid fa-sliders"></i> ${ucfirst(n.type)} Node
      </h5>
      <label style="font-size:0.75rem;color:var(--kw-text-muted);display:block;margin-bottom:0.25rem;">Label</label>
      <input id="prop-label" value="${escHtml(n.label)}"
             style="width:100%;padding:0.5rem 0.75rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:6px;font-size:0.82rem;color:var(--kw-text-primary);margin-bottom:0.85rem;outline:none;"
             oninput="WF.updateLabel(${n.id},this.value)">
      <div style="font-size:0.75rem;color:var(--kw-text-muted);margin-bottom:0.85rem;">
        <div style="display:flex;justify-content:space-between;padding:0.25rem 0;border-bottom:1px solid var(--kw-border);">
          <span>Type</span><span style="color:${n.color};font-weight:600;">${n.type}</span>
        </div>
        <div style="display:flex;justify-content:space-between;padding:0.25rem 0;border-bottom:1px solid var(--kw-border);">
          <span>Node ID</span><span>#${n.id}</span>
        </div>
        <div style="display:flex;justify-content:space-between;padding:0.25rem 0;">
          <span>Position</span><span>${Math.round(n.x)}, ${Math.round(n.y)}</span>
        </div>
      </div>
      <button onclick="WF.deleteNode(${n.id})" class="kw-btn kw-btn-sm" style="background:#EF444415;color:#EF4444;border-color:#EF444430;width:100%;justify-content:center;font-size:0.75rem;">
        <i class="fa-solid fa-trash"></i> Delete Node
      </button>
    `;
  }

  function updateStats() {
    const el = document.getElementById('stats-content');
    const typeCounts = {};
    nodes.forEach(n => typeCounts[n.type] = (typeCounts[n.type]||0)+1);
    if (!nodes.length) { el.innerHTML = 'No nodes added yet.'; return; }
    el.innerHTML = `
      <div style="display:flex;justify-content:space-between;font-size:0.78rem;padding:0.2rem 0;border-bottom:1px solid var(--kw-border);"><span style="color:var(--kw-text-muted);">Total Nodes</span><strong>${nodes.length}</strong></div>
      <div style="display:flex;justify-content:space-between;font-size:0.78rem;padding:0.2rem 0;border-bottom:1px solid var(--kw-border);"><span style="color:var(--kw-text-muted);">Connections</span><strong>${connections.length}</strong></div>
      ${Object.entries(typeCounts).map(([t,c]) => `<div style="display:flex;justify-content:space-between;font-size:0.78rem;padding:0.2rem 0;border-bottom:1px solid var(--kw-border);"><span style="color:${TYPE_COLORS[t]||'#aaa'};">${ucfirst(t)}</span><strong>${c}</strong></div>`).join('')}
    `;
  }

  function ucfirst(s) { return s.charAt(0).toUpperCase() + s.slice(1); }
  function escHtml(s) { const d=document.createElement('div');d.textContent=s;return d.innerHTML; }

  // Public API
  return {
    addNode,
    deleteNode(id) {
      nodes = nodes.filter(n => n.id !== id);
      connections = connections.filter(c => c.from!==id && c.to!==id);
      selectedNode = null; showProperties(null); draw(); updateStats();
      if (!nodes.length) hint.style.display = 'block';
    },
    updateLabel(id, val) {
      const n = nodes.find(n => n.id===id);
      if (n) { n.label = val; draw(); }
    },
    exportJSON() {
      return JSON.stringify({ nodes: nodes.map(n=>({id:n.id,type:n.type,label:n.label,x:Math.round(n.x),y:Math.round(n.y)})), connections }, null, 2);
    },
    loadTemplate(flow) {
      nodes = []; connections = []; nodeId = 0;
      hint.style.display = 'none';
      const types = flow.split('→');
      types.forEach((t,i) => {
        nodes.push({ id: ++nodeId, type: t, x: 30 + i*190, y: 220, label: ucfirst(t) + ' ' + nodeId, color: TYPE_COLORS[t] || '#6B7280' });
      });
      for (let i=0; i<nodes.length-1; i++) connections.push({from:nodes[i].id,to:nodes[i+1].id});
      selectedNode = null; showProperties(null); draw(); updateStats();
    },
    async aiSuggest() {
      const panel = document.getElementById('ai-suggestion-panel');
      const text  = document.getElementById('ai-suggestion-text');
      panel.style.display = 'block';
      text.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Analysing your workflow...';
      const summary = `I have a workflow with ${nodes.length} nodes: ${nodes.map(n=>n.label+' ('+n.type+')').join(', ')}. ${connections.length} connections. Suggest improvements in 3 bullet points.`;
      try {
        const resp = await fetch('<?= url('api/ai-assistant') ?>', {
          method:'POST',
          headers:{'Content-Type':'application/json','X-CSRF-Token':document.querySelector('meta[name="csrf-token"]')?.content||''},
          body: JSON.stringify({messages:[{role:'user',content:summary}],system:'You are a business process automation expert. Keep suggestions concise, max 3 bullet points.'})
        });
        const data = await resp.json();
        text.innerHTML = (data.data?.reply || 'No suggestion available.').replace(/\n/g,'<br>').replace(/\*\*(.*?)\*\*/g,'<strong>$1</strong>');
      } catch(e) { text.textContent = 'Could not fetch AI suggestion.'; }
    }
  };
})();

// Wire up buttons
document.querySelectorAll('.add-node-btn').forEach(btn => {
  btn.addEventListener('click', () => WF.addNode(btn.dataset.type));
});
document.getElementById('clear-canvas').addEventListener('click', () => {
  if (confirm('Clear all nodes?')) { WF.loadTemplate('trigger'); setTimeout(()=>{document.getElementById('workflow-canvas').getContext('2d').clearRect(0,0,900,540);location.reload();},100); }
});
document.getElementById('export-json').addEventListener('click', () => {
  document.getElementById('export-modal').style.display = 'block';
  document.getElementById('export-output').value = WF.exportJSON();
});
document.getElementById('ai-suggest').addEventListener('click', WF.aiSuggest);
document.querySelectorAll('.load-template').forEach(btn => {
  btn.addEventListener('click', () => WF.loadTemplate(btn.dataset.flow));
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>