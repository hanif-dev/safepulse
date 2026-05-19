/**
 * Emergency Exit Button.
 * Available on every sensitive page. Clicking redirects immediately
 * to a neutral site (weather.com by default) and clears history if possible.
 */
export default function EmergencyExitButton() {
  const handleExit = () => {
    try { window.history.replaceState({}, '', 'about:blank'); } catch {}
    window.location.replace('https://weather.com');
  };

  return (
    <button
      onClick={handleExit}
      aria-label="Emergency exit"
      title="Quick Exit"
      className="fixed bottom-6 right-6 z-40 bg-red-600 hover:bg-red-700 text-white font-bold w-14 h-14 rounded-full shadow-lg flex items-center justify-center transition-transform hover:scale-110"
    >
      🚪
    </button>
  );
}
