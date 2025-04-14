<?php

namespace App\Enums;

enum AttachmentTypeEnum: int
{
    case TEXT = 0;
    case AUDIO = 1;
    case ARTICLE = 2;
    case IMAGE = 3;
    case POLL = 4;
    case LOCATION = 5;
    case FILE = 6;

    public function label(): string
    {
        return match ($this) {
            self::TEXT => 'text',
            self::AUDIO => 'audio',
            self::ARTICLE => 'article',
            self::IMAGE => 'image',
            self::POLL => 'poll',
            self::LOCATION => 'location',
            self::FILE => 'file',
        };
    }

    public static function fromLabel(string $label): self
    {
        return match (strtolower($label)) {
            'text' => self::TEXT,
            'audio' => self::AUDIO,
            'article' => self::ARTICLE,
            'image' => self::IMAGE,
            'poll' => self::POLL,
            'location' => self::LOCATION,
            'file' => self::FILE,
        };
    }
}
