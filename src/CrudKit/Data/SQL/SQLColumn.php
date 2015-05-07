<?php

namespace CrudKit\Data\SQL;


use CrudKit\Util\FormHelper;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Types\Type;

abstract class SQLColumn {
    public $id = null;
    public $category = null;
    public $options = array();

    /**
     * @var Type
     */
    protected $type = null;

    /**
     * @var string
     */
    protected $typeName = null;

    const CATEGORY_VALUE = "value";
    const CATEGORY_PRIMARY = "primary";
    const CATEGORY_FOREIGN = "foreign";

    public function __construct ($id, $category, $options) {
        $this->id = $id;
        $this->category = $category;
        $this->options = $options;
    }

    public function doctrineColumnLookup ($col_lookup) {
        if(isset($this->options['expr']) && isset($col_lookup[$this->options['expr']]))
        {
            /**
             * @var $col Column
             */
            $col = $col_lookup[$this->options['expr']];
            $this->type = $col->getType();
            $this->typeName = $this->type->getName();
        }
    }

    public function setOptions($values) {
        $this->options = array_merge($this->options, $values);
    }

    public function getExprAs() {
        if(is_null($this->getExpr()))
        {
            // Some columns don't get expressions. In those case return null
            return null;
        }

        return $this->getExpr()." AS ".$this->id;
    }

    public function init () {
        // A child will override this
    }

    /**
     * @param $form FormHelper
     * @return mixed
     */
    public abstract function updateForm ($form);
    public abstract function getSchema ();
    public abstract function getExpr ();
    public abstract function getSummaryConfig ();
}