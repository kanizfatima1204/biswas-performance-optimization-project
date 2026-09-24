<script setup>
import { Head } from '@inertiajs/vue3';
import { ArrowLeft, CheckCircle2, Gauge, Monitor, Smartphone, TrendingDown, Zap } from 'lucide-vue-next';

const props = defineProps({ metrics: { type: Array, default: () => [] } });
const groups = ['mobile', 'desktop'];

const by = (device, label) => props.metrics.find((m) => m.device === device && m.label === label) || {};
const percent = (before, after) => before ? Math.round(((after - before) / before) * 100) : 0;
const reduction = (before, after) => before ? Math.abs(Math.round(((after - before) / before) * 100)) : 0;
const score = (device) => by(device, 'After optimization').performance_score || 0;
const scoreBefore = (device) => by(device, 'Before optimization').performance_score || 0;
const isMeasured = (device) => ['Before optimization', 'After optimization'].every((label) =>
  by(device, label).notes?.includes('Measured with Lighthouse')
);

const rows = (device) => [
  { key: 'Performance', before: scoreBefore(device), after: score(device), unit: '/100', higher: true },
  { key: 'LCP', before: by(device, 'Before optimization').lcp_ms, after: by(device, 'After optimization').lcp_ms, unit: 'ms' },
  { key: 'FCP', before: by(device, 'Before optimization').fcp_ms, after: by(device, 'After optimization').fcp_ms, unit: 'ms' },
  { key: 'CLS', before: by(device, 'Before optimization').cls, after: by(device, 'After optimization').cls, unit: '' },
  { key: 'TBT', before: by(device, 'Before optimization').tbt_ms, after: by(device, 'After optimization').tbt_ms, unit: 'ms' },
  { key: 'TTFB', before: by(device, 'Before optimization').ttfb_ms, after: by(device, 'After optimization').ttfb_ms, unit: 'ms' },
  { key: 'Transfer', before: by(device, 'Before optimization').transfer_kb, after: by(device, 'After optimization').transfer_kb, unit: 'KB' },
  { key: 'Requests', before: by(device, 'Before optimization').requests, after: by(device, 'After optimization').requests, unit: '' },
];
</script>

<template>
  <Head title="Performance Audit" />
  <div class="report-shell">
    <header class="report-topbar">
      <div class="container-xl report-nav">
        <!-- Plain anchor intentionally used as a reliable fallback: it works even if the JS bundle fails. -->
        <a href="/" class="back-link"><ArrowLeft :size="18" /> <span>Back to home</span></a>
        <div class="report-brand"><span class="brand-mark"><Gauge :size="16" /></span> Performance Audit</div>
      </div>
    </header>

    <main class="container-xl report-main">
      <section class="report-hero">
        <div>
          <span class="section-kicker">03 · Technical audit</span>
          <h1>Before vs. after, without the guesswork.</h1>
          <p>Use the same device profile and Lighthouse conditions for both runs. Rows currently contain illustrative seed data; they are not measurements and must be replaced with recorded runs before submission.</p>
          <div class="report-actions"><a href="/" class="secondary-btn"><ArrowLeft :size="16" /> Home</a><a href="#comparison" class="primary-btn"><Zap :size="16" /> Compare results</a></div>
        </div>
        <div class="audit-score-card">
          <div class="audit-score-ring" :style="{ '--score': `${score('mobile') * 3.6}deg` }"><span>{{ score('mobile') }}</span></div>
          <div><span>{{ isMeasured('mobile') ? 'Measured mobile score' : 'Sample mobile score' }}</span><b>Performance</b><small>{{ isMeasured('mobile') ? 'Lighthouse result' : 'Replace with Lighthouse result' }}</small></div>
        </div>
      </section>

      <section id="comparison" class="comparison-grid">
        <article v-for="device in groups" :key="device" class="audit-card">
          <div class="audit-card-head">
            <div class="device-title"><span class="device-icon"><Smartphone v-if="device === 'mobile'" :size="18" /><Monitor v-else :size="18" /></span><div><b>{{ device }}</b><small>Performance profile</small></div></div>
            <span class="status-pill"><i></i> {{ isMeasured(device) ? 'measured' : 'sample data' }}</span>
          </div>

          <div class="mini-score-row">
            <div><span>Before</span><b>{{ scoreBefore(device) }}</b></div>
            <div class="score-arrow">→</div>
            <div><span>After</span><b class="after-score">{{ score(device) }}</b></div>
            <div class="score-gain">+{{ Math.abs(percent(scoreBefore(device), score(device))) }}%</div>
          </div>

          <div class="table-wrap">
            <table>
              <thead><tr><th>Metric</th><th>Before</th><th>After</th><th>Delta</th></tr></thead>
              <tbody>
                <tr v-for="row in rows(device)" :key="row.key">
                  <td><b>{{ row.key }}</b></td>
                  <td>{{ row.before }}{{ row.unit }}</td>
                  <td class="after-value">{{ row.after }}{{ row.unit }}</td>
                  <td><span class="delta"><TrendingDown :size="13" /> {{ row.higher ? '+' + Math.abs(percent(row.before, row.after)) : '−' + reduction(row.before, row.after) }}%</span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </article>
      </section>

      <section class="checklist-card">
        <div><span class="section-kicker">04 · Optimization checklist</span><h2>What changed in the codebase</h2></div>
        <div class="checklist-grid">
          <div v-for="item in ['Vite production build with Vue and icon dependency chunks','Five-minute Laravel cache for home feature data','Mobile navigation and narrow-screen responsive CSS','No external image requests on the current home page','Laravel middleware sets security headers and short home HTML cache']" :key="item"><CheckCircle2 :size="17" /><span>{{ item }}</span></div>
        </div>
      </section>

      <p class="report-note">Sample metrics are seeded for the prototype and do not represent a measured before/after result. Record Lighthouse runs using <code>php artisan performance:record</code> and keep the run conditions and report files with your submission.</p>
    </main>
  </div>
</template>
