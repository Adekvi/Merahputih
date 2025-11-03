<?php

namespace Database\Seeders\Wilayah;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class KelurahanLocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Behavior:
     * - Hard-coded provider = nominatim (no .env needed)
     * - Respects Nominatim rate limits: 1 request per second
     * - Retries up to $maxAttempts with exponential backoff
     * - Safe to run multiple times (skips items already with lat/lng)
     */
    public function run(): void
    {
        // -------------------------
        // CONFIG (edit here if needed)
        // -------------------------
        $provider       = 'nominatim';                        // 'nominatim' or 'google' (if you adapt)
        $userAgent      = 'PatiSeeder/1.0 (admin@yourdomain.tld)'; // wajib untuk Nominatim
        $perRequestUs   = 1000000;                            // microseconds: 1_000_000 = 1 second
        $maxAttempts    = 3;                                  // retry attempts per record
        $initialBackoff = 800000;                             // microseconds (0.8s)
        $timeoutSeconds = 30;                                 // HTTP timeout
        // -------------------------

        // Count total for progress bar
        $total = DB::table('kelurahans')->count();

        $this->command?->info("Starting geocoding for {$total} kelurahan (provider: {$provider})");

        // create progress bar if running in artisan
        $bar = null;
        if ($this->command) {
            try {
                $bar = $this->command->getOutput()->createProgressBar($total);
                $bar->start();
            } catch (\Throwable $e) {
                $bar = null;
            }
        }

        // Process in chunks (to avoid memory spike)
        DB::table('kelurahans')->orderBy('id')->chunk(200, function ($rows) use (
            $provider, $userAgent, $perRequestUs, $maxAttempts, $initialBackoff, $timeoutSeconds, $bar
        ) {
            foreach ($rows as $r) {
                $namaKel = trim($r->nama_kelurahan);
                $idKec   = $r->id_kecamatan;
                if ($namaKel === '') {
                    $this->logWarn("Skip empty nama_kelurahan (id: {$r->id})");
                    if ($bar) $bar->advance();
                    continue;
                }

                // ambil nama kecamatan (baca dari tabel kecamatans)
                $namaKec = DB::table('kecamatans')->where('id', $idKec)->value('nama_kecamatan') ?? (string)$idKec;

                // Skip jika sudah ada dan lat/lng terisi
                $existing = DB::table('wilayah_locations')
                    ->where('id_kecamatan', $idKec)
                    ->where('nama_kelurahan', $namaKel)
                    ->first();

                if ($existing && $existing->latitude !== null && $existing->longitude !== null) {
                    // already have coordinates
                    $this->command?->info("Skip (exists): {$namaKel} — already has coords.");
                    if ($bar) $bar->advance();
                    // respectful small sleep to keep rate predictable even while skipping
                    usleep(200000);
                    continue;
                }

                // Build geocode query using readable names (not numeric ID)
                $query = "{$namaKel}, Kecamatan {$namaKec}, Kabupaten Pati, Jawa Tengah, Indonesia";

                $this->command?->info("Geocoding: {$query}");

                $attempt = 0;
                $success = false;
                $lat = $lng = null;
                $rawResponse = null;

                while ($attempt < $maxAttempts && ! $success) {
                    $attempt++;
                    try {
                        // Nominatim endpoint
                        $url = 'https://nominatim.openstreetmap.org/search';

                        // Make HTTP request
                        $response = Http::withHeaders([
                            'User-Agent' => $userAgent,
                            'Accept-Language' => 'id',
                        ])->timeout($timeoutSeconds)
                          ->get($url, [
                              'q' => $query,
                              'format' => 'json',
                              'limit' => 1,
                          ]);

                        $rawResponse = $response->body();

                        if ($response->ok()) {
                            $json = $response->json();
                            if (is_array($json) && count($json) > 0) {
                                $item = $json[0];
                                $lat = $item['lat'] ?? null;
                                $lng = $item['lon'] ?? null;
                                if ($lat !== null && $lng !== null) {
                                    $success = true;
                                    break;
                                }
                            }
                        } else {
                            // Non-OK - log status and will retry
                            $this->logWarn("HTTP not OK (attempt {$attempt}) for {$query} — status: " . $response->status());
                        }
                    } catch (\Throwable $e) {
                        // connection timeout or other HTTP exceptions — will retry
                        $this->logWarn("Exception (attempt {$attempt}) for {$query}: " . $e->getMessage());
                        $rawResponse = json_encode(['error' => $e->getMessage()]);
                    }

                    // backoff before next attempt (exponential)
                    $sleepUs = (int)($initialBackoff * $attempt);
                    usleep($sleepUs);
                } // end attempts

                // prepare payload for DB
                $payload = [
                    'kelurahan_id'   => $r->id,
                    'id_kecamatan'   => $idKec,
                    'nama_kelurahan' => $namaKel,
                    'provider'       => $provider,
                    'raw_response'   => $rawResponse ?? json_encode([]),
                    'updated_at'     => now(),
                ];

                if ($success) {
                    $payload['latitude']  = floatval($lat);
                    $payload['longitude'] = floatval($lng);
                } else {
                    // keep latitude/longitude null on failure
                    $payload['latitude']  = null;
                    $payload['longitude'] = null;
                }

                // insert or update
                if ($existing) {
                    DB::table('wilayah_locations')->where('id', $existing->id)->update($payload);
                } else {
                    $payload['created_at'] = now();
                    DB::table('wilayah_locations')->insert($payload);
                }

                if ($success) {
                    $this->command?->info("  ✅ {$namaKel} → {$lat}, {$lng}");
                } else {
                    $this->command?->warn("  ⚠️  Gagal geocode {$namaKel} — disimpan untuk retry nanti");
                }

                // Respect rate-limit: sleep perRequestUs microseconds between requests
                usleep($perRequestUs);

                if ($bar) $bar->advance();
            } // end foreach row
        }); // end chunk

        if ($bar) {
            $bar->finish();
            $this->command?->newLine();
        }

        $this->command?->info("Geocoding process completed. Cek tabel `wilayah_locations` untuk hasil.");
    }

    /**
     * Write a warning to Laravel log and to console (if available).
     */
    protected function logWarn(string $message): void
    {
        if ($this->command) {
            $this->command->warn($message);
        }
        Log::warning('[KelurahanGeocodeSeeder] ' . $message);
    }
}
