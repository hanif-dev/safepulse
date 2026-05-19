import { useState } from 'react';
import { useTranslation } from 'react-i18next';

interface SafetyGateProps {
  onProceed: () => void;
  onExit?: () => void;
}

/**
 * SAMHSA Trauma-Informed Care Principle #1: Safety.
 * Always shown before sensitive flows. Provides quick exit.
 */
export default function SafetyGate({ onProceed, onExit }: SafetyGateProps) {
  const { t } = useTranslation();
  const [acknowledged, setAcknowledged] = useState(false);

  const handleExit = () => {
    if (onExit) onExit();
    else window.location.href = 'https://weather.com';
  };

  return (
    <div className="fixed inset-0 bg-gray-900/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
      <div className="bg-white dark:bg-gray-900 rounded-2xl max-w-lg w-full p-8 shadow-2xl">
        <div className="flex items-start gap-4 mb-6">
          <span className="text-4xl">🛡️</span>
          <div>
            <h2 className="text-xl font-bold text-gray-900 dark:text-white mb-2">
              {t('safety_gate.title', 'Before we continue')}
            </h2>
            <p className="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
              {t('safety_gate.body',
                'Please make sure you are in a safe and private space. The content ahead may bring up difficult feelings. You can exit at any time.')}
            </p>
          </div>
        </div>

        <div className="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 mb-6">
          <p className="text-xs text-blue-700 dark:text-blue-300">
            💡 {t('safety_gate.tip',
              'If you share this device with someone, consider using private browsing. SafePulse stores nothing about you.')}
          </p>
        </div>

        <label className="flex items-start gap-3 cursor-pointer mb-6">
          <input
            type="checkbox"
            checked={acknowledged}
            onChange={(e) => setAcknowledged(e.target.checked)}
            className="mt-1 w-5 h-5 rounded border-gray-300 text-primary-600 focus:ring-primary-500"
          />
          <span className="text-sm text-gray-700 dark:text-gray-300">
            {t('safety_gate.acknowledge',
              'I am in a private space and ready to continue.')}
          </span>
        </label>

        <div className="grid grid-cols-2 gap-3">
          <button
            onClick={handleExit}
            className="px-4 py-3 rounded-xl border-2 border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-semibold hover:bg-gray-100 dark:hover:bg-gray-800"
          >
            🚪 {t('safety_gate.exit', 'Exit Quickly')}
          </button>
          <button
            onClick={onProceed}
            disabled={!acknowledged}
            className="px-4 py-3 rounded-xl bg-primary-600 hover:bg-primary-700 disabled:opacity-50 text-white font-bold"
          >
            {t('safety_gate.proceed', 'Continue')}
          </button>
        </div>
      </div>
    </div>
  );
}
