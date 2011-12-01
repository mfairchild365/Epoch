<?php
namespace Epoch;

class OutputController extends \Savvy
{
    function __construct($options = array())
    {
        parent::__construct();
        $this->setTemplatePath(array(dirname(dirname(dirname(__FILE__))).'/www/templates/Epoch', dirname(dirname(dirname(__FILE__))).'/www/templates/default'));
    }
    
    public function renderObject($object, $template = null)
    {
        return parent::renderObject($object, $template);

    }
    
    /**
     * 
     * @param timestamp $expires timestamp
     * 
     * @return void
     */
    function sendCORSHeaders($expires = null)
    {
        // Specify domains from which requests are allowed
        header('Access-Control-Allow-Origin: *');

        // Specify which request methods are allowed
        header('Access-Control-Allow-Methods: GET, OPTIONS');

        // Additional headers which may be sent along with the CORS request
        // The X-Requested-With header allows jQuery requests to go through

        header('Access-Control-Allow-Headers: X-Requested-With');

        // Set the ages for the access-control header to 20 days to improve speed/caching.
        header('Access-Control-Max-Age: 1728000');

        if (isset($expires)) {
            // Set expires header for 24 hours to improve speed caching.
            header('Expires: '.date('r', $expires));
        }
    }
}