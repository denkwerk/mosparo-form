<?php
declare(strict_types=1);

namespace Denkwerk\MosparoForm\Settings\Type;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;
use TYPO3\CMS\Core\Settings\SettingDefinition;
use TYPO3\CMS\Core\Settings\SettingsTypeInterface;

#[AsTaggedItem(index: 'password')]
readonly class PasswordType implements SettingsTypeInterface
{
    private const ENCRYPTION_PREFIX = '###ENCRYPTED###';

    public function __construct(
        protected LoggerInterface $logger,
    ) {}

    /**
     * @phpstan-ignore-next-line
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function validate(mixed $value, SettingDefinition $definition): bool
    {
        if (is_string($value)) {
            return true;
        }
        if ($value === null) {
            return true;
        }
        return false;
    }

    public function transformValue(mixed $value, SettingDefinition $definition): string
    {
        if (!$this->validate($value, $definition)) {
            $this->logger->warning('Setting validation field, reverting to default: {key}', ['key' => $definition->key]);
            $defaultValue = $definition->default ?? '';
            $defaultValueString = is_string($defaultValue) ? $defaultValue : '';
            return $this->encryptIfNeeded($defaultValueString);
        }

        if ($value === null || $value === '') {
            return '';
        }

        $stringValue = (string)$value;

        // If the value is already encrypted, return it as is
        if ($this->isEncrypted($stringValue)) {
            return $stringValue;
        }

        // Encrypt new value
        return $this->encryptIfNeeded($stringValue);
    }

    /**
     * Encrypts a value using the TYPO3 encryptionKey
     */
    private function encryptIfNeeded(string $value): string
    {
        if ($value === '') {
            return '';
        }

        $encryptionKey = $GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'] ?? '';
        if (empty($encryptionKey)) {
            $this->logger->error('Encryption key not found, password will be stored unencrypted');
            return $value;
        }

        // Use AES-256-CBC for encryption
        $cipher = 'AES-256-CBC';
        $ivLength = openssl_cipher_iv_length($cipher);
        // @phpstan-ignore-next-line
        if ($ivLength === false) {
            $this->logger->error('Failed to get IV length for encryption');
            return $value;
        }

        $initializationVector = openssl_random_pseudo_bytes($ivLength);
        // @phpstan-ignore-next-line
        if ($initializationVector === false) {
            $this->logger->error('Failed to generate IV for encryption');
            return $value;
        }

        // Use a derived key from the encryptionKey
        $key = hash('sha256', $encryptionKey, true);
        $encrypted = openssl_encrypt($value, $cipher, $key, OPENSSL_RAW_DATA, $initializationVector);
        if ($encrypted === false) {
            $this->logger->error('Failed to encrypt password');
            return $value;
        }

        // Combine IV and encrypted value and encode as base64
        $encryptedValue = base64_encode($initializationVector . $encrypted);
        return self::ENCRYPTION_PREFIX . $encryptedValue;
    }

    /**
     * Checks if a value is already encrypted
     */
    private function isEncrypted(string $value): bool
    {
        return str_starts_with($value, self::ENCRYPTION_PREFIX);
    }

    /**
     * Decrypts an encrypted value
     */
    public function decrypt(string $encryptedValue): string
    {
        if (!$this->isEncrypted($encryptedValue)) {
            return $encryptedValue;
        }

        // Remove prefix
        $encryptedData = substr($encryptedValue, strlen(self::ENCRYPTION_PREFIX));

        $encryptionKey = $GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'] ?? '';
        if (empty($encryptionKey)) {
            $this->logger->error('Encryption key not found, cannot decrypt password');
            return '';
        }

        $cipher = 'AES-256-CBC';
        $ivLength = openssl_cipher_iv_length($cipher);
        // @phpstan-ignore-next-line
        if ($ivLength === false) {
            $this->logger->error('Failed to get IV length for decryption');
            return '';
        }

        $decoded = base64_decode($encryptedData, true);
        if ($decoded === false) {
            $this->logger->error('Failed to decode encrypted password');
            return '';
        }

        $initializationVector = substr($decoded, 0, $ivLength);
        $encrypted = substr($decoded, $ivLength);

        $key = hash('sha256', $encryptionKey, true);
        $decrypted = openssl_decrypt($encrypted, $cipher, $key, OPENSSL_RAW_DATA, $initializationVector);
        if ($decrypted === false) {
            $this->logger->error('Failed to decrypt password');
            return '';
        }

        return $decrypted;
    }

    public function getJavaScriptModule(): string
    {
        return '@denkwerk/mosparo-form/settings/type/password.js';
    }
}

