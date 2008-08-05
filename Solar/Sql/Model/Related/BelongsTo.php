<?php
/**
 * 
 * Represents the characteristics of a relationship where a native model
 * "belongs to" a foreign model.
 * 
 * @category Solar
 * 
 * @package Solar_Sql_Model
 * 
 * @author Paul M. Jones <pmjones@solarphp.com>
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 * @version $Id$
 * 
 */
class Solar_Sql_Model_Related_BelongsTo extends Solar_Sql_Model_Related
{
    /**
     * 
     * When the native model is doing a select and an eager-join is requested
     * for this relation, this method modifies the select to add the eager
     * join.
     * 
     * Automatically adds the foreign columns to the select.
     * 
     * @param Solar_Sql_Select $select The SELECT to be modified.
     * 
     * @return void The SELECT is modified in place.
     * 
     */
    public function modSelectEager($select)
    {
        // build column names as "name__col" so that we can extract the
        // the related data later.
        $cols = array();
        foreach ($this->cols as $col) {
            $cols[] = "$col AS {$this->name}__$col";
        }
        
        $this->_modSelectEager($select, $cols);
    }
    
    /**
     * 
     * Sets the relationship type.
     * 
     * @return void
     * 
     */
    protected function _setType()
    {
        $this->type = 'belongs_to';
    }
    
    /**
     * 
     * Corrects the foreign_key value in the options; uses the foreign-model
     * table name as singular.
     * 
     * @param array &$opts The user-defined relationship options.
     * 
     * @return void
     * 
     */
    protected function _fixForeignKey(&$opts)
    {
        $prefix = $this->_inflect->toSingular(
            $this->_foreign_model->table_name
        );
        
        $column = $this->_foreign_model->primary_col;
        
        $opts['foreign_key'] = "{$prefix}_{$column}";
    }
    
    /**
     * 
     * Fixes the related column names in the user-defined options **in place**.
     * 
     * The foreign key is stored in the **native** model.
     * 
     * @param array $opts The user-defined relationship options.
     * 
     * @return void
     * 
     */
    protected function _fixRelatedCol(&$opts)
    {
        $opts['native_col'] = $opts['foreign_key'];
    }
    
    /**
     * 
     * A support method for _fixRelated() to handle belongs-to relationships.
     * 
     * @param array &$opts The relationship options; these are modified in-
     * place.
     * 
     * @param StdClass $foreign The catalog entry for the foreign model.
     * 
     * @return void
     * 
     */
    protected function _setRelated($opts)
    {
        // the foreign column
        if (empty($opts['foreign_col'])) {
            // named by foreign primary key
            $this->foreign_col = $this->_foreign_model->primary_col;
        } else {
            $this->foreign_col = $opts['foreign_col'];
        }
        
        // the native column
        if (empty($opts['native_col'])) {
            // named by foreign table's suggested foreign_col name
            $this->native_col = $this->_foreign_model->foreign_col;
        } else {
            $this->native_col = $opts['native_col'];
        }
        
        // the fetch type
        if (empty($opts['fetch'])) {
            $this->fetch = 'one';
        } else {
            $this->fetch = $opts['fetch'];
        }
    }
}