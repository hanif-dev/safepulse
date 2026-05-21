import { useEffect, useRef } from 'react';

interface Message {
  role:    'user' | 'assistant';
  content: string;
  sources?: any[];
  loading?: boolean;
}

export default function MessageList({ messages }: { messages: Message[] }) {
  const endRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    endRef.current?.scrollIntoView({ behavior: 'smooth' });
  }, [messages]);

  return (
    <div className="flex-1 overflow-y-auto px-4 py-3 space-y-4">
      {messages.map((msg, i) => (
        <div key={i} className={`flex ${msg.role === 'user' ? 'justify-end' : 'justify-start'}`}>
          <div
            className={`max-w-[85%] rounded-2xl px-4 py-2.5 text-sm leading-relaxed ${
              msg.role === 'user'
                ? 'bg-blue-600 text-white'
                : 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100'
            }`}
          >
            {msg.loading ? (
              <span className="animate-pulse">Thinking…</span>
            ) : (
              <>
                <p className="whitespace-pre-wrap">{msg.content}</p>
                {msg.sources && msg.sources.length > 0 && (
                  <div className="mt-2 pt-2 border-t border-gray-300 dark:border-gray-600 space-y-1">
                    <p className="text-xs font-semibold text-gray-500 dark:text-gray-400">Sources:</p>
                    {msg.sources.slice(0, 3).map((s, si) => (
                      <a
                        key={si}
                        href={s.url || '#'}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="block text-xs text-blue-600 dark:text-blue-400 hover:underline truncate"
                      >
                        📄 {s.title} {s.year ? `(${s.year})` : ''} — {s.organization}
                      </a>
                    ))}
                  </div>
                )}
              </>
            )}
          </div>
        </div>
      ))}
      <div ref={endRef} />
    </div>
  );
}
