import axios from 'axios';

const BASE_URL = (import.meta.env.VITE_API_BASE_URL ?? '/api') + '/v2';

const client = axios.create({
  baseURL: BASE_URL,
  headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
  timeout: 15_000,
});

// ── Types ─────────────────────────────────────────────────────────────────

export interface Hotline {
  slug: string;
  name: string;
  contact_channels: Record<string, string>;
  availability: '24_7' | 'business_hours' | 'custom';
  availability_note?: string;
}

export interface RecoveryPathwayListItem {
  slug: string;
  crime_domain: string;
  title: string;
  summary: string;
}

export interface RecoveryPathwayDetail extends RecoveryPathwayListItem {
  milestones: Array<{
    week: number;
    tasks: Array<{ key: string; title_key: string }>;
    emotional_notes_key?: string;
  }>;
  templates: Array<{ kind: string; path: string }>;
  hotlines: Hotline[];
  disclaimer: string;
}

export interface QuickResponse {
  mode: 'quick';
  profile_role: string;
  crime_domain: string;
  top_actions: Array<{ key: string; title: string; description: string }>;
  emergency_hotlines: Hotline[];
  upgrade_invitation: { title: string; message: string; cta: string };
}

export interface DeepStartResponse {
  token: string;
  expires_at: string;
  first_question: AssessmentQuestion;
  safety_message: string;
}

export interface AssessmentQuestion {
  id: string;
  type: 'yes_no' | 'single_select' | 'number' | 'text' | 'select';
  question: string;
  options?: Array<{ value: string; label: string }>;
  allow_skip?: boolean;
}

export interface DeepAnswerResponse {
  completion_pct: number;
  next_question: AssessmentQuestion | null;
  risk_signals: Array<{ type: string; severity: string }>;
  is_complete: boolean;
}

export interface PersonalizedPlan {
  profile_role: string;
  crime_domain: string;
  risk_signals: Array<{ type: string; severity: string }>;
  guidance_focus: string;
  recovery_pathway: { slug: string; title: string; url: string } | null;
  next_steps: Array<{ key: string; title: string; description: string }>;
  emergency_hotlines: Hotline[];
}

export interface MigrantModule {
  sequence: number;
  module_code: string;
  title: string;
  has_video: boolean;
  source: string;
}

export interface MigrantModuleDetail extends MigrantModule {
  content: string;
  video_urls: string[] | null;
  pre_post_questions: any[];
}

// ── Recovery ─────────────────────────────────────────────────────────────

export const listRecoveryPathways = (domain?: string, lang = 'id') =>
  client.get<{ data: RecoveryPathwayListItem[]; meta: any }>('/recovery-pathways', {
    params: { domain, lang },
  }).then(r => r.data);

export const getRecoveryPathway = (slug: string, lang = 'id') =>
  client.get<RecoveryPathwayDetail>(`/recovery-pathways/${slug}`, { params: { lang } })
    .then(r => r.data);

export const getLegalAid = (province?: string) =>
  client.get<{ data: any[] }>('/legal-aid', { params: { province } })
    .then(r => r.data);

// ── Adaptive ─────────────────────────────────────────────────────────────

export const adaptiveQuick = (role: string, domain: string, country: string, locale = 'id') =>
  client.post<QuickResponse>('/adaptive/quick', { role, domain, country, locale })
    .then(r => r.data);

export const adaptiveDeepStart = (
  role: string, country: string, locale: string,
  consent: { pfa_disclaimer: boolean; data_use: boolean }
) => client.post<DeepStartResponse>('/adaptive/deep/start', { role, country, locale, consent })
    .then(r => r.data);

export const adaptiveDeepAnswer = (token: string, domain: string, question_id: string, answer: any) =>
  client.post<DeepAnswerResponse>('/adaptive/deep/answer', { token, domain, question_id, answer })
    .then(r => r.data);

export const adaptiveDeepResolve = (token: string, domain: string) =>
  client.post<PersonalizedPlan>('/adaptive/deep/resolve', { token, domain })
    .then(r => r.data);

// ── Migrant Education ────────────────────────────────────────────────────

export const getMigrantCurriculum = (to: string, sector?: string, lang = 'id') =>
  client.get<{ destination_country: string; sector: string | null; locale: string; modules: MigrantModule[] }>(
    '/migrant/curriculum',
    { params: { to, sector, lang } }
  ).then(r => r.data);

export const getMigrantModule = (id: number, lang = 'id') =>
  client.get<MigrantModuleDetail>(`/migrant/modules/${id}`, { params: { lang } })
    .then(r => r.data);

export const submitMigrantPreAssessment = (curriculum_code: string, answers: any[]) =>
  client.post('/migrant/assessments/pre', { curriculum_code, answers }).then(r => r.data);

export const submitMigrantPostAssessment = (curriculum_code: string, pre_score: number, answers: any[]) =>
  client.post('/migrant/assessments/post', { curriculum_code, pre_score, answers }).then(r => r.data);

// ── Workshop ─────────────────────────────────────────────────────────────

export const joinWorkshop = (code: string) =>
  client.post<{ participant_code: string; session: any }>(`/workshop/sessions/${code}/join`)
    .then(r => r.data);

export const submitWorkshopAssessment = (
  participant_code: string, kind: 'pre' | 'post',
  answers: any[], score: number, self_efficacy?: number
) => client.post('/workshop/assessments', { participant_code, kind, answers, score, self_efficacy })
    .then(r => r.data);

export const issueCertificate = (participantCode: string) =>
  client.post(`/workshop/certificates/${participantCode}`).then(r => r.data);

export const verifyCertificate = (hash: string) =>
  client.get(`/workshop/certificates/verify/${hash}`).then(r => r.data);
