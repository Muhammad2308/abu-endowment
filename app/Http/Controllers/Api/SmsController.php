<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Validator;

class SmsController extends Controller
{
    public function sendSms(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'to' => ['required', 'regex:/^\+\d{10,15}$/'],
            'message' => ['required', 'string', 'max:1600'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors()->first(),
            ], 422);
        }

        $to = $request->input('to');
        $message = $request->input('message');

        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $from = env('TWILIO_PHONE_NUMBER');
        $messagingServiceSid = env('TWILIO_MESSAGING_SERVICE_SID');

        try {
            $twilio = new Client($sid, $token);

            $messageData = [
                'body' => $message,
            ];
            if ($messagingServiceSid) {
                $messageData['messagingServiceSid'] = $messagingServiceSid;
            } elseif ($from) {
                $messageData['from'] = $from;
            }

            $sms = $twilio->messages->create($to, $messageData);

            return response()->json([
                'success' => true,
                'sid' => $sms->sid,
                'message' => 'Message sent',
            ]);
        } catch (\Twilio\Exceptions\RestException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getMessages()
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $twilio = new Client($sid, $token);

        try {
            $messages = $twilio->messages->read([], 20); // last 20 messages
            $result = [];
            foreach ($messages as $msg) {
                $result[] = [
                    'sid' => $msg->sid,
                    'to' => $msg->to,
                    'from' => $msg->from,
                    'body' => $msg->body,
                    'status' => $msg->status,
                    'date_sent' => $msg->dateSent,
                ];
            }
            return response()->json(['success' => true, 'messages' => $result]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
} 