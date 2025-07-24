# cot-mstorefilter
Plugin "Mstore Filter" Dynamic filter parameters for Mstore module for Cotonti v.0.9.26 


Detailed instructions for filling in filter parameter fields: <ul>
  <li>
    <b>Parameter Code</b> — a unique system identifier for the parameter. Use only Latin letters, numbers, and underscores without spaces. For example: <i>power</i>, <i>battery_capacity</i>. This code will be used in the database and code, so there must be no duplicates.
  </li>
  <li>
    <b>Parameter Name</b> — a readable name that users will see in the site interface. For example: <i>Power</i>, <i>Battery Capacity</i>.
  </li>
  <li>
    <b>Parameter Type</b> — select the type of value: <ul>
      <li>
        <i>Range</i> — for numeric parameters with minimum and maximum values, e.g., price, weight;
      </li>
      <li>
        <i>Dropdown List</i> — for selecting one option from a list of fixed values;
      </li>
      <li>
        <i>Checkboxes</i> — for selecting one or more options from a list.
      </li>
    </ul>
  </li>
  <li>
    <b>Parameter Values (JSON)</b> — specify the allowed parameter values in JSON format: <ul>
      <li>For the <i>Range</i> type, provide an object with two properties <code>min</code> and <code>max</code>, e.g.: <code>{"min":0,"max":100}</code>. Values must be numbers, and <code>min</code> must be less than or equal to <code>max</code>. </li>
      <li>For <i>Dropdown List</i> and <i>Checkboxes</i> types, provide an array of strings with possible options, e.g.: <code>["Red","Green","Blue"]</code>. Each array element is a separate value. </li>
    </ul>
    <p>
      <b>Important:</b> JSON must be strictly valid:
    <ul>
      <li>Use double quotes for keys and string values;</li>
      <li>Do not add extra commas after the last element;</li>
      <li>The structure must exactly match the examples above.</li>
    </ul> To verify JSON correctness, use online validators, such as <a href="https://jsonlint.com" target="_blank" rel="noopener noreferrer">jsonlint.com</a>. Invalid JSON will cause errors during saving or filter operation. </p>
  </li>
  <li>
    <b>Active</b> — a toggle that enables or disables the filter parameter on the site. If the parameter is inactive, it will not be displayed to users.
  </li>
</ul>

Add to mstore.tpl
```
<!-- IF {PHP|cot_plugin_active('mstorefilter')} -->
<h3>{PHP.L.mstorefilter_paramsItem}</h3>
<dl class="row">
    <!-- BEGIN: MSTORE_FILTER_PARAMS -->
    <dt class="col-sm-4">{PARAM_TITLE}</dt>
    <dd class="col-sm-8">{PARAM_VALUE}</dd>
    <!-- END: MSTORE_FILTER_PARAMS -->
</dl>
<!-- ENDIF -->
```

