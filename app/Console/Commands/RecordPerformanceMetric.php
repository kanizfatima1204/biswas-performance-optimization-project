<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\PerformanceMetric;
class RecordPerformanceMetric extends Command {
 protected $signature='performance:record {label} {device : mobile or desktop} {score} {lcp_ms} {fcp_ms} {cls} {tbt_ms} {ttfb_ms} {transfer_kb} {requests} {--notes=}';
 protected $description='Record a measured Lighthouse performance result';
 public function handle(){ PerformanceMetric::create(['label'=>$this->argument('label'),'device'=>$this->argument('device'),'performance_score'=>$this->argument('score'),'lcp_ms'=>$this->argument('lcp_ms'),'fcp_ms'=>$this->argument('fcp_ms'),'cls'=>$this->argument('cls'),'tbt_ms'=>$this->argument('tbt_ms'),'ttfb_ms'=>$this->argument('ttfb_ms'),'transfer_kb'=>$this->argument('transfer_kb'),'requests'=>$this->argument('requests'),'notes'=>$this->option('notes')]); $this->info('Performance metric recorded.'); return self::SUCCESS; }
}
