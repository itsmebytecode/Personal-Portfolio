import './bootstrap';

const chatPanel = document.querySelector('#chat-panel');
const launcher = document.querySelector('#chat-launcher');
const messages = document.querySelector('#chat-messages');
const form = document.querySelector('#chat-form');
const input = document.querySelector('#chat-input');
const sendButton = form?.querySelector('.chat-send-button');
const assistantName = chatPanel?.dataset.assistantName || 'Portfolio assistant';
const themeToggle = document.querySelector('#theme-toggle');
const certificateModal = document.querySelector('#certificate-modal');
const certificateModalImage = document.querySelector('#certificate-modal-image');
const certificateModalPdf = document.querySelector('#certificate-modal-pdf');
const certificateModalTitle = document.querySelector('#certificate-modal-title');
const certificateModalIssuer = document.querySelector('#certificate-modal-issuer');
const certificateVerifyLink = document.querySelector('#certificate-verify-link');

const savedTheme = localStorage.getItem('portfolio-theme');
if (savedTheme === 'dark') {
    document.body.classList.add('theme-dark');
    themeToggle?.setAttribute('aria-pressed', 'true');
}

themeToggle?.addEventListener('click', () => {
    const isDark = document.body.classList.toggle('theme-dark');
    themeToggle.setAttribute('aria-pressed', String(isDark));
    localStorage.setItem('portfolio-theme', isDark ? 'dark' : 'light');
});

function openChat() {
    chatPanel.hidden = false;
    launcher.hidden = true;
    input.focus();
}

function closeChat() {
    chatPanel.hidden = true;
    launcher.hidden = false;
    chatPanel.classList.remove('expanded');
}

function updateSendButton() {
    if (!input || !sendButton || !form) return;
    sendButton.disabled = input.value.trim() === '' || form.dataset.waiting === 'true';
}

input?.addEventListener('input', updateSendButton);
updateSendButton();

launcher?.addEventListener('click', openChat);
document.querySelector('#chat-close')?.addEventListener('click', closeChat);
document.querySelector('#chat-expand')?.addEventListener('click', () => chatPanel.classList.toggle('expanded'));

const marqueeMotionAllowed = !window.matchMedia('(prefers-reduced-motion: reduce)').matches;

function updateTechStackFocus() {
    document.querySelectorAll('.tool-marquee').forEach((marquee) => {
        const bounds = marquee.getBoundingClientRect();
        const center = bounds.left + (bounds.width / 2);
        const range = Math.max(bounds.width * 0.48, 1);

        marquee.querySelectorAll('.tool-logo').forEach((logo) => {
            const logoBounds = logo.getBoundingClientRect();
            const distance = Math.abs((logoBounds.left + (logoBounds.width / 2)) - center);
            const closeness = Math.max(0, 1 - (distance / range));
            const smoothCloseness = closeness * closeness * (3 - (2 * closeness));
            logo.style.setProperty('--logo-scale', String(0.82 + (smoothCloseness * 0.62)));
            logo.style.setProperty('--logo-opacity', String(0.62 + (smoothCloseness * 0.38)));
        });
    });

    if (marqueeMotionAllowed) window.requestAnimationFrame(updateTechStackFocus);
}

if (marqueeMotionAllowed) window.requestAnimationFrame(updateTechStackFocus);

function closeCertificateModal() {
    if (!certificateModal) return;
    certificateModal.hidden = true;
    certificateModalImage.src = '';
    certificateModalPdf.src = '';
}

document.querySelectorAll('[data-certificate-preview]').forEach((preview) => {
    preview.addEventListener('click', () => {
        if (!certificateModal) return;
        const isPdf = preview.dataset.documentType === 'pdf';
        certificateModalImage.hidden = isPdf;
        certificateModalPdf.hidden = !isPdf;
        if (isPdf) {
            certificateModalPdf.src = preview.dataset.document;
        } else {
            certificateModalImage.src = preview.dataset.document;
            certificateModalImage.alt = preview.dataset.title;
        }
        certificateModalTitle.textContent = preview.dataset.title;
        certificateModalIssuer.textContent = preview.dataset.issuer || '';
        const verificationUrl = preview.dataset.verificationUrl;
        certificateVerifyLink.hidden = !verificationUrl;
        certificateVerifyLink.href = verificationUrl || '#';
        certificateModal.hidden = false;
        document.querySelector('#certificate-modal-close')?.focus();
    });
});

document.querySelector('#certificate-modal-close')?.addEventListener('click', closeCertificateModal);
document.querySelector('#certificate-modal-backdrop')?.addEventListener('click', closeCertificateModal);
document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && certificateModal && !certificateModal.hidden) closeCertificateModal();
});

function timeNow() {
    return new Intl.DateTimeFormat('en', { hour: '2-digit', minute: '2-digit' }).format(new Date());
}

function addAssistantText(element, text) {
    String(text).split(/(\*\*[^*]+\*\*)/g).forEach((part) => {
        const isBold = part.startsWith('**') && part.endsWith('**');
        const content = isBold ? part.slice(2, -2) : part;
        const fragments = content.split('\n');

        fragments.forEach((fragment, index) => {
            if (index > 0) element.append(document.createElement('br'));
            if (isBold) {
                const strong = document.createElement('strong');
                strong.textContent = fragment;
                element.append(strong);
            } else {
                element.append(document.createTextNode(fragment));
            }
        });
    });
}

function appendMessage(text, type) {
    const wrapper = document.createElement('div');
    wrapper.className = type === 'outgoing' ? 'outgoing-wrap' : 'incoming-wrap';
    const bubble = document.createElement('div');
    bubble.className = `bubble ${type}`;
    if (type === 'incoming') {
        addAssistantText(bubble, text);
    } else {
        bubble.textContent = text;
    }
    const time = document.createElement('time');
    time.className = type === 'outgoing' ? 'chat-time-out' : '';
    time.textContent = timeNow();

    if (type === 'incoming') {
        const avatar = document.createElement('div');
        avatar.className = 'chat-avatar mini';
        const body = document.createElement('div');
        body.append(bubble, time);
        wrapper.append(avatar, body);
    } else {
        wrapper.append(bubble);
        wrapper.append(time);
    }
    messages.append(wrapper);
    messages.scrollTop = messages.scrollHeight;
}

form?.addEventListener('submit', async (event) => {
    event.preventDefault();
    const message = input.value.trim();
    if (!message || form.dataset.waiting === 'true') return;
    appendMessage(message, 'outgoing');
    input.value = '';
    form.dataset.waiting = 'true';
    updateSendButton();
    const typing = document.createElement('div');
    typing.className = 'typing';
    typing.setAttribute('aria-label', `${assistantName} is typing`);
    typing.innerHTML = '<i></i><i></i><i></i>';
    messages.append(typing);
    messages.scrollTop = messages.scrollHeight;

    try {
        const response = await fetch('/api/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ message }),
        });
        const data = await response.json().catch(() => ({ error: 'I’m having trouble replying right now. Please try again shortly.' }));
        typing.remove();
        if (!response.ok) throw new Error(data.error || data.message || 'Sorry, I couldn’t send that. Please try again.');
        appendMessage(data.reply, 'incoming');
    } catch (error) {
        typing.remove();
        appendMessage(error.message || 'The chat service is unavailable right now. Please try again later.', 'incoming');
    } finally {
        form.dataset.waiting = 'false';
        updateSendButton();
        input.focus();
    }
});
