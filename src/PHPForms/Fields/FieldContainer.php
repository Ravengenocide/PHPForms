<?php
namespace PHPForms\Fields;
use PHPForms\Forms;
trait FieldContainer {
    protected $fields = array();
    protected $fieldNames = array();

    /**
     * Adds a FormField to this Form
     * @param FormField $field
     * @return $this
     */
    public function addField(FormField $field) {
        if ($field->getName() == '' || !isset($this->fieldNames[$field->getName()])) {
            $this->fields[] = $field;
            if ($field->getName() != '') {
                $this->fieldNames[$field->getName()] = $field;
            }
        } else {
            trigger_error("You cannot add a field with name: {$field->getName()}, since there has already been one added.");
        }

        return $this;
    }
    public function addNested(Forms\Forms $form, $name){
        $this->fields[] = $form;
        $this->fieldNames[$name] = $form;
        $form->setWrapped($name);
        return $this;
    }
    /**
     * Returns all fields that are added
     * @return array
     */
    public function getFields() {
        return $this->fields;
    }
    public function getFieldNames(){
        return $this->fieldNames;
    }
    public function getValues(){
        $result = [];
        foreach($this->fieldNames as $name=>$field){
            $result[$name] = $field->getValue();
        }
        return $result;
    }
    /**
     * Specialized method for adding a submit button to the form
     * Won't place it at the bottom
     * @param string $value
     * @param array $options
     * @return $this
     */
    public function addButton($value, array $options = []) {
        $options = array_merge(['value' => $value], $options);
        $this->fields[] = new ButtonField('', 'submit', $options);

        return $this;
    }

    /**
     * Tries to create the field, and if it doesn't exist, create a generic FormField one
     * If the specialized class exists, it is assume to take the same arguments as FormField does
     * @param $field string Name of the class, and file, that should be created
     * @param $name string See $name on __construct on FormField
     * @param $type string See $type on __construct on FormField
     * @param array $options See $options on __construct on FormField
     * @return $this
     */
    public function add($field, $name, $type, $options = []) {
        $options = array_merge([], $options);
        $field = 'PHPForms\\Fields\\' . $field . 'Field';
        if (class_exists($field)) {
            echo "Class existed";
            $this->fields[] = new $field($name, $type, $options);
        } else {
            echo "Class did not exist";
            $this->fields[] = new FormField($name, $type, $options);
        }

        return $this;
    }
}