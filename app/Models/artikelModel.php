<?php

namespace App\Models;

use CodeIgniter\Model;

class artikelModel extends Model
{
    protected $table = 'artikel';
    protected $allowedFields = ['judul', 'slug', 'ringkasan', 'konten', 'status', 'published_at'];

    public function getPublished(int $limit = 0): array
    {
        $builder = $this->articleQuery()->where('artikel.status', 'publish');
        $builder->orderBy('artikel.published_at', 'DESC');
        if ($limit > 0) {
            $builder->limit($limit);
        }

        return $builder->get()->getResultArray();
    }

    public function getPublishedBySlug(string $slug): ?array
    {
        $row = $this->articleQuery()
            ->where('artikel.status', 'publish')
            ->where('artikel.slug', $slug)
            ->get()
            ->getFirstRowArray();

        return $row ?? null;
    }

    public function getAdminArticles(): array
    {
        return $this->articleQuery()
            ->orderBy('artikel.id', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getCategories(): array
    {
        return $this->db->table('kategori_artikel')
            ->orderBy('nama')
            ->get()
            ->getResultArray();
    }

    public function getCategoryOptions(): array
    {
        $options = [];
        foreach ($this->getCategories() as $category) {
            $options[(int) $category['id']] = $category['nama'];
        }

        return $options;
    }

    public function getCategoryIds(int $articleId): array
    {
        $rows = $this->db->table('artikel_kategori')
            ->select('kategori_id')
            ->where('artikel_id', (int) $articleId)
            ->get()
            ->getResultArray();

        return array_map(static function (array $row): int {
            return (int) $row['kategori_id'];
        }, $rows);
    }

    public function findById(int $id): ?array
    {
        $row = $this->db->table('artikel')
            ->where('id', (int) $id)
            ->get()
            ->getFirstRowArray();

        return $row ?? null;
    }

    public function findBySlug(string $slug): ?array
    {
        $row = $this->db->table('artikel')
            ->where('slug', $slug)
            ->get()
            ->getFirstRowArray();

        return $row ?? null;
    }

    public function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $builder = $this->db->table('artikel')->where('slug', $slug);
        if ($excludeId !== null) {
            $builder->where('id !=', (int) $excludeId);
        }

        return $builder->countAllResults() > 0;
    }

    public function saveCategories(int $articleId, array $categoryIds): void
    {
        $articleId = (int) $articleId;
        $this->db->table('artikel_kategori')->where('artikel_id', $articleId)->delete();

        $rows = [];
        foreach (array_unique($categoryIds) as $categoryId) {
            $rows[] = [
                'artikel_id' => $articleId,
                'kategori_id' => (int) $categoryId,
            ];
        }

        if ($rows !== []) {
            $this->db->table('artikel_kategori')->insertBatch($rows);
        }
    }

    private function articleQuery()
    {
        return $this->db->table('artikel')
            ->select('artikel.id, artikel.judul, artikel.slug, artikel.ringkasan, artikel.konten, artikel.status, artikel.published_at, kategori_artikel.nama AS nama_kategori, kategori_artikel.id AS kategori_id')
            ->join('artikel_kategori', 'artikel_kategori.artikel_id = artikel.id', 'left')
            ->join('kategori_artikel', 'kategori_artikel.id = artikel_kategori.kategori_id', 'left');
    }
}
