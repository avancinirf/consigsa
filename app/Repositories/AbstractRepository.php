<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class AbstractRepository {

    public function __construct($model) {
        $this->model = $model;

    }

    /* Define os campos a serem exibidos nos "files" de cada projeto. */
    public function selectRelatedRegistersAttributes($attributes) {
        $this->model = $this->model->with($attributes);
    }

    /* Define os filtros a serem aplicados nos campos para cada projeto. (cláusula WHERE) */
    public function filter($filters) {
        $filters = str_replace( '\\', '', $filters );
        $filters = explode( ';', $filters );

        foreach( $filters as $key => $filter ) {
            $f = explode(':', $filter);
            $this->model = $this->model->where($f[0], $f[1], $f[2]);
        }
    }

    /* Define os campos a serem exibidos nos projeto. */
    public function selectAttributes($attributes) {
        $this->model = $this->model->selectRaw($attributes);
    }

    public function getResult() {
        return $this->model->get();
    }

    public function getPagedResult($elementsPerPage) {
        return $this->model->paginate($elementsPerPage);
    }


}

?>
