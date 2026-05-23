import { useState } from 'react';
import MessageList from './MessageList';
import MessageInput from './MessageInput';

interface Message {
  role: 'user' | 'assistant';
  content: string;
  sources?: any[];
  loading?: boolean;
}

interface Props {
  token: string | null;
  locale: string;
  apiBase: string;
  onClose: () => void;
}

export default function ChatWindow({ token, locale, apiBase, onClose }: Props) {
  const [messages, setMessages] = useState<Message[]>([
    { role: 'assistant', content: 'Hello! Ask me about online scams, radicalization, trafficking, or child protection.' }
  ]);
  const [loading, setLoading] = useState(false);

  const sendMessage = async (text: string) => {
    if (!text.trim() || loading) return;
    setMessages(prev => [...prev,
      { role: 'user', content: text },
      { role: 'assistant', content: '', loading: true }
    ]);
    setLoading(true);
    try {
      const res = await fetch(`${apiBase}/ai/chat`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ session_token: token ?? 'anonymous', message: text, locale }),
      });
      const data = await res.json();
      setMessages(prev => [...prev.slice(0, -1),
        { role: 'assistant', content: data.answer || 'No response.', sources: data.sources }
      ]);
    } catch {
      setMessages(prev => [...prev.slice(0, -1),
        { role: 'assistant', content: 'Connection error. Please try again.' }
      ]);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="fixed bottom-24 right-6 z-50 w-96 h-[560px] bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden">
      <div className="flex items-center justify-between px-4 py-3 bg-blue-600 text-white">
        <div className="flex items-center gap-2">
          <span>🛡️</span>
          <div>
            <p className="font-bold text-sm">SafePulse Assistant</p>
            <p className="text-xs opacity-75">Educational use only</p>
          </div>
        </div>
        <button onClick={onClose} className="hover:bg-blue-700 rounded-lg p-1">✕</button>
      </div>
      <div className="bg-amber-50 border-b border-amber-200 px-3 py-1">
        <p className="text-xs text-amber-700">⚠️ Preventive and educational only.</p>
      </div>
      <MessageList messages={messages} />
      <MessageInput onSend={sendMessage} loading={loading} locale={locale} />
    </div>
  );
}
