interface ScoreGaugeProps {
  score: number;
  level: 'Low' | 'Medium' | 'High';
}

const LEVEL_STYLES = {
  Low:    { bar: 'bg-accent-500',  text: 'text-accent-600 dark:text-accent-400',  bg: 'bg-accent-50 dark:bg-accent-900/20',  border: 'border-accent-200 dark:border-accent-800' },
  Medium: { bar: 'bg-warning-500', text: 'text-warning-600 dark:text-warning-400', bg: 'bg-warning-50 dark:bg-warning-900/20', border: 'border-warning-200 dark:border-warning-700' },
  High:   { bar: 'bg-danger-500',  text: 'text-danger-600 dark:text-danger-400',   bg: 'bg-danger-50 dark:bg-danger-900/20',  border: 'border-danger-200 dark:border-danger-800' },
};

export default function ScoreGauge({ score, level }: ScoreGaugeProps) {
  const s = LEVEL_STYLES[level];
  return (
    <div className={`rounded-2xl border p-6 ${s.bg} ${s.border}`} role="status" aria-live="polite">
      <div className="flex items-end justify-between mb-4">
        <div>
          <p className="text-xs font-semibold uppercase tracking-widest text-gray-500 dark:text-gray-400 mb-1">Risk Score</p>
          <p className={`text-5xl font-extrabold ${s.text}`}>{score}<span className="text-2xl font-medium text-gray-400">/100</span></p>
        </div>
        <span className={`text-lg font-bold px-4 py-1.5 rounded-full border ${s.text} ${s.border} bg-white dark:bg-gray-900`}>
          {level} Risk
        </span>
      </div>
      {/* Progress bar */}
      <div className="h-3 w-full bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden" aria-hidden="true">
        <div
          className={`h-full rounded-full transition-all duration-700 ${s.bar}`}
          style={{ width: `${score}%` }}
        />
      </div>
    </div>
  );
}
