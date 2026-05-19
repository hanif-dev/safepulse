interface Hotline {
  slug: string;
  name: string;
  contact_channels: Record<string, string>;
  availability: string;
  availability_note?: string;
}

interface HotlineCardProps {
  hotline: Hotline;
}

const AVAILABILITY_LABEL: Record<string, string> = {
  '24_7':           '🟢 24/7',
  'business_hours': '🟡 Business hours',
  'custom':         '🔵 See note',
};

export default function HotlineCard({ hotline }: HotlineCardProps) {
  const channels = hotline.contact_channels;
  const tel  = channels.tel ?? channels.emergency;
  const wa   = channels.whatsapp;
  const url  = channels.url;

  return (
    <div className="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition-shadow">
      <div className="flex items-start justify-between gap-2 mb-3">
        <p className="font-semibold text-gray-900 dark:text-white text-sm">{hotline.name}</p>
        <span className="text-xs whitespace-nowrap">{AVAILABILITY_LABEL[hotline.availability] ?? '⚪'}</span>
      </div>
      {hotline.availability_note && (
        <p className="text-xs text-gray-500 mb-2">{hotline.availability_note}</p>
      )}
      <div className="flex flex-wrap gap-2">
        {tel && (
          <a href={`tel:${tel}`}
             className="text-xs bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 px-3 py-1.5 rounded-full font-medium">
            📞 {tel}
          </a>
        )}
        {wa && (
          <a href={`https://wa.me/${wa.replace(/\D/g, '')}`} target="_blank" rel="noopener noreferrer"
             className="text-xs bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 px-3 py-1.5 rounded-full font-medium">
            💬 WhatsApp
          </a>
        )}
        {url && (
          <a href={url} target="_blank" rel="noopener noreferrer"
             className="text-xs bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-3 py-1.5 rounded-full font-medium">
            🌐 Open Portal
          </a>
        )}
      </div>
    </div>
  );
}
