import { useState, useRef, useEffect, useCallback } from 'react';
import MessageList from './MessageList';
import MessageInput from './MessageInput';

interface Message {
  role:    'user' | 'assistant';
  content: string;
  sources?: any[];
  loading?: boolean;
}

interface Props {
  token:   string | null;
  locale:  string;
  apiBase: string;
  onClose: () => void;
}

export default function ChatWindow({ token, locale, apiBase, onClose }: Props) {
  const [messages, setMessages] = useState<Message[]>([
    {
      role:    'assistant',
      content: getWelcome(locale),
    },
  ]);
  const [loading, setLoading] = useState(false);

  // Draggable state
  const winRef  = useRef<HTMLDivElement>(null);
  const dragRef = useRef({ dragging: false, startX: 0, startY: 0, left: 0, top: 0 });
  const [pos, setPos] = useState({ right: 24, bottom: 24 });

  const onMouseDown = useCallback((e: React.MouseEvent) => {
    if (!winRef.current) return;
    const rect = winRef.current.getBoundingClientRect();
    dragRef.current = {
      dragging: true,
      startX:   e.clientX,
      startY:   e.clientY,
      left:     rect.left,
      top:      rect.top,
    };
    e.preventDefault();
  }, []);

  useEffect(() => {
    const onMove = (e: MouseEvent) => {
      if (!dragRef.current.dragging) return;
      const dx = e.clientX - dragRef.current.startX;
      const dy = e.clientY - dragRef.current.startY;
      setPos({
        right:  Math.max(0, window.innerWidth  - dragRef.current.left - dx - 384),
        bottom: Math.max(0, window.innerHeight - dragRef.current.top  - dy - 600),
      });
    };
    const onUp = () => { dragRef.current.dragging = false; };
    window.addEventListener('mousemove', onMove);
    window.addEventListener('mouseup',   onUp);
    return () => {
      window.removeEventListener('mousemove', onMove);
      window.removeEventListener('mouseup',   onUp);
    };
  }, []);

  const sendMessage = async (text: string) => {
    if (!text.trim() || loading) return;

    setMessages(prev => [
      ...prev,
      { role: 'user', content: text },
      { role: 'assistant', content: '', loading: true },
    ]);
    setLoading(true);

    try {
      const res = await fetch(`${apiBase}/ai/chat`, {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify({
          session_token: token ?? 'anonymous',
          message:       text,
          locale,
        }),
      });
      const data = await res.json();

      setMessages(prev => [
        ...prev.slice(0, -1),
        {
          role:    'assistant',
          content: data.answer || 'No response.',
          sources: data.sources,
        },
      ]);
    } catch {
      setMessages(prev => [
        ...prev.slice(0, -1),
        { role: 'assistant', content: 'Connection error. Please try again.' },
      ]);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div
      ref={winRef}
      style={{ right: pos.right, bottom: pos.bottom }}
      className="fixed z-50 w-96 h-[600px] bg-white dark:bg-gray-900
                 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700
                 flex flex-col overflow-hidden"
    >
      {/* Header — drag handle */}
      <div
        onMouseDown={onMouseDown}
        className="flex items-center justify-between px-4 py-3
                   bg-blue-600 text-white cursor-grab active:cursor-grabbing select-none"
      >
        <div className="flex items-center gap-2">
          <span className="text-lg">🛡️</span>
          <div>
            <p className="font-bold text-sm">SafePulse Assistant</p>
            <p className="text-xs opacity-75">Powered by Groq · Educational only</p>
          </div>
        </div>
        <button
          onClick={onClose}
          className="hover:bg-blue-700 rounded-lg p-1 transition-colors"
          aria-label="Close"
        >
          ✕
        </button>
      </div>

      {/* Safety notice */}
      <div className="bg-amber-50 dark:bg-amber-900/20 border-b border-amber-200 dark:border-amber-800 px-3 py-2">
        <p className="text-xs text-amber-700 dark:text-amber-300">
          ⚠️ Educational and preventive use only. Not a substitute for professional advice.
        </p>
      </div>

      {/* Messages */}
      <MessageList messages={messages} />

      {/* Input */}
      <MessageInput onSend={sendMessage} loading={loading} locale={locale} />
    </div>
  );
}

function getWelcome(locale: string): string {
  const welcomes: Record<string, string> = {
    en: "Hello! I'm the SafePulse Digital Resilience Assistant. Ask me about online scams, counter-radicalization, migrant worker rights, child protection, or any of our 13 crime domains. How can I help?",
    id: "Halo! Saya Asisten Ketahanan Digital SafePulse. Tanyakan tentang penipuan online, kontra-radikalisasi, hak pekerja migran, perlindungan anak, atau 13 domain kejahatan yang kami tangani. Bagaimana saya bisa membantu?",
    fr: "Bonjour! Je suis l'assistant SafePulse. Posez-moi des questions sur les arnaques en ligne, la contre-radicalisation, les droits des travailleurs migrants ou la protection des enfants.",
    ar: "مرحباً! أنا مساعد SafePulse للصمود الرقمي. اسألني عن الاحتيال الإلكتروني، مكافحة التطرف، حقوق العمال المهاجرين، أو حماية الأطفال.",
  };
  return welcomes[locale] ?? welcomes["en"];
}
