import axios from 'axios';

const BASE_URL = import.meta.env.VITE_API_BASE_URL ?? '/api';

const client = axios.create({
  baseURL: BASE_URL,
  headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
  timeout: 20_000,
});

// ── Types ─────────────────────────────────────────────────────────────────

export interface ScamCheckPayload {
  message_text?: string; url?: string; phone_number?: string; bank_account?: string;
}
export interface ScamCheckResult {
  score: number; level: 'Low'|'Medium'|'High'; reasons: string[];
  analysis_by?: string[]; powered_by?: string; privacy_note?: string;
}

export interface IncidentPayload {
  category: string; country: string; age_group?: string;
  description: string; health_impact_level: string; financial_loss_estimate?: number;
}

export interface IncidentAdvice {
  category_label: string;
  severity: 'low'|'medium'|'high';
  advice: { headline: string; steps: string[] };
  resources: { name: string; contact: string; type: string }[];
  action_checklist: string[];
  knowledge_refs: { title: string; source: string; org: string; year: number; url: string|null; region: string }[];
  ai_analysis: string|null;
  powered_by: string;
}

export interface IncidentResult {
  success: boolean; incident_id: number; message: string;
  analysis?: IncidentAdvice; privacy_note?: string;
}

export interface Article {
  id: number; slug: string; title: string; language: string;
  category: string; summary: string; body_markdown?: string; published_at: string;
}
export interface ArticleListResponse {
  data: Article[]; meta: { current_page: number; last_page: number; total: number };
}

export interface StatsOverview {
  summary: { total_incidents: number; high_impact: number; total_financial_loss: number;
    countries_affected: number; people_protected: number };
  by_category: Record<string, number>; by_country: Record<string, number>;
  monthly: Record<string, number>;
}

// ── Knowledge / Admin ────────────────────────────────────────────────────

export interface KnowledgeDoc {
  id: number; title: string; source: string|null; organization: string|null;
  topic: string; region: string; language: string; year: number|null;
  source_url: string|null; description: string|null; is_active: boolean;
  created_at: string; updated_at: string;
}
export interface KnowledgeStatus {
  system: string; version: string; total_docs: number; active_docs: number;
  by_topic: Record<string, number>; mistral_ready: boolean; rag_status: string;
}

// ── Public API ────────────────────────────────────────────────────────────

export const checkScam = (p: ScamCheckPayload): Promise<ScamCheckResult> =>
  client.post('/check-scam', p).then((r) => r.data);

export const reportIncident = (p: IncidentPayload): Promise<IncidentResult> =>
  client.post('/incidents', p).then((r) => r.data);

export const fetchArticles = (params: { category?: string; language?: string; search?: string; page?: number }):
  Promise<ArticleListResponse> => client.get('/articles', { params }).then((r) => r.data);

export const fetchArticle = (slug: string): Promise<{ data: Article }> =>
  client.get(`/articles/${slug}`).then((r) => r.data);

export const fetchStats = (): Promise<StatsOverview> =>
  client.get('/stats/overview').then((r) => r.data);

// ── Admin API ─────────────────────────────────────────────────────────────

const adminClient = (token: string) => axios.create({
  baseURL: BASE_URL,
  headers: { 'Content-Type':'application/json', Accept:'application/json', 'X-Admin-Token': token },
  timeout: 15_000,
});

export const adminGetStatus = (token: string): Promise<KnowledgeStatus> =>
  adminClient(token).get('/admin/knowledge/status').then((r) => r.data);

export const adminListDocs = (token: string): Promise<{ data: KnowledgeDoc[]; meta: any }> =>
  adminClient(token).get('/admin/knowledge').then((r) => r.data);

export const adminCreateDoc = (token: string, data: any): Promise<{ data: KnowledgeDoc }> =>
  adminClient(token).post('/admin/knowledge', data).then((r) => r.data);

export const adminDisableDoc = (token: string, id: number): Promise<{ message: string }> =>
  adminClient(token).delete(`/admin/knowledge/${id}`).then((r) => r.data);
