<?php
/**
 * @license	    GNU General Public License version 2 or later; see  LICENSE.txt
 * @author      Eric Fernance
 *
 * @copyright   Eric Fernance
 */

defined("BASEPATH") or die("Invalid Direct Access");

class Quick_trigger{

    public function __construct()
    {
        $this->config->load('quick_trigger');

        $this->plugins_path = $this->config->item('quick_trigger_plugins');

        if (!$this->plugins_path) {
            echo 'Could not find plugins path -- have you defined this in your config';
        }
    }

    public function trigger($group='', $event='', $data=array()){
        $plugins = scandir(APPPATH.'/'.$this->plugins_path.'/'.$group);

        if ($plugins) {
            foreach ($plugins as $plugin) {
                if ($plugin !== '.' && $plugin !== '..') {
                    require_once(APPPATH.'/'.$this->plugins_path.'/'.$group.'/'.$plugin);
                    $fqn = str_replace('.php','',str_replace(' ', '', ucwords(str_replace('_', ' ', $plugin))));
                    $class = new $fqn();
                    if (method_exists($class,$event)) {
                        call_user_func(array($class, $event),$data);
                    }
                }
            }
        }
    }



    /**
     * __get
     *
     * Enables the use of CI super-global without having to define an extra variable.
     *
     * I can't remember where I first saw this, so thank you if you are the original author. -Militis
     *
     * Taken from IonAuth - so thank you to IonAuth as well
     *
     * @access	public
     * @param	$var
     * @return	mixed
     */
    public function __get($var)
    {
        return get_instance()->$var;
    }
}
