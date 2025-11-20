<?php

namespace App\Jobs;

use App\Models\Beer;
use App\Models\BeerEmbedding;
use App\Services\EmbeddingService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Exceptions\PrismRateLimitedException;
use Prism\Prism\Facades\Prism;

class ProcessBeerJob implements ShouldQueue
{
    use Queueable;

    public $tries = 10;

    public function backoff(): array
    {
        // segundos entre tentativas: 10s, 30s, 60s...
        return [10, 30, 60, 120];
    }

    /**
     * Create a new job instance.
     */
    public function __construct(public Beer $beer)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(EmbeddingService $embeddingService): void
    {
        try {
            $response = Prism::text()
                ->using(Provider::Groq, 'llama-3.1-8b-instant')
                ->withSystemPrompt(view('prompts.brewer_tips_agent'))
                ->withPrompt($this->beer->toJson())
                ->withClientOptions(['timeout' => 9999])
                ->asText();

            $this->beer->update(['brewer_tips' => $response->text]);

            $embedding = $embeddingService->generateEmbedding($response->text);

            BeerEmbedding::create([
                'beer_id' => $this->beer->id,
                'text' => $response->text,
                'metadata' => $this->beer->toArray(),
                'embedding' => $embedding->embeddings[0]->embedding,
            ]);

        } catch (PrismRateLimitedException $e) {
            Log::warning('Rate limit no ProcessBeerJob: ' . $e->getMessage());

            // se o provider informa retryAfter, usa; senão chuta 10s
            $retryAfter = method_exists($e, 'retryAfter') ? $e->retryAfter : 10;

            $this->release($retryAfter);  // volta pra fila com delay
            return;
        } catch (\Throwable $e) {
            Log::error('Erro no ProcessBeerJob: ' . $e->getMessage());
            // aqui você escolhe se deixa falhar ou não
            $this->fail($e);
        }
    }
}
