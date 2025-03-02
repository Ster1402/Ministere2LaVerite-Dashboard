<?php

namespace App\Services\Commons;

class SMSMessageFormatter
{
    /**
     * Format a message for SMS, converting HTML to plain text and creating a structured message
     *
     * @param string $subject The message subject
     * @param string $category The message category
     * @param string $htmlContent The HTML content of the message
     * @param int $maxLength Maximum SMS length (default 160 characters)
     * @return string Formatted SMS message
     */
    public function formatSMSMessage(
        string $subject,
        string $category,
        string $htmlContent,
        int $maxLength = 160
    ): string {
        // Step 1: Convert HTML to plain text
        $plainTextContent = $this->convertHtmlToPlainText($htmlContent);

        // Step 2: Create a structured message template
        $formattedMessage = $this->createStructuredMessage(
            $subject,
            $category,
            $plainTextContent
        );

        // Step 3: Truncate if message is too long
        return $this->truncateMessage($formattedMessage, $maxLength);
    }

    /**
     * Convert HTML content to plain text
     *
     * @param string $htmlContent HTML-formatted message
     * @return string Plain text message
     */
    private function convertHtmlToPlainText(string $htmlContent): string
    {
        // Replace <p> tags with new lines
        $newLineContent = preg_replace('/<\/p>\s*<p>/', "\n", $htmlContent);

        // Remove opening and closing <p> tags
        $newLineContent = preg_replace('/<\/?p>/', '', $newLineContent);

        // Remove other HTML tags
        $plainText = strip_tags($newLineContent);

        // Decode HTML entities
        $plainText = html_entity_decode($plainText, ENT_QUOTES, 'UTF-8');

        // Normalize whitespace and trim
        $plainText = preg_replace('/\s+/', ' ', trim($plainText));

        return $plainText;
    }

    /**
     * Create a structured message with subject, category, and content
     *
     * @param string $subject Message subject
     * @param string $category Message category
     * @param string $content Plain text content
     * @return string Formatted message
     */
    private function createStructuredMessage(
        string $subject,
        string $category,
        string $content
    ): string {
        // Create a professional, informative message structure
        $messageParts = [];

        // Add subject if not empty
        if (!empty(trim($subject))) {
            $messageParts[] = "📌 Sujet: {$subject}";
        }

        // Add category if not empty
        if (!empty(trim($category))) {
            $messageParts[] = "🏷️ Catégorie: {$category}";
        }

        // Add content
        $messageParts[] = "📝 Message:";
        $messageParts[] = "";
        $messageParts[] = $content;
        $messageParts[] = "";
        $messageParts[] = "🙏🏿 " . env('APP_NAME');

        // Join parts with line breaks
        return implode("\n", $messageParts);
    }

    /**
     * Truncate message to fit within SMS length limit
     *
     * @param string $message Full message
     * @param int $maxLength Maximum allowed length
     * @return string Truncated message
     */
    private function truncateMessage(string $message, int $maxLength): string
    {
        // If message is already within limit, return as-is
        if (mb_strlen($message) <= $maxLength) {
            return $message;
        }

        // Truncate and add ellipsis
        $truncatedMessage = mb_substr($message, 0, $maxLength - 3) . '...';

        return $truncatedMessage;
    }

    /**
     * Preview message formatting without sending
     *
     * @param string $subject Message subject
     * @param string $category Message category
     * @param string $htmlContent HTML content
     * @return array Message details
     */
    public function previewMessageFormat(
        string $subject,
        string $category,
        string $htmlContent
    ): array {
        // Convert HTML to plain text
        $plainTextContent = $this->convertHtmlToPlainText($htmlContent);

        return [
            'original_html' => $htmlContent,
            'plain_text_content' => $plainTextContent,
            'formatted_sms' => $this->formatSMSMessage($subject, $category, $htmlContent),
            'message_length' => mb_strlen($this->formatSMSMessage($subject, $category, $htmlContent))
        ];
    }
}
