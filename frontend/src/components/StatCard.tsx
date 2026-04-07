interface StatCardProps {
  value: string | number;
  label: string;
  icon?: string;
  highlight?: boolean;
}

export default function StatCard({ value, label, icon, highlight }: StatCardProps) {
  return (
    <div className={`rounded-2xl p-6 flex flex-col gap-2 ${
      highlight
        ? 'bg-primary-600 text-white'
        : 'bg-gray-50 dark:bg-gray-800/60 text-gray-900 dark:text-white'
    }`}>
      {icon && <span className="text-3xl" aria-hidden="true">{icon}</span>}
      <p className={`text-4xl font-extrabold tracking-tight ${highlight ? 'text-white' : 'text-primary-700 dark:text-primary-300'}`}>
        {value}
      </p>
      <p className={`text-sm font-medium ${highlight ? 'text-primary-100' : 'text-gray-500 dark:text-gray-400'}`}>
        {label}
      </p>
    </div>
  );
}
