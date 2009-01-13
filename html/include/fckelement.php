<?php

require_once("HTML/QuickForm/element.php");

class HTML_QuickForm_fckeditor extends HTML_QuickForm_element
{
/**
* Field value
* @var       string
* @since     1.0
* @access    private
*/
    var $_value = null;

/**
* Class constructor
*
* @param     string    Input field name attribute
* @param     mixed     Label(s) for a field
* @param     mixed     Either a typical HTML attribute string or an associative array
* @since     1.0
* @access    public
* @return    void
*/
    function  HTML_QuickForm_fckeditor($elementName=null, $elementLabel=null, $attributes=null)
    {
        HTML_QuickForm_element::HTML_QuickForm_element($elementName, $elementLabel, $attributes);
        $this->_persistantFreeze = true;
        $this->_type = 'fckeditor';
    } //end constructor

/**
* Sets the input field name
*
* @param     string    $name   Input field name attribute
* @since     1.0
* @access    public
* @return    void
*/
    function setName($name)
    {
        $this->updateAttributes(array('name'=>$name));
    } //end func setName

/**
* Returns the element name
*
* @since     1.0
* @access    public
* @return    string
*/
    function getName()
    {
        return $this->getAttribute('name');
    } //end func getName

/**
* Sets value for textarea element
*
* @param     string    $value  Value for textarea element
* @since     1.0
* @access    public
* @return    void
*/
    function setValue($value)
    {
        $this->_value = $value;
    } //end func setValue


/**
* Returns the value of the form element
*
* @since     1.0
* @access    public
* @return    string
*/
    function getValue()
    {
        return $this->_value;
    } // end func getValue


/**
* Returns the textarea element in HTML
*
* @since     1.0
* @access    public
* @return    string
*/
    function toHtml()
    {
       global $web_root;
       
        if ($this->_flagFrozen)
        {
            return $this->getFrozenHtml();
        }
        else
        {
            $fck = new FCKeditor($this->_attributes['name']);
            
            if(isset($this->_attributes['basepath'])) {
                $fck->BasePath=$this->_attributes['basepath'];
            }
            else
            {
            	$fck->BasePath= $web_root.'include/FCKeditor/';
            }
            
            if(isset($this->_attributes['skin']))  {
                $fck->Config['SkinPath'] = $fck->BasePath."editor/skins/".$this->_attributes['skin']."/";
            }
            else
            {
            	// Default to silver
            	$fck->Config['SkinPath'] = $fck->BasePath."editor/skins/silver/";
            }
            
            if(isset($this->_attributes['toolbarset']))
            {
                $fck->ToolbarSet=$this->_attributes['toolbarset'];
            }
            else
            {
            	$fck->ToolbarSet= "Content";
            }
            
            if ($this->_attributes['height'])
            {
                $fck->Height = $this->_attributes['height'];    
            }
            else
            {
                $fck->Height = '300';
            }
            
            if ($this->_attributes['width'])
            {
                $fck->Width = $this->_attributes['width'];    
            }
            else
            {
                $fck->Width = '500';
            }
            
            $fck->Value=$this->_value;
            $output = $fck->CreateHtml();



            return $this->_getTabs() .$output;
            /*'<textarea' . $this->_getAttrString($this->_attributes)
            . '>' .
            // because we wrap the form later we don't want the text
            indented
            preg_replace("/(\r\n|\n|\r)/", '
            ',
            htmlspecialchars($this->_value)) .
            '</textarea>';*/
        }
    } //end func toHtml


/**
* Returns the value of field without HTML tags (in this case, value is changed to a mask)
*
* @since     1.0
* @access    public
* @return    string
*/
    function getFrozenHtml()
    {
        $value = htmlspecialchars($this->getValue());
        echo $value;
        if ($this->getAttribute('wrap') == 'off')
        {
            $html = $this->_getTabs() . '<pre>' . $value."</pre>\n";
        }
        else
        {
            $html = nl2br($value)."\n";
        }
        return $html . $this->_getPersistantData();
    } //end func getFrozenHtml

}
?>
