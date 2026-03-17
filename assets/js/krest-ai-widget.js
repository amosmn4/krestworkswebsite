// ============================================================
// KRESTWORKS — KREST AI CHAT WIDGET
// ============================================================

const KrestAI = (() => {

  const SYSTEM_PROMPT = `You are Krest, the friendly and knowledgeable AI assistant for Krestworks Solutions — a software company that builds AI-powered enterprise systems.

About Krestworks:
- Builds custom enterprise software: HR systems, Procurement, eLearning, Real Estate, Supply Chain, Decision Support, CRM, Hospital Management, POS systems
- Services: Custom Software Development, Web Development, System Integration, AI Solutions, Consulting, Cloud Infrastructure, Mobile Development, Cybersecurity
- AI Hub: Free tools (Document Summarizer, Resume Analyzer, Code Assistant, Meeting Notes) and Premium tools (Business Strategy AI, Financial Analysis AI, Sales Forecasting, Data Insight Generator)
- Innovation Lab: Interactive tools for learning and exploration
- Community platform for tech discussions and questions
- Client Portal for subscription and license management
- Based in Kenya, serving businesses across Africa and beyond

Your role:
- Help visitors understand Krestworks products and services
- Guide users to the right system for their business needs
- Offer to connect users with the demo team or consultation booking
- Answer technical and business questions helpfully
- Be professional, warm, concise, and helpful
- If asked about pricing, direct users to the pricing page or suggest a consultation
- For complex technical questions, provide helpful context then suggest consulting the team

Keep responses concise (2-4 sentences unless detail is needed). Use friendly, professional language.`;

  let messages = [];
  let isOpen = false;
  let isTyping = false;

  // DOM refs (lazily resolved)
  const $ = id => document.getElementById(id);

  function getEls() {
    return {
      chat:      $('kw-ai-chat'),
      messages:  $('ai-chat-messages'),
      input:     $('ai-chat-input'),
      send:      $('ai-chat-send'),
      floatBtn:  $('kw-float-ai'),
    };
  }

  // ---- Render a message bubble ----
  function renderMessage(role, text) {
    const { messages: msgContainer } = getEls();
    if (!msgContainer) return;

    const isBot = role === 'assistant';
    const initial = isBot ? 'K' : (window._kwUser?.name?.[0] || 'U');

    const div = document.createElement('div');
    div.className = `chat-msg ${isBot ? 'bot' : 'user'}`;
    div.innerHTML = `
      <div class="chat-msg-avatar">${initial}</div>
      <div class="chat-bubble">${formatMessage(text)}</div>
    `;

    msgContainer.appendChild(div);
    msgContainer.scrollTop = msgContainer.scrollHeight;
  }

  function formatMessage(text) {
    // Convert markdown-lite to HTML
    return text
      .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
      .replace(/\*(.*?)\*/g, '<em>$1</em>')
      .replace(/`(.*?)`/g, '<code style="background:rgba(245,168,0,0.15);padding:0.1em 0.3em;border-radius:3px;font-size:0.85em">$1</code>')
      .replace(/\n/g, '<br>');
  }

  // ---- Typing indicator ----
  function showTyping() {
    const { messages: msgContainer } = getEls();
    if (!msgContainer) return;

    const div = document.createElement('div');
    div.className = 'chat-msg bot';
    div.id = 'ai-typing-indicator';
    div.innerHTML = `
      <div class="chat-msg-avatar">K</div>
      <div class="chat-bubble chat-typing">
        <span></span><span></span><span></span>
      </div>
    `;
    msgContainer.appendChild(div);
    msgContainer.scrollTop = msgContainer.scrollHeight;
  }

  function hideTyping() {
    document.getElementById('ai-typing-indicator')?.remove();
  }

  // ---- Send message ----
  async function sendMessage(text) {
    if (!text.trim() || isTyping) return;

    const { input, send } = getEls();

    // Add user message
    messages.push({ role: 'user', content: text });
    renderMessage('user', text);

    if (input)  { input.value = ''; input.style.height = 'auto'; }
    if (send)   send.disabled = true;
    isTyping = true;

    showTyping();

    try {
      const res = await fetch('/api/ai-assistant', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
        },
        body: JSON.stringify({
          messages,
          system: SYSTEM_PROMPT,
        }),
      });

      const data = await res.json();
      hideTyping();

      if (data.success && data.reply) {
        messages.push({ role: 'assistant', content: data.reply });
        renderMessage('assistant', data.reply);
      } else {
        renderMessage('assistant', 'I\'m having trouble connecting right now. Please try again or <a href="/contact" style="color:var(--kw-primary)">contact us directly</a>.');
      }
    } catch (err) {
      hideTyping();
      renderMessage('assistant', 'Connection error. Please check your internet and try again.');
    } finally {
      isTyping = false;
      if (send) send.disabled = false;
      if (input) input.focus();
    }
  }

  // ---- Toggle open/close ----
  function toggle() {
    const { chat } = getEls();
    if (!chat) return;

    isOpen = !isOpen;
    chat.classList.toggle('open', isOpen);

    if (isOpen && messages.length === 0) {
      // Welcome message
      setTimeout(() => {
        renderMessage('assistant', 'Hi! 👋 I\'m **Krest**, your AI assistant. I can help you explore our products, services, and find the right solution for your business. What can I help you with today?');
      }, 300);
    }

    if (isOpen) {
      getEls().input?.focus();
    }
  }

  // ---- Init ----
  function init() {
    const { input, send, floatBtn } = getEls();

    floatBtn?.addEventListener('click', toggle);
    document.getElementById('ai-chat-close')?.addEventListener('click', toggle);

    // Send on button click
    send?.addEventListener('click', () => {
      sendMessage(input?.value || '');
    });

    // Send on Enter (Shift+Enter = newline)
    input?.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage(input.value);
      }
    });

    // Auto-resize textarea
    input?.addEventListener('input', () => {
      input.style.height = 'auto';
      input.style.height = Math.min(input.scrollHeight, 120) + 'px';
    });
  }

  return { init, toggle, sendMessage };
})();

document.addEventListener('DOMContentLoaded', KrestAI.init);
window.KrestAI = KrestAI;