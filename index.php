<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<?php
/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2015-03-24
 * Time: 10:00
 */
define('__SITEROOT__', realpath(dirname(__FILE__)));
function __autoload($name){
    echo $name;
    if(file_exists(__SITEROOT__ . '/src/' . $name . '.php')){
        include_once __SITEROOT__ . '/src/' . $name . '.php';
    }

}
use PHPForms\Forms\FormBuilder;
use PHPForms\Fields\FormField;
use PHPForms\Fields\ButtonField;
use PHPForms\Fields\ButtonButtonField;
use PHPForms\Fields\PasswordField;
use PHPForms\Fields\TextareaField;
use PHPForms\Validators\Validator;
use PHPForms\Fields\LegendField;

class ValidatorYes implements Validator{
    public function validate($value){
        echo "Validate called with some odd value:  $value";
        return "This is not right!";
    }
}
$fieldset = new \PHPForms\Fields\FieldsetField();
$fieldset->addField(new ButtonField('', 'button', ['value'=>'Empty click']));
$fieldset->addField(new LegendField('', '', ['value'=>'Testing, testing']));
$select = new \PHPForms\Fields\SelectField();
$select->addField(new \PHPForms\Fields\OptionField('','',['value'=>'1','text'=>'My text']));
$select->addField(new \PHPForms\Fields\OptionField('','',['value'=>'2', 'text'=>'Some other text']));
$formbuilder = new FormBuilder();

// The fields are instantiated with the values $name, $type, $options
// Text that should appear under value, is placed under $options['value'] and so on
// Attributes are placed under $options['attributes'] and css - classes $options['classes']

$formbuilder->addField(
        new FormField('test', 'number', ['value'=>"Test"], [new \PHPForms\Validators\ValueRangeValidator(1, 3, "Value must be between 1 and 3")/*, new \PHPForms\Validators\MinValueValidator(8, "Value must be at least 8.")*/, new \PHPForms\Validators\MaxValueValidator(5, "Value must be at most 5."), new \PHPForms\Validators\RegexValidator('/3/', "Must not be 3", true),
        new \PHPForms\Validators\RegexValidator('/2/', "Must be 2", false), function($value){
                return "No can do!";
            }]))
    ->addButton('Submit', ['onclick' => 'alert("test")', 'style'=>'border:10px solid black;'])
    ->addField(new ButtonField('', 'button', ['value'=>'Empty click','label'=>['wrap'=>true, 'value'=>'This is my label']]))
    ->addField(new ButtonButtonField('', '',['value'=>'Hello there']))
    ->addField(new PasswordField('password'))
    ->addField(new ButtonField('', 'submit', ['value'=>'Another one']))
    ->add('Button', '', '')
    ->add('PasswordField', '','')
    ->addField(new TextareaField('test-name', '', ['value'=>'Hello the textarea']))
    ->addField(new FormField('someothername', 'text'))
    ->addField($select)
    ->addField($fieldset);

echo $formbuilder->form->asParagraph();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $formbuilder->addData($_POST);
} else if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $formbuilder->addData($_GET);
}

echo $formbuilder->form->asUnorderedList();

$formbuilder->form->setMethod('POST');
echo $formbuilder->form->asTable();

var_dump($formbuilder->form->getErrors());

if($formbuilder->form->isValid()){
    echo "The form is valid";
} else {
    echo "The form is not valid";
}
?>
</body>
</html>