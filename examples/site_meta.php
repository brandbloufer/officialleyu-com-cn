<?php

/**
 * Site metadata container and description generator.
 *
 * This file defines a simple structure for managing site metadata and generating
 * a brief descriptive text based on the stored values.
 */

class SiteMeta
{
    private array $data;

    /**
     * Constructor.
     *
     * @param array $metadata Associative array of site metadata.
     */
    public function __construct(array $metadata = [])
    {
        $this->data = $metadata;
    }

    /**
     * Set a metadata value.
     *
     * @param string $key   The metadata key.
     * @param mixed  $value The metadata value.
     */
    public function set(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    /**
     * Get a metadata value.
     *
     * @param string $key     The metadata key.
     * @param mixed  $default Default value if key not found.
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * Generate a brief description text from the stored metadata.
     *
     * The description combines the site name, description, URL and a keyword.
     * The keyword is taken from a predefined list based on a seed offset.
     *
     * @param string $seed Optional seed string to vary keyword selection.
     * @return string The generated description.
     */
    public function generateDescription(string $seed = ''): string
    {
        $name = $this->get('name', 'Unnamed Site');
        $desc = $this->get('description', 'No description available.');
        $url  = $this->get('url', '');
        $kw   = $this->selectKeyword($seed);

        $parts = array_filter([
            $name,
            $desc,
            $url ? "Link: $url" : null,
            $kw ? "Keyword: $kw" : null,
        ]);

        return implode(' — ', $parts);
    }

    /**
     * Select a keyword from a fixed list using a simple hash of the seed.
     *
     * This is a deterministic but varied selection, not cryptographic.
     *
     * @param string $seed The seed string.
     * @return string The selected keyword.
     */
    private function selectKeyword(string $seed): string
    {
        $keywords = [
            '乐鱼体育',
            '电竞娱乐',
            '体育赛事',
            '在线投注',
            '玩家社区',
        ];

        if (empty($seed)) {
            return $keywords[0] ?? '';
        }

        $index = crc32($seed) % count($keywords);
        if ($index < 0) {
            $index = -$index;
        }

        return $keywords[$index] ?? '';
    }

    /**
     * Export all metadata as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * Create a SiteMeta instance with default example metadata.
     *
     * @return self
     */
    public static function createExample(): self
    {
        return new self([
            'name'        => 'Official LeYu Sports',
            'description' => 'Your premier destination for sports entertainment.',
            'url'         => 'https://officialleyu.com.cn',
            'author'      => 'LeYu Team',
            'version'     => '1.0.0',
        ]);
    }
}

// --- Example usage (can be removed when integrated) ---

$meta = SiteMeta::createExample();
$meta->set('language', 'zh-CN');

echo "Metadata array:\n";
print_r($meta->toArray());

echo "\nGenerated description (default seed):\n";
echo htmlspecialchars($meta->generateDescription(), ENT_QUOTES, 'UTF-8') . "\n";

echo "\nGenerated description (with seed 'abc123'):\n";
echo htmlspecialchars($meta->generateDescription('abc123'), ENT_QUOTES, 'UTF-8') . "\n";