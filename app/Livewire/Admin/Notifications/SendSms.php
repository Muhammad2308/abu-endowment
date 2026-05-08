<?php

namespace App\Livewire\Admin\Notifications;

use Livewire\Component;
use App\Models\SmsLog;
use App\Services\KudiSmsService;

class SendSms extends Component
{
    public $receiver;
    public $message;
    public $sending = false;
    public $statusMessage;
    public $statusType = 'success';

    protected $rules = [
        'receiver' => 'required|string|max:30',
        'message' => 'required|string|max:918',
    ];

    public function sendSms()
    {
        $this->validate();

        $this->sending = true;
        $this->statusMessage = null;

        $smsService = new KudiSmsService();
        $result = $smsService->sendSms($this->receiver, $this->message, 'ABU');

        $status = $result['success'] ? 'sent' : 'failed';
        $errorMessage = $result['success'] ? null : ($result['error'] ?? 'Failed to send SMS.');
        $responsePayload = is_array($result['response']) ? json_encode($result['response']) : (string) ($result['response'] ?? '');
        $cost = is_array($result['response']) ? ($result['response']['cost'] ?? null) : null;

        SmsLog::create([
            'recipient_phone' => $this->receiver,
            'sender_id' => 'ABU',
            'message' => $this->message,
            'status' => $status,
            'error_message' => $errorMessage,
            'cost' => $cost,
            'response_payload' => $responsePayload,
            'sent_at' => $result['success'] ? now() : null,
        ]);

        if ($result['success']) {
            $this->statusType = 'success';
            $this->statusMessage = 'SMS sent successfully.';
            $this->reset(['receiver', 'message']);
        } else {
            $this->statusType = 'error';
            $this->statusMessage = $errorMessage;
        }

        $this->sending = false;
    }

    public function render()
    {
        return view('livewire.admin.notifications.send-sms');
    }
}
