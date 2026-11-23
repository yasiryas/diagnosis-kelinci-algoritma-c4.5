<?php

namespace App\Services;

final class DecisionTreeGraph
{
    private const DEFAULT_ROOT = 'Umum';
    private const X_ORIGIN = 40;
    private const X_STEP = 180;
    private const Y_ORIGIN = 40;
    private const Y_STEP = 110;
    private const NODE_WIDTH = 150;
    private const NODE_HEIGHT = 44;
    private const NODE_MARGIN = 40;
    private const EMPTY_MESSAGE = 'Tidak ada pohon. Jalankan Proses Mining.';
    private const EMPTY_TEXT_X = 10;
    private const EMPTY_TEXT_Y = 28;
    private const EMPTY_VIEWBOX_WIDTH = 480;
    private const EMPTY_VIEWBOX_HEIGHT = 48;

    private array $nodes = [];
    private array $edges = [];
    private array $depthCounts = [];
    private array $symptomLabels;

    public function __construct(array $rows, array $symptomLabels = [])
    {
        $this->symptomLabels = $symptomLabels;
        $rootIds = [];
        $symptomIds = [];

        foreach ($rows as $row) {
            $akar = trim((string) ($row['akar'] ?? ''));
            $rootKey = $akar === '' ? self::DEFAULT_ROOT : $akar;
            if (!isset($rootIds[$rootKey])) {
                $rootIds[$rootKey] = $this->addNode($this->symptomLabels[$akar] ?? $rootKey, 'root', 0);
            }

            $previousId = $rootIds[$rootKey];
            $tokens = preg_split('/\s+/', trim((string) ($row['parent'] ?? '')));
            $tokens = $tokens === false
                ? []
                : array_values(array_filter($tokens, static fn ($token): bool => $token !== ''));

            foreach ($tokens as $index => $token) {
                $symptomKey = $rootKey . "\0" . $token;
                if (!isset($symptomIds[$symptomKey])) {
                    $symptomIds[$symptomKey] = $this->addNode(
                        $this->symptomLabels[$token] ?? $token,
                        'symptom',
                        $index + 1
                    );
                }

                $this->addEdge($previousId, $symptomIds[$symptomKey]);
                $previousId = $symptomIds[$symptomKey];
            }

            $diseaseId = $this->addNode((string) ($row['penyakit'] ?? ''), 'disease', count($tokens) + 1);
            $this->addEdge($previousId, $diseaseId);
        }
    }

    public function nodes(): array
    {
        return $this->nodes;
    }

    public function edges(): array
    {
        return $this->edges;
    }

    public function toSvg(): string
    {
        $maxX = 0;
        $maxY = 0;
        foreach ($this->nodes as $node) {
            $maxX = max($maxX, $node['x']);
            $maxY = max($maxY, $node['y']);
        }

        $isEmpty = $this->nodes === [];
        $width = $isEmpty ? self::EMPTY_VIEWBOX_WIDTH : $maxX + self::NODE_WIDTH + self::NODE_MARGIN;
        $height = $isEmpty ? self::EMPTY_VIEWBOX_HEIGHT : $maxY + self::NODE_HEIGHT + self::NODE_MARGIN;
        $svg = '<svg class="decision-tree-svg" viewBox="0 0 ' . $width . ' ' . $height
            . '" role="img" aria-label="Pohon keputusan">';

        if ($isEmpty) {
            return $svg . '<text x="' . self::EMPTY_TEXT_X . '" y="' . self::EMPTY_TEXT_Y . '">'
                . $this->escape(self::EMPTY_MESSAGE) . '</text></svg>';
        }

        $nodes = $this->indexNodes();
        foreach ($this->edges as $edge) {
            if (!isset($nodes[$edge['from']], $nodes[$edge['to']])) {
                continue;
            }

            $source = $nodes[$edge['from']];
            $target = $nodes[$edge['to']];
            $startX = $source['x'] + intdiv(self::NODE_WIDTH, 2);
            $startY = $source['y'] + self::NODE_HEIGHT;
            $endX = $target['x'] + intdiv(self::NODE_WIDTH, 2);
            $endY = $target['y'];
            $middleY = intdiv($startY + $endY, 2);
            $svg .= '<path class="tree-edge" d="M ' . $startX . ' ' . $startY
                . ' C ' . $startX . ' ' . $middleY . ' ' . $endX . ' ' . $middleY
                . ' ' . $endX . ' ' . $endY . '"/>';
        }

        foreach ($this->nodes as $node) {
            $svg .= '<g tabindex="0" class="tree-node" transform="translate('
                . $node['x'] . ' ' . $node['y'] . ')">';
            $svg .= '<title>' . $this->escape($node['label']) . '</title>';
            $svg .= '<rect width="' . self::NODE_WIDTH . '" height="' . self::NODE_HEIGHT . '"/>';
            $svg .= '<text x="10" y="28">' . $this->escape($node['label']) . '</text>';
            $svg .= '</g>';
        }

        return $svg . '</svg>';
    }

    public function toAccessibleList(): string
    {
        if ($this->nodes === []) {
            return '';
        }

        $nodes = $this->indexNodes();
        $children = [];
        foreach ($this->edges as $edge) {
            $children[$edge['from']][] = $edge['to'];
        }

        $items = '';
        foreach ($this->nodes as $node) {
            if ($node['kind'] === 'root') {
                $items .= $this->renderListItem($node['id'], $nodes, $children, []);
            }
        }

        return '<div class="sr-only"><ul>' . $items . '</ul></div>';
    }

    private function addNode(string $label, string $kind, int $depth): string
    {
        $index = $this->depthCounts[$depth] ?? 0;
        $this->depthCounts[$depth] = $index + 1;
        $id = 'n' . (count($this->nodes) + 1);

        $this->nodes[] = [
            'id' => $id,
            'label' => $label,
            'kind' => $kind,
            'x' => self::X_ORIGIN + $index * self::X_STEP,
            'y' => self::Y_ORIGIN + $depth * self::Y_STEP,
        ];

        return $id;
    }

    private function addEdge(string $from, string $to): void
    {
        $this->edges[] = ['from' => $from, 'to' => $to];
    }

    private function indexNodes(): array
    {
        $index = [];
        foreach ($this->nodes as $node) {
            $index[$node['id']] = $node;
        }

        return $index;
    }

    private function renderListItem(string $id, array $nodes, array $children, array $visited): string
    {
        if (isset($visited[$id]) || !isset($nodes[$id])) {
            return '';
        }

        $visited[$id] = true;
        $item = '<li>' . $this->escape($nodes[$id]['label']);
        $childItems = '';
        foreach ($children[$id] ?? [] as $childId) {
            $childItems .= $this->renderListItem($childId, $nodes, $children, $visited);
        }

        if ($childItems !== '') {
            $item .= '<ul>' . $childItems . '</ul>';
        }

        return $item . '</li>';
    }

    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}
