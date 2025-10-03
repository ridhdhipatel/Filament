<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CommonCrudService
{
    protected $source;
    protected $isModel = false;
    protected $builder;

    protected $joins = [];
    protected $wheres = [];
    protected $orWheres = [];
    protected $withs = [];

    public function setSource($source)
    {
        $this->source = $source;
        $this->builder = null;
        return $this;
    }

    protected function initQuery()
    {
        if (is_null($this->builder)) {
            if (class_exists($this->source) && is_subclass_of($this->source, Model::class)) {
                $this->isModel = true;
                $this->source = new $this->source;
                $this->builder = $this->source->newQuery();
            } else {
                $this->isModel = false;
                $this->builder = DB::table($this->source);
            }
        }
        return $this->builder;
    }

    public function where(array $conditions)
    {
        $this->wheres[] = $conditions;
        return $this;
    }

    public function orWhere(array $conditions)
    {
        $this->orWheres[] = $conditions;
        return $this;
    }

    protected function buildQuery($columns = ['*'])
    {
        $query = $this->initQuery();

        if ($this->isModel && !empty($this->withs)) {
            $query->with($this->withs);
        }

        foreach ($this->wheres as $where) {
            $query->where($where);
        }

        foreach ($this->orWheres as $orWhere) {
            $query->orWhere($orWhere);
        }

        return $query->select(...(array)$columns);
    }

    public function select($source, $columns = ['*'])
    {
        $this->setSource($source);
        return $this->buildQuery($columns)->get();
    }

    public function selectWithId($source, $id, $columns = ['*'], $idColumn = 'id')
    {
        $this->setSource($source);
        $this->where([$idColumn => $id]);
        return $this->buildQuery($columns)->first();
    }

    public function create($source, array $data)
    {
        $this->setSource($source);
        $query = $this->initQuery();
        return $this->isModel
            ? $query->create($data)
            : DB::table($this->source->from ?? $this->source)->insertGetId($data);
    }

    public function update($source, $id, array $data, $idColumn = 'id')
    {
        $this->setSource($source);
        $query = $this->initQuery();
        if ($this->isModel) {
            $record = $query->find($id);
            return $record ? tap($record)->update($data) : null;
        }
        return DB::table($this->source->from ?? $this->source)->where($idColumn, $id)->update($data);
    }

    public function delete($source, $id, $idColumn = 'id')
    {
        $this->setSource($source);
        $query = $this->initQuery();
        if ($this->isModel) {
            $record = $query->find($id);
            return $record ? $record->delete() : false;
        }
        return DB::table($this->source->from ?? $this->source)->where($idColumn, $id)->delete();
    }

    public function createOrUpdate($source, array $matchCondition, array $data)
    {
        $this->setSource($source);
        $query = $this->initQuery();

        if ($this->isModel) {
            return $query->updateOrCreate($matchCondition, $data);
        }

        $exists = DB::table($this->source->from ?? $this->source)->where($matchCondition)->first();

        if ($exists) {
            DB::table($this->source->from ?? $this->source)->where($matchCondition)->update($data);
        } else {
            DB::table($this->source->from ?? $this->source)->insert($matchCondition + $data);
        }

        return DB::table($this->source->from ?? $this->source)->where($matchCondition)->first();
    }
}
