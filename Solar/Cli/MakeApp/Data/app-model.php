/**
 * 
 * Generic BREAD application for {:model_class}.
 * 
 */
class {:class} extends {:extends} {
    
    /**
     * 
     * The default action when no action is specified.
     * 
     * @var string
     * 
     */
    protected $_action_default = 'browse';
    
    /**
     * 
     * A list of records.
     * 
     * @var {:model_class}_Collection
     * 
     */
    public $list;
    
    /**
     * 
     * A single record.
     * 
     * @var {:model_class}_Record
     * 
     */
    public $item;
    
    /**
     * 
     * A form for editing a single record.
     * 
     * @var Solar_Form
     * 
     */
    public $form;
    
    /**
     * 
     * Use only these columns in the form, and when loading record data.
     * 
     * When empty, uses all columns.
     * 
     * @var array
     * 
     */
    protected $_cols = array();
    
    /**
     * 
     * An instance of the model class.
     * 
     * @var {:model_class}
     * 
     */
    protected $_{:model_var};
    
    /**
     * 
     * Pre-run logic to load a model instance.
     * 
     * @return void
     * 
     */
    protected function _preRun()
    {
        // parent logic
        parent::_preRun();
        
        // load a model instance
        $this->_{:model_var} = Solar::factory('{:model_class}');
    }
    
    /**
     * 
     * Browse records by page.
     * 
     * @return void
     * 
     */
    public function actionBrowse()
    {
        // get the collection
        $this->list = $this->_{:model_var}->fetchAll(array(
            'page'        => $this->_query('page', 1),
            'paging'      => $this->_query('paging', 10),
            'count_pages' => true,
        ));
    }
    
    /**
     * 
     * View one record by ID.
     * 
     * @param int $id The record ID to view.
     * 
     * @return void
     * 
     */
    public function actionRead($id = null)
    {
        // need an id
        if (! $id) {
            return $this->_error('ERR_NO_ID_SPECIFIED');
        }
                
        // get the record
        $this->item = $this->_{:model_var}->fetch($id);
        
        // does the record exist?
        if (! $this->item) {
            return $this->_error('ERR_NO_SUCH_ITEM');
        }
    }
    
    /**
     * 
     * Edit a record by ID.
     * 
     * @param int $id The record id.
     * 
     * @return void
     * 
     */
    public function actionEdit($id = null)
    {
        // process: cancel
        if ($this->_isProcess('cancel')) {
            // forward back to reading
            return $this->_redirect("/{$this->_controller}/read/$id");
        }
        
        // process: delete
        if ($this->_isProcess('delete')) {
            // forward to the delete method for confirmation
            return $this->_redirect("/{$this->_controller}/delete/$id");
        }
        
        // need an id
        if (! $id) {
            return $this->_error('ERR_NO_ID_SPECIFIED');
        }
        
        // get the record
        $this->item = $this->_{:model_var}->fetch($id);
        
        // does the record exist?
        if (! $this->item) {
            return $this->_error('ERR_NO_SUCH_ITEM');
        }
        
        // process: save
        if ($this->_isProcess('save')) {
            
            // what array name should we look for in the POST data?
            $name = $this->_{:model_var}->model_name;
            
            // get the POST data using the array name
            $data = $this->_request->post($name, array());
            
            // load the data cols to the record
            $this->item->load($data, $this->_cols);
            
            // attempt the save.  this will update the record and
            // set invalidation messages if it didn't work.
            $this->item->save();
        }
        
        // get the form-building hints for the cols
        $this->form = $this->item->form($this->_cols);
        
        // catch flash indicating a successful add
        if ($this->_session->getFlash('success_added')) {
            $this->form->setStatus(true);
            $this->form->feedback = $this->locale('SUCCESS_ADDED');
        }
    }
    
    /**
     * 
     * Add a new record.
     * 
     * @return void
     * 
     */
    public function actionAdd()
    {
        // process: cancel
        if ($this->_isProcess('cancel')) {
            // forward back to browse
            return $this->_redirect("/{$this->_controller}/browse");
        }
        
        // get a new record
        $this->item = $this->_{:model_var}->fetchNew();
        
        // process: save
        if ($this->_isProcess('save')) {
            
            // what array name should we look for in the POST data?
            $name = $this->_{:model_var}->model_name;
            
            // get the POST data using the array name
            $data = $this->_request->post($name, array());
            
            // load the data cols to the record
            $this->item->load($data, $this->_cols);
            
            // attempt the save.  this will update the record and
            // set invalidation messages if it didn't work.
            if ($this->item->save()) {
                // save a flash value for the next page
                $this->_session->setFlash('success_added', true);
                // redirect to editing.
                return $this->_redirectNoCache("/{$this->_controller}/edit/{$this->item->id}");
            }
        }
        
        // get the form-building hints for the cols
        $this->form = $this->item->form($this->_cols);
    }
    
    /**
     * 
     * Delete a record by ID; asks for confirmation before actually deleting.
     * 
     * @param int $id The record ID.
     * 
     * @return void
     * 
     */
    public function actionDelete($id = null)
    {
        // need an id
        if (! $id) {
            return $this->_error('ERR_NO_ID_SPECIFIED');
        }
        
        // get the record
        $this->item = $this->_{:model_var}->fetch($id);
        
        // does the record exist?
        if (! $this->item) {
            return $this->_error('ERR_NO_SUCH_ITEM');
        }
        
        // process: delete confirm
        if ($this->_isProcess('delete_confirm')) {
            // delete it
            $this->item->delete();
            // redirect to browse
            $this->_redirectNoCache("/{$this->_controller}");
        }
    }
}
