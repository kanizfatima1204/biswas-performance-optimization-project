<script setup>
import { Head } from '@inertiajs/vue3';
import { ArrowUpRight, Gauge, Image as ImageIcon, Layers3, Menu, ShieldCheck, Sparkles, Zap } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps({ features: { type: Array, default: () => [] } });
const menuOpen = ref(false);

const icons = [Zap, ImageIcon, ShieldCheck];
const highlights = [
  { value: 'Mobile-first', label: 'layout', detail: 'responsive breakpoints' },
  { value: 'Vite', label: 'asset pipeline', detail: 'fingerprinted build output' },
  { value: 'Lighthouse', label: 'measurement', detail: 'record actual runs' },
];

const closeMenu = () => { menuOpen.value = false; };
</script>

<template>
  <Head title="Performance Lab" />

  <div class="site-shell">
    <header class="topbar">
      <div class="container-xl topbar-inner">
        <a href="/" class="brand" aria-label="Biswas Performance Lab home">
          <span class="brand-mark"><Gauge :size="18" stroke-width="2.5" /></span>
          <span>Biswas <b>Performance Lab</b></span>
        </a>

        <nav class="desktop-nav" aria-label="Main navigation">
          <a href="#optimizations">Optimizations</a>
          <a href="#method">Method</a>
          <a href="/performance-report" class="nav-cta">Open audit <ArrowUpRight :size="15" /></a>
        </nav>

        <button class="menu-button" type="button" aria-label="Open navigation" :aria-expanded="menuOpen" @click="menuOpen = !menuOpen">
          <Menu :size="21" />
        </button>
      </div>
      <div v-if="menuOpen" class="mobile-menu">
        <a href="#optimizations" @click="closeMenu">Optimizations</a>
        <a href="#method" @click="closeMenu">Method</a>
        <a href="/performance-report">Open audit <ArrowUpRight :size="15" /></a>
      </div>
    </header>

    <main>
      <section class="hero-section">
        <div class="hero-orb hero-orb-one"></div>
        <div class="hero-orb hero-orb-two"></div>
        <div class="container-xl hero-layout">
          <div class="hero-copy">
            <div class="eyebrow"><span class="pulse-dot"></span> Advanced performance engineering</div>
            <h1>Make every <span>millisecond</span> count.</h1>
            <p class="hero-lead">A business website demo built to explore asset delivery, JavaScript loading, caching and mobile performance. Lighthouse results are reported only after they are measured.</p>

            <div class="hero-actions">
              <a href="/performance-report" class="primary-btn">View before / after <ArrowUpRight :size="17" /></a>
              <a href="#optimizations" class="secondary-btn">Explore the engineering</a>
            </div>

            <div class="trust-row">
              <div><Sparkles :size="15" /> Laravel 12</div>
              <div><Sparkles :size="15" /> Vue 3 + Inertia</div>
              <div><Sparkles :size="15" /> MySQL</div>
            </div>
          </div>

          <div class="hero-visual">
            <div class="visual-glow"></div>
            <div class="dashboard-window">
              <div class="window-bar">
                <div class="window-dots"><i></i><i></i><i></i></div>
                <span>performance.lab / overview</span>
                <span class="live-pill"><b></b> DEMO</span>
              </div>
              <div class="dashboard-body">
                <div class="score-panel">
                  <div>
                    <span class="mini-label">Performance evidence</span>
                    <strong>Run</strong><small>Lighthouse</small>
                  </div>
                  <div class="score-ring"><span>—</span></div>
                </div>
                <div class="chart-card">
                  <div class="chart-heading"><span>Benchmark status</span><b>Pending</b></div>
                  <div class="chart-axis"><span>Record Lighthouse runs to populate results</span></div>
                </div>
                <div class="metric-grid">
                  <div><span>LCP</span><b>Pending</b><em>Measure</em></div>
                  <div><span>CLS</span><b>Pending</b><em>Measure</em></div>
                  <div><span>TTFB</span><b>Pending</b><em>Measure</em></div>
                </div>
              </div>
            </div>
            <div class="floating-card floating-card-top"><Zap :size="16" /> <span>Optimization demo</span></div>
            <div class="floating-card floating-card-bottom"><span class="tiny-check">✓</span> Evidence before claims</div>
          </div>
        </div>
      </section>

      <section class="stats-strip">
        <div class="container-xl stats-grid">
          <div v-for="item in highlights" :key="item.label" class="stat-item">
            <strong>{{ item.value }}</strong>
            <div><span>{{ item.label }}</span><small>{{ item.detail }}</small></div>
          </div>
        </div>
      </section>

      <section id="optimizations" class="section-light">
        <div class="container-xl">
          <div class="section-heading">
            <div>
              <span class="section-kicker">01 · Engineering layer</span>
              <h2>Performance built into the interface.</h2>
            </div>
            <p>Every visual decision has a technical reason behind it: fewer bytes, fewer blocking tasks and a smoother mobile experience.</p>
          </div>

          <div class="feature-grid">
            <article v-for="(feature, i) in props.features" :key="feature.title || i" class="feature-card">
              <div class="feature-icon"><component :is="icons[i] || Layers3" :size="20" /></div>
              <span class="feature-number">0{{ i + 1 }}</span>
              <h3>{{ feature.title }}</h3>
              <p>{{ feature.text }}</p>
              <div class="feature-line"></div>
            </article>
          </div>
        </div>
      </section>

      <section id="method" class="method-section">
        <div class="container-xl method-layout">
          <div>
            <span class="section-kicker light">02 · Audit method</span>
            <h2>Measure. Optimize. Prove it.</h2>
            <p>This project keeps baseline and optimized runs separate so the final submission can show a measurable difference without treating sample data as evidence.</p>
            <a href="/performance-report" class="method-button">Open the technical audit <ArrowUpRight :size="17" /></a>
          </div>
          <div class="method-list">
            <div><span>01</span><b>Baseline</b><small>Capture mobile + desktop Lighthouse metrics.</small></div>
            <div><span>02</span><b>Engineering</b><small>Optimize assets, JavaScript, images and caching.</small></div>
            <div><span>03</span><b>Validation</b><small>Repeat the same test conditions and compare.</small></div>
          </div>
        </div>
      </section>
    </main>

    <footer class="footer">
      <div class="container-xl footer-inner">
        <span>Biswas IT Firm · Advanced Website Performance Optimization</span>
        <span>Laravel 12 · Vue 3 · Inertia.js · MySQL</span>
      </div>
    </footer>
  </div>
</template>
