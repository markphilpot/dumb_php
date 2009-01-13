<?php


/*
 * Created on May 5, 2006
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class rule
{
   public function validate($value)
   {
      return true;
   }
   
   public function get_name()
   {
      return $_name;
   }
   
   private $_name;
}

class required extends rule
{
   public function required($name)
   {
      $this->_name = $name;
   }
   
   public function validate($value)
   {
      if ((string) $value == '')
         return false;
      else
         return true;
   }
}

class email extends rule
{
   private $regex = '/^((\"[^\"\f\n\r\t\v\b]+\")|([\w\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+(\.[\w\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+)*))@((\[(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))\])|(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))|((([A-Za-z0-9\-])+\.)+[A-Za-z\-]+))$/';

   public function email($name)
   {
      $this->_name = $name;
   }

   public function validate($email)
   {
      if (preg_match($this->regex, $email))
         return true;
      else
         return false;
   }
}

class regex extends rule
{
   private $_data = array(
                    'lettersonly'   => '/^[a-zA-Z]+$/',
                    'alphanumeric'  => '/^[a-zA-Z0-9]+$/',
                    'numeric'       => '/(^-?\d\d*\.\d*$)|(^-?\d\d*$)|(^-?\.\d\d*$)/',
                    'nopunctuation' => '/^[^().\/\*\^\?#!@$%+=,\"\'><~\[\]{}]+$/',
                    'nonzero'       => '/^-?[1-9][0-9]*/'
                    );

   public function regex($name)
   {
      $this->_name = $name;
   }

    public function validate($value, $regex)
    {
        if (isset($this->_data[$regex]))
        {
            if (!preg_match($this->_data[$regex], $value))
            {
                return false;
            }
        }
        else
        {
            if (!preg_match($regex, $value))
            {
                return false;
            }
        }
        
        return true;
    } // end func validate

    public function addData($name, $pattern)
    {
        $this->_data[$name] = $pattern;
    } // end func addData
}

class form_validator
{

   public function form_validator($form)
   {
      $this->_form = $form;
      $this->_results = array();
   }

   public function add_rule($variable, $message, $type, $regex=null)
   {
      $type = strtolower($type);
      $rule = null;
      
      if($type == 'required')
      {
         $rule = new required($variable);
         if(!$rule->validate($this->_form[$variable]))
         {
            array_push($this->_results, $message);
         }
      }
      elseif($type == 'email')
      {
         $rule = new email($variable);
         if(!$rule->validate($this->_form[$variable]))
         {
            array_push($this->_results, $message);
         }
      }
      elseif($type == 'regex' && $regex != null)
      {
         $rule = new regex($variable);
         if(!$rule->validate($this->_form[$variable], $regex))
         {
            array_push($this->_results, $message);
         }
      }
   }
   
   public function get_results()
   {
      return $this->_results;
   }
   
   private $_results;
   private $_form;
}
?>
