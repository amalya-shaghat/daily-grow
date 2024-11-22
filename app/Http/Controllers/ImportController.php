<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Inertia\Inertia;
use Inertia\Response;

class ImportController extends Controller
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('Import');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:txt',
        ]);

        $file = $request->file('file');

        $content = mb_convert_encoding(file_get_contents($file->getRealPath()), 'UTF-8', 'Windows-1251');
        $sections = explode("\nСекцияДокумент=", $content);

        foreach ($sections as $section) {
            if (str_contains($section, 'Платежное поручение')) {
                $data = $this->parseSection($section);
                if ($data) {
                    $transaction = Transaction::query()->create($data);
                    $transaction->users()->attach(auth()->id());
                }
            }
        }

        return redirect()->route('dashboard')->with('success', 'Файл успешно обработан!');
    }

    /**
     * @return array{operation_date: null|string, counterparty: null|string, amount: null|string, description: null|string, type: string, payment_date: null|string, status: string}
     */
    private function parseSection($section): array
    {
        return [
            'operation_date' => $this->formatDate($this->extractValue($section, 'Дата')),
            'counterparty' => $this->extractValue($section, 'Плательщик1'),
            'amount' => $this->extractValue($section, 'Сумма'),
            'description' => $this->extractValue($section, 'НазначениеПлатежа'),
            'type' => str_contains($section, 'Поступило') ? 'income' : 'expense',
            'payment_date' => $this->formatDate($this->extractValue($section, 'ДатаПоступило')),
            'status' => 'fact',
        ];
    }

    /**
     * @param string|null $date
     * @return string|null
     */
    private function formatDate(?string $date): ?string
    {
        if (!$date) {
            return null;
        }

        try {
            return Carbon::createFromFormat('d.m.Y', $date)->format('Y-m-d');
        } catch (Exception $e) {
            return null;
        }
    }
    /**
     * @param $section
     * @param $key
     * @return string|null
     */
    private function extractValue($section, $key): ?string
    {
        preg_match("/{$key}=(.*?)(\n|$)/u", $section, $matches);

        return isset($matches[1]) ? trim($matches[1]) : null;
    }
}
