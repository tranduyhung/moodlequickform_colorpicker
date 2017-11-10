# MoodleQuickForm_colorpicker: Color picker form field for Moodle

A quick custom form field for selecting color, it uses Moodle's JavaScript colour picker which is used in various places in Moodle Admin.

## Usage

* Download or clone the repository to where you want to store the files.
* Require colorpicker.php in your moodleform class's file.
* Register new element type.
* Add a new color picker element.

## Example moodleform class

Assume your moodleform class is in `local/my_plugin/forms/my_form.php`, "colorpicker" folder is stored in the same folder, so we have:

```
local/my_plugin/forms/my_form.php
local/my_plugin/forms/colorpicker/colorpicker.php

```
<?php
global $CFG;

require_once $CFG->libdir . "/formslib.php";
require_once __DIR__ . '/colorpicker/colorpicker.php';
MoodleQuickForm::registerElementType('colorpicker', __DIR__ . '/colorpicker/colorpicker.php', 'MoodleQuickForm_colorpicker');

class my_form extends moodleform {
	public function definition() {
		$mform = $this->_form;

		$mform->addElement('colorpicker', 'my_color', get_string('my_color', 'local_my_plugin'));
		$mform->setType('my_color', PARAM_TEXT);

		$this->add_action_buttons();
	}
}
```




