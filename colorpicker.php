<?php
defined('MOODLE_INTERNAL') || die();

require_once "HTML/QuickForm/text.php";
require_once $CFG->libdir . "/form/templatable_form_element.php";

/**
 * Form element for picking color.
 * Based on the core MoodleQuickForm_text class.
 *
 * @copyright 2017 Hung Tran
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class MoodleQuickForm_colorpicker extends HTML_QuickForm_text implements templatable {
    use templatable_form_element;

    /** @var string html for help button, if empty then no help */
    var $_helpbutton='';

    /** @var bool if true label will be hidden */
    var $_hiddenLabel=false;

    /** @var bool Whether to force the display of this element to flow LTR. */
    protected $forceltr = false;

    /**
     * constructor
     *
     * @param string $elementName (optional) name of the text field
     * @param string $elementLabel (optional) text field label
     * @param string $attributes (optional) Either a typical HTML attribute string or an associative array
     */
    public function __construct($elementName=null, $elementLabel=null, $attributes=null) {
        parent::__construct($elementName, $elementLabel, $attributes);
    }

    /**
     * Sets label to be hidden
     *
     * @param bool $hiddenLabel sets if label should be hidden
     */
    function setHiddenLabel($hiddenLabel){
        $this->_hiddenLabel = $hiddenLabel;
    }

    /**
     * Freeze the element so that only its value is returned and set persistantfreeze to false
     *
     * @since     Moodle 2.4
     * @access    public
     * @return    void
     */
    function freeze() {
        $this->_flagFrozen = true;
        // No hidden element is needed refer MDL-30845
        $this->setPersistantFreeze(false);
    }

    /**
     * Returns the html to be used when the element is frozen
     *
     * @since     Moodle 2.4
     * @return    string Frozen html
     */
    function getFrozenHtml(){
        $attributes = array('readonly' => 'readonly');
        $this->updateAttributes($attributes);

        return $this->_getTabs() . '<input' . $this->_getAttrString($this->_attributes) . ' />' . $this->_getPersistantData();
    }

    /**
     * Returns HTML for this form element.
     *
     * @return string
     */
    public function toHtml() {
        global $PAGE, $OUTPUT;

        // Add the class at the last minute.
        if ($this->get_force_ltr()) {
            if (!isset($this->_attributes['class'])) {
                $this->_attributes['class'] = 'text-ltr';
            } else {
                $this->_attributes['class'] .= ' text-ltr';
            }
        }

        $this->_generateId();

        if ($this->_flagFrozen) {
            return $this->getFrozenHtml();
        }

        $icon = new pix_icon('i/loading', get_string('loading', 'admin'), 'moodle', ['class' => 'loadingicon']);
        $context = (object) [
            'value' => '#FFFFFF',
            'icon' => $icon->export_for_template($OUTPUT),
            'forceltr' => $this->get_force_ltr()
        ];

        $fieldId = '';

        if (count($this->_attributes) > 0) {
            foreach ($this->_attributes as $k => $v) {
                $context->$k = $v;
            }

            if (isset($this->_attributes['id']))
                $fieldId = $this->_attributes['id'];
        }

        $html = $this->_getTabs() . $OUTPUT->render_from_template('core_admin/setting_configcolourpicker', $context);
        $PAGE->requires->js_init_call('M.util.init_colour_picker', array($fieldId));

        if ($this->_hiddenLabel){
            return '<label class="accesshide" for="'.$this->getAttribute('id').'" >'.
                        $this->getLabel() . '</label>' . $html;
        } else {
             return $html;
        }
    }

    /**
     * get html for help button
     *
     * @return string html for help button
     */
    function getHelpButton(){
        return $this->_helpbutton;
    }

    /**
     * Get force LTR option.
     *
     * @return bool
     */
    public function get_force_ltr() {
        return $this->forceltr;
    }

    /**
     * Force the field to flow left-to-right.
     *
     * This is useful for fields such as URLs, passwords, settings, etc...
     *
     * @param bool $value The value to set the option to.
     */
    public function set_force_ltr($value) {
        $this->forceltr = (bool) $value;
    }
}
