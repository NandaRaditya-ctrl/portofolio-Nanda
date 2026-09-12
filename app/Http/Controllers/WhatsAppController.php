<?php

namespace App\Http\Controllers;

use App\Models\WebsiteRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppController extends Controller
{
    public function sendRequestNotification(WebsiteRequest $request): void
    {
        $sid = env('TWILIO_ACCOUNT_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $from = env('TWILIO_WHATSAPP_FROM');
        $to = env('WHATSAPP_TO', '081917380682');

        $message = "Permintaan website baru dari {$request->nama}\n"
            . "Nomor: {$request->wa}\n"
            . "Email: {$request->email}\n"
            . "Website: {$request->nama_website}\n"
            . "Tujuan: {$request->tujuan_website}\n"
            . "Estimasi harga: Rp " . number_format((float) ($request->estimasi_harga ?? 0), 0, ',', '.');

        if (!$sid || !$token || !$from) {
            Log::info('Mode gratis aktif: notifikasi tersedia lewat email/admin dan webhook.', [
                'to' => $to,
                'request_id' => $request->id,
                'message' => $message,
            ]);
            return;
        }

        Http::asForm()->withBasicAuth($sid, $token)->post("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json", [
            'From' => $from,
            'To' => 'whatsapp:' . $to,
            'Body' => $message,
        ]);
    }

    public function sendAutoReply(string $to): void
    {
        $sid = env('TWILIO_ACCOUNT_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $from = env('TWILIO_WHATSAPP_FROM');

        $autoReply = "Terima kasih sudah mengirimkan permintaan website. Kami akan segera meninjau kebutuhan Anda.\n\n"
            . "Tim kami akan menghubungi Anda kembali dalam waktu dekat.";

        if (!$sid || !$token || !$from) {
            Log::info('Mode gratis aktif: auto-reply ditahan sampai Twilio aktif.', [
                'to' => $to,
                'message' => $autoReply,
            ]);
            return;
        }

        Http::asForm()->withBasicAuth($sid, $token)->post("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json", [
            'From' => $from,
            'To' => 'whatsapp:' . $to,
            'Body' => $autoReply,
        ]);
    }

    public function handleIncomingMessage(Request $request)
    {
        $body = trim((string) $request->input('Body', ''));
        $reply = $this->buildReply($body);

        $xml = new \SimpleXMLElement('<Response/>');
        $message = $xml->addChild('Message');
        $message->addChild('Body', $reply);

        return response($xml->asXML(), 200)->header('Content-Type', 'application/xml');
    }

    private function buildReply(string $body): string
    {
        $text = strtolower($body);

        if (str_contains($text, 'harga') || str_contains($text, 'paket')) {
            return 'Kami punya beberapa paket pembuatan website. Balas dengan kata "detail" untuk informasi lebih lanjut.';
        }

        if (str_contains($text, 'contoh') || str_contains($text, 'portfolio')) {
            return 'Silakan cek portofolio kami di halaman website ini. Kami juga siap membuat desain sesuai kebutuhan Anda.';
        }

        return 'Terima kasih telah menghubungi kami. Tim kami akan segera membantu kebutuhan website Anda. Balas dengan "paket" atau "detail" jika ingin informasi lebih lanjut.';
    }
}
