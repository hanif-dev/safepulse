import { useEffect, useState } from 'react';
import { adminListDocs, adminCreateDoc, adminDisableDoc, adminGetStatus, KnowledgeDoc, KnowledgeStatus } from '../services/api';

const TABS = ['documents','add','status'] as const;
type Tab = typeof TABS[number];

const TOKEN_STORAGE_KEY = 'safepulse_admin_token';

export default function Admin() {
  const [token, setToken]     = useState<string>(() => localStorage.getItem(TOKEN_STORAGE_KEY) ?? '');
  const [authed, setAuthed]   = useState(false);
  const [tab, setTab]         = useState<Tab>('documents');
  const [docs, setDocs]       = useState<KnowledgeDoc[]>([]);
  const [status, setStatus]   = useState<KnowledgeStatus | null>(null);
  const [loading, setLoading] = useState(false);
  const [error, setError]     = useState('');
  const [success, setSuccess] = useState('');

  // Form
  const [form, setForm] = useState({
    title:'', source:'', organization:'',
    topic:'phishing', region:'Indonesia', language:'en',
    year:'2024', source_url:'', description:'', content:'',
  });

  const tryLogin = async () => {
    if (!token.trim()) { setError('Token required'); return; }
    setLoading(true); setError('');
    try {
      const s = await adminGetStatus(token);
      setStatus(s);
      setAuthed(true);
      localStorage.setItem(TOKEN_STORAGE_KEY, token);
      await loadDocs();
    } catch (e: any) {
      setError(e?.response?.data?.message ?? 'Invalid token or server error');
    } finally {
      setLoading(false);
    }
  };

  const loadDocs = async () => {
    try {
      const res = await adminListDocs(token);
      setDocs(res.data);
    } catch (e: any) {
      setError(e?.response?.data?.message ?? 'Failed to load documents');
    }
  };

  const loadStatus = async () => {
    try {
      const s = await adminGetStatus(token);
      setStatus(s);
    } catch (e: any) {
      setError(e?.response?.data?.message ?? 'Failed to load status');
    }
  };

  const handleAdd = async () => {
    if (!form.title || !form.topic) { setError('Title and topic are required'); return; }
    setLoading(true); setError(''); setSuccess('');
    try {
      await adminCreateDoc(token, {
        ...form,
        year: form.year ? parseInt(form.year) : undefined,
      });
      setSuccess('Document added to knowledge base.');
      setForm({ title:'', source:'', organization:'', topic:'phishing', region:'Indonesia', language:'en', year:'2024', source_url:'', description:'', content:'' });
      await loadDocs();
      await loadStatus();
    } catch (e: any) {
      setError(e?.response?.data?.message ?? 'Failed to add document');
    } finally {
      setLoading(false);
    }
  };

  const handleDisable = async (id: number) => {
    if (!confirm('Disable this document?')) return;
    setLoading(true);
    try {
      await adminDisableDoc(token, id);
      await loadDocs();
    } finally {
      setLoading(false);
    }
  };

  // Auto-attempt login on mount if token stored
  useEffect(() => {
    if (token) tryLogin();
  }, []); // eslint-disable-line react-hooks/exhaustive-deps

  // ── LOGIN VIEW ─────────────────────────────────────────────────────────
  if (!authed) {
    return (
      <section className="max-w-md mx-auto px-4 py-24">
        <div className="text-center mb-8">
          <span className="text-5xl block mb-4">🔐</span>
          <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Admin Panel</h1>
          <p className="text-sm text-gray-500 dark:text-gray-400 mt-2">
            Developer access only. Provide your X-Admin-Token to manage the knowledge base.
          </p>
        </div>

        <div className="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-4 mb-6 text-xs text-amber-800 dark:text-amber-200">
          ⚠️ This panel allows knowledge-base management. Only documents from trusted sources (INTERPOL, UN, ICCT, BNPT, OJK, peer-reviewed journals) should be added to maintain answer quality.
        </div>

        <input
          type="password" value={token} onChange={(e) => setToken(e.target.value)}
          placeholder="X-Admin-Token"
          className="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-xl px-4 py-3 text-sm mb-3"
          onKeyDown={(e) => e.key === 'Enter' && tryLogin()}
        />
        {error && <p className="text-sm text-red-600 mb-3">{error}</p>}
        <button onClick={tryLogin} disabled={loading}
          className="w-full bg-primary-600 hover:bg-primary-700 disabled:opacity-60 text-white font-bold py-3 rounded-xl">
          {loading ? 'Verifying…' : 'Authenticate'}
        </button>
      </section>
    );
  }

  // ── AUTHENTICATED VIEW ─────────────────────────────────────────────────
  return (
    <section className="max-w-6xl mx-auto px-4 sm:px-6 py-10">

      <div className="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
          <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Admin Panel</h1>
          <p className="text-sm text-gray-500 dark:text-gray-400">Manage knowledge base & monitor system status</p>
        </div>
        <button onClick={() => { localStorage.removeItem(TOKEN_STORAGE_KEY); setAuthed(false); setToken(''); }}
          className="text-sm text-gray-500 hover:text-red-600">
          🔓 Logout
        </button>
      </div>

      <div className="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-3 mb-6 text-xs text-amber-800 dark:text-amber-200">
        ⚠️ Only add documents from trusted sources (INTERPOL, UN, ICCT, BNPT, OJK, peer-reviewed journals, etc.) to maintain answer quality.
      </div>

      {/* Tabs */}
      <div className="flex gap-2 mb-6 border-b border-gray-200 dark:border-gray-700">
        {[
          ['documents', `📚 Documents (${docs.length})`],
          ['add',       '➕ Add Document'],
          ['status',    '📊 System Status'],
        ].map(([key, label]) => (
          <button key={key}
            onClick={() => setTab(key as Tab)}
            className={`px-4 py-2 text-sm font-semibold border-b-2 -mb-px transition-colors ${
              tab === key
                ? 'border-primary-600 text-primary-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'
            }`}
          >{label}</button>
        ))}
      </div>

      {success && <p className="bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 rounded-xl p-3 mb-4 text-sm">✅ {success}</p>}
      {error && <p className="bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 rounded-xl p-3 mb-4 text-sm">{error}</p>}

      {/* ── Documents tab ─────────────────────────────────────────────── */}
      {tab === 'documents' && (
        <div className="space-y-3">
          {docs.length === 0
            ? <p className="text-sm text-gray-500">No documents yet. Add one in the next tab, or run <code className="text-xs bg-gray-100 dark:bg-gray-800 px-2 py-0.5 rounded">php artisan db:seed --class=KnowledgeSeeder</code></p>
            : docs.map((d) => (
              <div key={d.id} className="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-4 flex flex-wrap items-start justify-between gap-3">
                <div className="flex-1 min-w-0">
                  <p className="font-semibold text-gray-900 dark:text-white">{d.title}</p>
                  <p className="text-xs text-gray-500 dark:text-gray-400">
                    {d.source ?? 'Unknown source'} · {d.organization ?? '—'} · {d.year ?? 'n/a'}
                  </p>
                  <div className="flex flex-wrap gap-1.5 mt-2">
                    <span className="text-xs bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300 px-2 py-0.5 rounded">{d.topic}</span>
                    <span className="text-xs bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 px-2 py-0.5 rounded">{d.region}</span>
                    <span className="text-xs bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 px-2 py-0.5 rounded">{d.language}</span>
                    {d.is_active
                      ? <span className="text-xs bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300 px-2 py-0.5 rounded">active</span>
                      : <span className="text-xs bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 px-2 py-0.5 rounded">disabled</span>}
                  </div>
                  {d.source_url && <a href={d.source_url} target="_blank" rel="noopener noreferrer" className="text-xs text-primary-600 hover:underline block mt-1 break-all">{d.source_url}</a>}
                </div>
                {d.is_active && (
                  <button onClick={() => handleDisable(d.id)} className="text-xs text-red-600 hover:underline">Disable</button>
                )}
              </div>
            ))}
        </div>
      )}

      {/* ── Add document tab ──────────────────────────────────────────── */}
      {tab === 'add' && (
        <div className="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-6 space-y-4 max-w-3xl">
          <h2 className="font-bold text-gray-900 dark:text-white">Add Trusted Document to Knowledge Base</h2>
          <p className="text-xs text-gray-500 dark:text-gray-400">Documents will be referenced when generating advice for incident reports. Only verified trusted sources.</p>

          <div className="grid sm:grid-cols-2 gap-3">
            <input className="border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-xl px-4 py-2.5 text-sm" placeholder="Title *" value={form.title} onChange={(e) => setForm({...form, title:e.target.value})} />
            <input className="border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-xl px-4 py-2.5 text-sm" placeholder="Source (e.g. INTERPOL)" value={form.source} onChange={(e) => setForm({...form, source:e.target.value})} />
            <input className="border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-xl px-4 py-2.5 text-sm" placeholder="Organization" value={form.organization} onChange={(e) => setForm({...form, organization:e.target.value})} />
            <input className="border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-xl px-4 py-2.5 text-sm" type="number" placeholder="Year" value={form.year} onChange={(e) => setForm({...form, year:e.target.value})} />
            <select className="border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-xl px-4 py-2.5 text-sm" value={form.topic} onChange={(e) => setForm({...form, topic:e.target.value})}>
              {['phishing','investment','romance','radicalization','money_laundering','other'].map(t => <option key={t}>{t}</option>)}
            </select>
            <input className="border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-xl px-4 py-2.5 text-sm" placeholder="Region (e.g. Indonesia)" value={form.region} onChange={(e) => setForm({...form, region:e.target.value})} />
            <select className="border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-xl px-4 py-2.5 text-sm" value={form.language} onChange={(e) => setForm({...form, language:e.target.value})}>
              {['en','id','fr','ar','es','de','zh','zh-TW','ru','ja','ko','jv','th','vi','tl','km'].map(l => <option key={l}>{l}</option>)}
            </select>
            <input className="border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-xl px-4 py-2.5 text-sm" placeholder="Source URL (optional)" value={form.source_url} onChange={(e) => setForm({...form, source_url:e.target.value})} />
          </div>
          <textarea className="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-xl px-4 py-2.5 text-sm" rows={3} placeholder="Description (short summary)" value={form.description} onChange={(e) => setForm({...form, description:e.target.value})}/>
          <textarea className="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 rounded-xl px-4 py-2.5 text-sm" rows={6} placeholder="Content (full markdown/text — optional)" value={form.content} onChange={(e) => setForm({...form, content:e.target.value})}/>

          <button onClick={handleAdd} disabled={loading}
            className="bg-primary-600 hover:bg-primary-700 disabled:opacity-60 text-white font-bold px-6 py-2.5 rounded-xl">
            {loading ? 'Adding…' : 'Add to Knowledge Base'}
          </button>
        </div>
      )}

      {/* ── Status tab ────────────────────────────────────────────────── */}
      {tab === 'status' && status && (
        <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <StatusCard label="System" value={status.system} />
          <StatusCard label="Version" value={status.version} />
          <StatusCard label="Total Documents" value={status.total_docs.toString()} />
          <StatusCard label="Active Documents" value={status.active_docs.toString()} />
          <StatusCard label="Mistral AI Ready" value={status.mistral_ready ? '✅ Yes (key set)' : '⚠️ No (key missing)'} />
          <StatusCard label="RAG Status" value={status.rag_status} />

          {Object.keys(status.by_topic).length > 0 && (
            <div className="sm:col-span-2 lg:col-span-3 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
              <p className="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Documents by Topic</p>
              <div className="flex flex-wrap gap-2">
                {Object.entries(status.by_topic).map(([topic, count]) => (
                  <span key={topic} className="bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300 px-3 py-1 rounded-full text-sm">
                    {topic}: <strong>{count}</strong>
                  </span>
                ))}
              </div>
            </div>
          )}
        </div>
      )}
    </section>
  );
}

function StatusCard({ label, value }: { label: string; value: string }) {
  return (
    <div className="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
      <p className="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">{label}</p>
      <p className="text-lg font-bold text-gray-900 dark:text-white mt-1">{value}</p>
    </div>
  );
}
