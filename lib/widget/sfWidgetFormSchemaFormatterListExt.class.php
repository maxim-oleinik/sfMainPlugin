<?php

/**
 * Рисовальщик форм
 */
class sfWidgetFormSchemaFormatterListExt extends sfWidgetFormSchemaFormatter
{
    /**
     * Config props
     */
    protected
        $rowFormat = "<li class='%class%form-item'>\n%label%\n%help%\n%error%\n%field%\n%hidden_fields%</li>\n",
        $errorRowFormat  = "<li>\n%errors%</li>\n",
        $helpFormat      = '<div class="help">%help%</div>',
        $decoratorFormat = "<ul>\n%content%</ul>";

    /**
     * sfValidatorSchema
     */
    protected $validatorSchema = null;


    /**
     * Зареристрировать набор валидаторов
     *
     * @param sfValidatorSchema $schema
     * @return void
     */
    public function setValidatorSchema(sfValidatorSchema $schema)
    {
        $this->validatorSchema = $schema;
    }


    /**
     * Создать HTML-тег LABEL
     *
     * Помечает "label" классом "required" в соответствии с настройками валидатора
     *
     * @return string
     */
    public function generateLabel($name, $attributes = array())
    {
        if (!empty($attributes['class'])) {
            $class = explode(' ', $attributes['class']);
        } else {
            $class = array();
        }

        // Required
        if ($this->validatorSchema && $this->validatorSchema[$name]->getOption('required')) {
            $class[] = 'required';
        }

        if ($class) {
            $attributes['class'] = implode(' ', $class);
        }

        return parent::generateLabel($name, $attributes);
    }


    /**
     * Создать содержимое для тега LABEL
     *
     * Создает имя по аналогии с ID
     *
     * @return string
     */
    public function generateLabelName($name)
    {
        $label = $this->widgetSchema->getLabel($name);

        if (!$label && false !== $label) {
            if ('_csrf_token' != $name) {
                $label = 'form_field: ' . $this->widgetSchema->generateId($this->widgetSchema->generateName($name));
            } else {
                $label = 'csrf_token';
            }
            $label = $this->translate($label);
        }

        return $label;
    }


    /**
     * Отрисовать поле формы
     *   - хак, чтобы поменять местами checkbox и label
     *   - добавить class в LI
     *
     * @return string
     */
    public function formatRow($label, $field, $errors = array(), $help = '', $hiddenFields = null)
    {
        preg_match('/for="([^"]+)"/', $label, $matches);

        if (strpos($field, 'checkbox') !== false) {
            $tmp = $field;
            $field = $label;
            $label = $tmp;
        }

        $class = !empty($matches[1]) ? "form-item-{$matches[1]} ": '';
        $class = str_replace('_', '-', $class);

        return strtr($this->getRowFormat(), array(
            '%class%'         => $class,
            '%label%'         => $label,
            '%field%'         => $field,
            '%error%'         => $this->formatErrorsForRow($errors),
            '%help%'          => $this->formatHelp($help),
            '%hidden_fields%' => null === $hiddenFields ? '%hidden_fields%' : $hiddenFields,
        ));
    }

}
