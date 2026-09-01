<?php

namespace App\Repositories;

use App\Models\StudiesReport;

class StudiesReportRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new StudiesReport();
    }

    public function findAll()
    {
        return $this->model->with('division')->latest()->get();
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(array $data, $id)
    {
        $record = $this->model->find($id);
        if ($record) {
            $record->update($data);
            return $record;
        }
        return null;
    }

    public function delete($id)
    {
        $record = $this->model->find($id);
        if ($record) {
            return $record->delete();
        }
        return false;
    }

    public function findForPublic($limit = null)
    {
        $query = $this->model->where('is_published', 1)->where('is_approved', 1)
            ->orderBy('report_year', 'desc')
            ->orderBy('created_at', 'desc');

        if ($limit) {
            return $query->limit($limit)->get();
        }
        
        return $query->get();
    }
}
