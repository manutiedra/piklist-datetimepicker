
# piklist-datetimepicker

## About:

piklist-datetimepicker is a wordpress plugin that uses [datetimepicker](http://trentrichardson.com/examples/timepicker/) to add datetime picker support.

## How to use

To use the datatable field, you should set the ```type``` option to ```datetimepicker```.

An example of use is:
```php
piklist('field', array(
  'type' => 'datetimepicker',
  'field' => 'action_date',
  'label' => __('Action Date', 'my-plugin'),
  'required' => true,
  'value' => date_i18n('d/m/Y H:i'),
  'options' => array(
    'first-day' => 1,
    'date-format' => 'dd/mm/yy',
    'time-format' => 'HH:mm',
    'control-type' => 'select',
    'step-minute' => 5,
  ),
));
```

As the rest of my plugins, it is tested using the piklist dev branch.

## History:
* 25/01/2018: v0.0.1 released
