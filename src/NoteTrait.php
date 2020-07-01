<?php

namespace App\ModelService;



use App\ModelService\Model\FieldNote;
use Illuminate\Database\Eloquent\Model;

trait NoteTrait
{
    protected $note_fields_suffix = [];

    public function __construct()
    {
        Model::__construct();
        $this->noteFieldsWithSuffix();
    }


    public function scopeNote($query, array $input = []){
        if(!empty($input)){
            $this->note_fields = is_string($input) ? func_get_args() : $input;
        }
        $this->noteFieldsWithSuffix();
        return $query->with($this->note_fields_suffix);
    }

    public function __call($method,$args)
    {

        if(in_array($method,$this->note_fields_suffix)){
            $field =  substr($method,0,-strlen(config('fieldnote.suffix')));

            return $this->setUpBeloogsTo($field);

        }else{
            return Model::__call($method,$args);
        }
    }


    /**
     * get table all Note
     * @return mixed
     */
    public function getAllNote(){
        return FieldNote::where('table',$this->getTable())->orderBy('field')->orderBy('value')->get();
    }

    /**
     * get a note
     * @param $field
     * @param $value
     */
    public function getNote($field,$value){
        return FieldNote::where('table',$this->getTable())->where('field',$field)->where('value',$value)->first();
    }

    /**
     * get note by field
     * @param $field
     * @return mixed
     */
    public function getNoteByField($field){
        return FieldNote::where('table',$this->getTable())->where('field',$field)->orderBy('value')->get();
    }

    /**
     * has note
     * @param $field
     * @param $value
     * @return bool
     */
    public function hasNote($field,$value){
        return FieldNote::where('table',$this->getTable())->where('field',$field)->where('value',$value)->first()
            ? true : false;
    }

    /**
     * Set up beloogsto
     * @param $field
     * @return mixed
     */
    protected function setUpBeloogsTo($field){
        return $this->belongsTo(FieldNote::class, $field, 'value')
            ->where('field',$field)
            ->where('table', $this->getTable())->withDefault([
                'id'   => 0,
                'table' => $this->getTable(),
                'field' => $field,
                'value' => '',
                'note' => 'please add field note.',
                'created_at' => '',
                'updated_at' => '',
            ]);
    }

    /**
     * add suffix
     */
    protected function noteFieldsWithSuffix(){

        foreach($this->note_fields as $note_field){
            $this->note_fields_suffix[] = $note_field.config('fieldnote.suffix');
        }
    }


}
