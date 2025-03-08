<?php

namespace App\Actions\Ministere2LaVerite;

use App\DTOs\messages\MessageDTO;
use App\Models\Assembly;
use App\Models\Message;
use App\Models\User;
use App\Services\Commons\SMSMessageFormatter;
use App\Services\messages\ApiMessageService;

class StoreMessageAction
{
    public function execute(MessageDTO $messageDTO): void
    {
        if ($messageDTO->shouldSendSMS) {

            try {
                $apiMessageService = app(ApiMessageService::class);
                $this->sendMessages($messageDTO, $apiMessageService);
                session()->flash('success', 'Message sent successfully');
            } catch (\Exception $e) {
                ddd($e);
                session()->flash('error', 'Messages was not sent successfully');
            }
        }

        $message = Message::create([
            'subject' => $messageDTO->subject,
            'seen' => $messageDTO->seen,
            'tags' => $messageDTO->tags,
            'category' => $messageDTO->category,
            'content' => $messageDTO->content,
            'picture_path' => $messageDTO->picturePath,
            'message_id' => $messageDTO->messageId,
            'received' => $messageDTO->received,
            'receiverId' => $messageDTO->receiverId,
            'senderId' => $messageDTO->senderId,
        ]);

        foreach ($messageDTO->assembliesId as $assemblyId) {
            $message->update(['receiverId' => null]);
            $assembly = Assembly::firstWhere('id', $assemblyId);
            $message->assemblies()->attach($assembly);
        }
    }

    /**
     * Send SMS to eligible users
     *
     * @param MessageDTO $messageDTO Message details
     * @param ApiMessageService $apiMessageService SMS sending service
     * @return bool
     */
    public function sendMessages(MessageDTO $messageDTO, ApiMessageService $apiMessageService)
    {
        // Retrieve eligible users based on message criteria
        $users = $this->getEligibleUsers($messageDTO);

        // Get message content
        $messageContent = $messageDTO->content;
        // Get Message formatter
        $messageFormatter = app(SMSMessageFormatter::class);

        $messageContent = $messageFormatter->formatSMSMessage(
            subject: $messageDTO->subject,
            category: $messageDTO->category,
            htmlContent: $messageDTO->content,
            maxLength: 1800
        );

        // Track successful and failed message sends
        $successCount = 0;
        $failedCount = 0;

        // Loop through eligible users and send SMS
        foreach ($users as $user) {
            // Get user's phone number
            $phoneNumber = $user->phoneNumber;

            // Skip if phone number is invalid
            if (!$this->isValidPhoneNumber($phoneNumber)) {
                $failedCount++;
                continue;
            }

            // Format phone number consistently
            $formattedPhoneNumber = $this->formatPhoneNumber($phoneNumber);

            try {
                // Attempt to send SMS
                $apiMessageService->send(
                    to: $formattedPhoneNumber,
                    message: $messageContent
                );
                $successCount++;
            } catch (\Exception $e) {
                // Log failed message send
                \Log::error('Failed to send SMS to ' . $formattedPhoneNumber . ': ' . $e->getMessage());
                $failedCount++;
            }
        }

        // Log summary of message sending
        \Log::info("Message Send Summary: Successful - {$successCount}, Failed - {$failedCount}");

        return $successCount > 0;
    }

    /**
     * Retrieve eligible users for message sending
     *
     * @param MessageDTO $messageDTO Message details
     * @return \Illuminate\Support\Collection
     */
    private function getEligibleUsers(MessageDTO $messageDTO)
    {
        // Base query to get users
        $query = User::query();

        // Filter by assembly if specified
        if (!empty($messageDTO->assembliesId)) {
            $query->whereHas('assemblies', function ($q) use ($messageDTO) {
                $q->whereIn('assemblies.id', $messageDTO->assembliesId);
            });
        }

        // Ensure only users with valid phone numbers are selected
        $query->whereNotNull('phoneNumber')
            ->where('phoneNumber', '!=', '');

        // Return the filtered users
        return $query->get();
    }

    /**
     * Format phone number to ensure it starts with the country code
     * Default country code is +237 (Cameroon)
     *
     * @param string $phoneNumber Raw phone number
     * @return string Formatted phone number
     */
    private function formatPhoneNumber(string $phoneNumber): string
    {
        // Default country code
        $countryCode = '+237';

        // Remove any non-numeric characters except '+'
        $phoneNumber = preg_replace('/[^\d+]/', '', $phoneNumber);

        // If the phone number doesn't start with '+', prepend the country code
        if (!str_starts_with($phoneNumber, '+')) {
            $phoneNumber = $countryCode . trim(ltrim($phoneNumber, '0'));
        }

        return $phoneNumber;
    }

    /**
     * Validate if a string is a valid phone number
     *
     * @param string $phoneNumber Phone number to validate
     * @return bool Whether the phone number is valid
     */
    private function isValidPhoneNumber(?string $phoneNumber): bool
    {
        // Regex for validating phone numbers
        // This example is for Cameroonian phone numbers (9 digits after country code)
        // Adjust the regex based on your specific phone number format requirements
        $phoneNumberRegex = '/^(\+237)?[26][0-9]{8}$/';

        return preg_match($phoneNumberRegex, $phoneNumber) === 1;
    }
}
