<?php

declare(strict_types=1);

namespace EMS\SubmissionBundle\Request;

final class ServiceNowRequest
{
    /** @var string */
    private $host;
    /** @var string */
    private $table;
    /** @var string */
    private $bodyEndpoint;
    /** @var string */
    private $attachmentEndpoint;
    /** @var string */
    private $username;
    /** @var string */
    private $password;

    /** @var string */
    private $body = '';
    /** @var array<array> */
    private $attachments = [];

    /**
     * @param array<string, string> $endpoint
     * @param array<string, mixed>  $message
     */
    public function __construct(array $endpoint, array $message)
    {
        $this->host = $endpoint['host'];
        $this->table = $endpoint['table'];
        $this->username = $endpoint['username'];
        $this->password = $endpoint['password'];
        $this->bodyEndpoint = ($endpoint['bodyEndpoint']) ?? '/api/now/table';
        $this->attachmentEndpoint = ($endpoint['attachmentEndpoint']) ?? '/api/now/attachment/file';

        if (!empty($message['body'])) {
            $body = \json_encode($message['body']);
            $this->body = (!empty($body)) ? $body : '';
        }

        if (!empty($message['attachments'])) {
            $this->attachments = $message['attachments'];
        }
    }

    public function getBodyEndpoint(): string
    {
        return $this->host.$this->bodyEndpoint.'/'.$this->table;
    }

    public function getAttachmentEndpoint(): string
    {
        return $this->host.$this->attachmentEndpoint;
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @return array<array>
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }

    public function getBasicAuth(): string
    {
        $credentials = \base64_encode(\sprintf('%s:%s', $this->getUsername(), $this->getPassword()));

        return \sprintf('Basic %s', $credentials);
    }
}
