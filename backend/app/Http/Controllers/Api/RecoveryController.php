<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Hotline;
use App\Models\LegalAidContact;
use App\Models\RecoveryPathway;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Recovery Pathway endpoints.
 *
 * Returns sequenced recovery guidance (Week 1, 2, 4, 8) anchored in:
 *  - SAMHSA's six trauma-informed principles
 *  - WHO Psychological First Aid (Look-Listen-Link)
 *  - GASO survivor-support framework
 *
 * Privacy: NO user identification required. All endpoints public-anonymous.
 */
class RecoveryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $domain = $request->query('domain');
        $locale = $request->query('lang', 'id');

        $pathways = RecoveryPathway::published()
            ->when($domain, fn ($q) => $q->where('crime_domain', $domain))
            ->get(['slug', 'crime_domain', 'title', 'summary']);

        return response()->json([
            'data' => $pathways->map(fn ($p) => [
                'slug'         => $p->slug,
                'crime_domain' => $p->crime_domain,
                'title'        => $p->localized('title', $locale),
                'summary'      => $p->localized('summary', $locale),
            ]),
            'meta' => ['locale' => $locale, 'total' => $pathways->count()],
        ]);
    }

    public function show(string $slug, Request $request): JsonResponse
    {
        $locale = $request->query('lang', 'id');
        $pathway = RecoveryPathway::published()->where('slug', $slug)->firstOrFail();

        // Hydrate linked hotlines
        $hotlineSlugs = collect($pathway->hotlines ?? [])->pluck('slug');
        $hotlines = Hotline::verified()
            ->whereIn('slug', $hotlineSlugs)
            ->get()
            ->map(fn ($h) => [
                'slug'             => $h->slug,
                'name'             => $h->name,
                'contact_channels' => $h->contact_channels,
                'availability'     => $h->availability,
                'availability_note'=> $h->availability_note,
            ]);

        AuditLog::record('recovery.show', 'anonymous', [
            'slug'         => $slug,
            'crime_domain' => $pathway->crime_domain,
            'locale'       => $locale,
        ]);

        return response()->json([
            'slug'         => $pathway->slug,
            'crime_domain' => $pathway->crime_domain,
            'title'        => $pathway->localized('title', $locale),
            'summary'      => $pathway->localized('summary', $locale),
            'milestones'   => $pathway->milestones,
            'templates'    => $pathway->templates,
            'hotlines'     => $hotlines,
            'disclaimer'   => $this->traumaInformedDisclaimer($locale),
        ]);
    }

    public function legalAid(Request $request): JsonResponse
    {
        $province = $request->query('province');
        $contacts = LegalAidContact::when($province, fn ($q) => $q->where('province', $province))
            ->where('pro_bono', true)
            ->get();

        return response()->json(['data' => $contacts]);
    }

    public function template(string $slug, string $kind): JsonResponse
    {
        $pathway = RecoveryPathway::published()->where('slug', $slug)->firstOrFail();
        $template = collect($pathway->templates ?? [])
            ->firstWhere('kind', $kind);

        if (! $template) {
            return response()->json(['error' => 'Template not found'], 404);
        }

        AuditLog::record('recovery.template', 'anonymous', [
            'slug' => $slug,
            'kind' => $kind,
        ]);

        return response()->json([
            'kind'         => $template['kind'],
            'download_url' => url('/storage/' . $template['path']),
        ]);
    }

    private function traumaInformedDisclaimer(string $locale): string
    {
        $messages = [
            'en' => 'This guide offers practical steps, not professional therapy. Recovery happens at your own pace. You did nothing wrong.',
            'id' => 'Panduan ini memberikan langkah praktis, bukan terapi profesional. Pemulihan berjalan sesuai kecepatan Anda sendiri. Anda tidak melakukan kesalahan.',
            'fr' => 'Ce guide propose des étapes pratiques, pas une thérapie professionnelle. La guérison se fait à votre rythme. Vous n\'avez rien fait de mal.',
        ];
        return $messages[$locale] ?? $messages['en'];
    }
}
