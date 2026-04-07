import { Routes, Route } from 'react-router-dom';
import { useTheme } from './hooks/useTheme';
import { useFontSize } from './hooks/useFontSize';
import Navbar from './components/Navbar';
import Footer from './components/Footer';

import Home           from './pages/Home';
import Products       from './pages/Products';
import Impact         from './pages/Impact';
import Evidence       from './pages/Evidence';
import Insights       from './pages/Insights';
import InsightDetail  from './pages/InsightDetail';
import Contact        from './pages/Contact';
import Dashboard      from './pages/Dashboard';
import ScamChecker    from './pages/ScamChecker';
import IncidentReport from './pages/IncidentReport';

export default function App() {
  const { theme, toggle: toggleTheme } = useTheme();
  const fontSize = useFontSize();

  return (
    <div className="min-h-screen flex flex-col bg-white dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition-colors">
      <Navbar theme={theme} toggleTheme={toggleTheme} fontSize={fontSize} />

      <main id="main-content" className="flex-1">
        <Routes>
          <Route path="/"                element={<Home />} />
          <Route path="/products"        element={<Products />} />
          <Route path="/impact"          element={<Impact />} />
          <Route path="/evidence"        element={<Evidence />} />
          <Route path="/insights"        element={<Insights />} />
          <Route path="/insights/:slug"  element={<InsightDetail />} />
          <Route path="/contact"         element={<Contact />} />
          <Route path="/dashboard"       element={<Dashboard />} />
          <Route path="/check"           element={<ScamChecker />} />
          <Route path="/report"          element={<IncidentReport />} />
        </Routes>
      </main>

      <Footer />
    </div>
  );
}
