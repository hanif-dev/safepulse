import { useState, KeyboardEvent } from 'react';

interface Props {
  onSend:  (text: string) => void;
  loading: boolean;
  locale:  string;
}

const PLACEHOLDERS: Record<string, string> = {
  en: 'Ask about scams, radicalization, trafficking…',
  id: 'Tanya tentang penipuan, radikalisasi, TPPO…',
  fr: 'Posez une question sur les arnaques…',
  ar: 'اسأل عن الاحتيال، التطرف…',
  th: 'ถามเกี่ยวกับการหลอกลวง…',
  vi: 'Hỏi về lừa đảo, cực đoan…',
};

export default function MessageInput({ onSend, loading, locale }: Props) {
  const [text, setText] = useState('');
  const placeholder = PLACEHOLDERS[locale] ?? PLACEHOLDERS['en'];

  const handleSend = () => {
    if (!text.trim() || loading) return;
    onSend(text.trim());
    setText('');
  };

  const handleKey = (e: KeyboardEvent<HTMLTextAreaElement>) => {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      handleSend();
    }
  };

  return (
    <div className="px-3 py-3 border-t border-gray-200 dark:border-gray-700 flex gap-2">
      <textarea
        value={text}
        onChange={e => setText(e.target.value)}
        onKeyDown={handleKey}
        placeholder={placeholder}
        rows={2}
        disabled={loading}
        className="flex-1 resize-none rounded-xl border border-gray-200 dark:border-gray-700
                   bg-white dark:bg-gray-900 text-sm px-3 py-2
                   focus:outline-none focus:ring-2 focus:ring-blue-500
                   disabled:opacity-50"
      />
      <button
        onClick={handleSend}
        disabled={!text.trim() || loading}
        className="bg-blue-600 hover:bg-blue-700 disabled:opacity-50
                   text-white rounded-xl px-3 py-2 self-end transition-colors"
        aria-label="Send"
      >
        {loading ? '⏳' : '➤'}
      </button>
    </div>
  );
}
