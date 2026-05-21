import { useState, useEffect, useRef } from 'react';
import { useLocation } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import ChatWindow from './ChatWindow';

// Pages where the widget should be hidden
const HIDDEN_PATHS = ['/check', '/report', '/admin', '/adaptive'];

export default function AIAssistant() {
  const [open, setOpen]       = useState(false);
  const [token, setToken]     = useState<string | null>(null);
  const location              = useLocation();
  const { i18n }              = useTranslation();
  const apiBase               = import.meta.env.VITE_API_BASE_URL ?? '';

  const hidden = HIDDEN_PATHS.some(p => location.pathname.startsWith(p));
  if (hidden) return null;

  const startSession = async () => {
    if (token) { setOpen(true); return; }
    try {
      const res = await fetch(`${apiBase}/ai/session/start`, {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify({ locale: i18n.language }),
      });
      const data = await res.json();
      setToken(data.session_token);
      setOpen(true);
    } catch {
      setOpen(true); // open anyway, chat will show error
    }
  };

  return (
    <>
      {/* Floating button */}
      {!open && (
        <button
          onClick={startSession}
          aria-label="Open AI Assistant"
          className="fixed bottom-6 right-6 z-40 bg-blue-600 hover:bg-blue-700
                     text-white rounded-full w-14 h-14 flex items-center justify-center
                     shadow-xl transition-transform hover:scale-110 group"
        >
          <span className="text-2xl">💬</span>
          <span className="absolute right-16 bottom-3 bg-gray-900 text-white text-xs
                           rounded-lg px-3 py-1.5 whitespace-nowrap opacity-0
                           group-hover:opacity-100 transition-opacity pointer-events-none">
            Ask the Assistant
          </span>
        </button>
      )}

      {/* Chat window */}
      {open && (
        <ChatWindow
          token={token}
          locale={i18n.language}
          apiBase={apiBase}
          onClose={() => setOpen(false)}
        />
      )}
    </>
  );
}
