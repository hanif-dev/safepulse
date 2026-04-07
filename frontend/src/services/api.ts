import axios from 'axios';

// VITE_API_BASE_URL is set in .env
// In Codespaces dev: "/api" (proxied by Vite to localhost:8000)
// In production:     "https://api.yourdomain.com/api"
const BASE_URL = import.meta.env.VITE_API_BASE_URL ?? '/api';

const client = axios.create({
  baseURL: BASE_URL,
  headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
  timeout: 15_000,
});

// ── Types ─────────────────────────────────────────────────────────────────────

export interface ScamCheckPayload {
  message_text?: string;
  url?: string;
  phone_number?: string;
  bank_account?: string;
}

export interface ScamCheckResult {
  score: number;
  level: 'Low' | 'Medium' | 'High';
  reasons: string[];
}

export interface IncidentPayload {
  category: string;
  country: string;
  age_group?: string;
  description: string;
  health_impact_level: string;
  financial_loss_estimate?: number;
}

export interface Article {
  id: number;
  slug: string;
  title: string;
  language: string;
  category: string;
  summary: string;
  body_markdown?: string;
  published_at: string;
}

export interface ArticleListResponse {
  data: Article[];
  meta: { current_page: number; last_page: number; total: number };
}

export interface StatsOverview {
  summary: {
    total_incidents: number;
    high_impact: number;
    total_financial_loss: number;
    countries_affected: number;
    people_protected: number;
  };
  by_category: Record<string, number>;
  by_country: Record<string, number>;
  monthly: Record<string, number>;
}

// ── API calls ─────────────────────────────────────────────────────────────────

export const checkScam = (payload: ScamCheckPayload): Promise<ScamCheckResult> =>
  client.post('/check-scam', payload).then((r) => r.data);

export const reportIncident = (payload: IncidentPayload): Promise<{ message: string }> =>
  client.post('/incidents', payload).then((r) => r.data);

export const fetchArticles = (params: {
  category?: string;
  language?: string;
  search?: string;
  page?: number;
}): Promise<ArticleListResponse> =>
  client.get('/articles', { params }).then((r) => r.data);

export const fetchArticle = (slug: string): Promise<{ data: Article }> =>
  client.get(`/articles/${slug}`).then((r) => r.data);

export const fetchStats = (): Promise<StatsOverview> =>
  client.get('/stats/overview').then((r) => r.data);
