<?php
namespace Epoch;

abstract class Editable extends \Epoch\PermissionableRecord
{
    //Require these for every \Epoch\Editable class.
    public $id;
    
    function __construct($options = array())
    {
        //Check to see if we are editing this bro.
        if (isset($options['model']) && get_called_class() != $options['model']) {
            //We are not viewing this model, return.
            return;
        }
        
        //An Id was not passed, so we are just making a new one.
        if (!isset($options['id'])) {
            return;
        }
        
        if (!$class = $this->getByID($options['id'])) {
            throw new Exception("Could not find that", 400);
        }
        
        $this->synchronizeWithArray($class->toArray());
        
        if (!\Epoch\ACL::isAllowed(\Epoch\Controller::getAccount(), $this, 'view')) {
            throw new Exception("You do not have permission to view this object", 401);
        }
        
        if (isset($options['model']) && substr($options['model'], -5) != '_Edit') {
            //We are not viewing the Edit model for this class, return.
            return;
        }
        
        //We are editing... require login.
        \Epoch\Controller::requireLogin();
        
        if (!\Epoch\ACL::isAllowed(\Epoch\Controller::getAccount(), $this, 'edit')) {
            throw new Exception("You do not have permission to edit this.", 401);
        }
    }
    
    function handlePost($post = array())
    {
        //check if the id was changed via post.  This is a big no no.
        if (isset($this->id) && !empty($this->id) && ($this->id != $post['id'])) {
            throw new Exception("Id was changed in POST, record not saved.", 400);
        }
        
        if (!$this->canEdit(\Epoch\Controller::getAccount())) {
            throw new Exception("You do not have permission to edit this!", 401);
        }
        
        $this->synchronizeWithArray($post);
        
        //set the owner if not set.
        if (empty($this->owner_id)) {
            $this->owner_id = \Epoch\Controller::getAccount()->id;
        }
        
        //set the creator if not set.
        if (empty($this->creator_id)) {
            $this->creator_id = \Epoch\Controller::getAccount()->id;
        }
        
        if (empty($this->date_created)) {
            $this->date_created = time();
        }
        
        $this->date_edited = time();
        
        $saveType = 'create';
        if (isset($this->id) && !empty($this->id)) {
            $saveType = 'save';
        }
        
        if (!$this->save()) {
            throw new Exception("There was an error saving this.", 500);
        }
        
        //We have now finished the POST handling. call postPOST for final steps.
        $this->postPost($post);
    }
    
    function postPOST($post = array())
    {
        $redirectURL = \Epoch\Controller::$url.'success?class='.$post['_class'].'&saveType=' . $saveType;
        
        //check if a continue url was passed.
        if (isset($options['continueURL'])) {
            $redirectURL .= "&continueURL=";
            
            switch($options['continueURL']) {
                case "edit":
                    $redirectURL .= $this->getURL() . "/edit";
                    break;
                case "view":
                    $redirectURL .= $this->getURL();
                    break;
                default:
                    $redirectURL .= $options['onCreate']['continueURL'];
                    break;
            }
            
        }
        
        \Epoch\Controller::redirect($redirectURL);
    }
    
    function handleDelete() {
        if (isset($_POST['action']) && $_POST['action'] == 'delete') {
            if (!$this->canDelete()) {
                throw new Exception("You do not have permission to delete this.", 401);
            }
            
            $this->delete();
            
            \Epoch\Controller::redirect(\Epoch\Controller::$url . "success?for=".$this->getTable()."_delete");
        }
    }
    
    abstract public static function getName();
}