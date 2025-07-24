<!-- BEGIN: FILTER_FORM -->
<div class="p-3 mb-4 rounded-2 bg-white border border-primary-subtle">
<form action="{SEARCH_ACTION_URL}" method="get" class="mb-4">
    <input type="hidden" name="c" value="{PHP.c}" />
    <input type="hidden" name="e" value="mstore" />
    <input type="hidden" name="l" value="{PHP.lang}" />
    <input type="hidden" name="saveFilter" value="1" />

    <!-- BEGIN: ERROR -->
    <div class="alert alert-warning">{FILTER_ERROR}</div>
    <!-- END: ERROR -->

    <!-- BEGIN: FILTER_PARAM -->
    <div class="mb-3">
        <label class="form-label"><strong>{FILTER_PARAM_TITLE}</strong></label>
        <div class="text-muted small">{FILTER_PARAM_VALUES_DEBUG}</div>

        <!-- BEGIN: ERROR -->
        <div class="alert alert-warning">{FILTER_PARAM_ERROR}</div>
        <!-- END: ERROR -->

        <!-- IF {FILTER_PARAM_TYPE} == "range" -->
        <div class="input-group">
            <input type="text" name="filter_{FILTER_PARAM_NAME}" value="{FILTER_PARAM_VALUE_MIN},{FILTER_PARAM_VALUE_MAX}" class="form-control" placeholder="от {FILTER_PARAM_MIN} до {FILTER_PARAM_MAX}" pattern="\d+,\d+">
        </div>
        <!-- ENDIF -->

        <!-- IF {FILTER_PARAM_TYPE} == "select" -->
        <select name="filter_{FILTER_PARAM_NAME}" class="form-select">
            <option value="">-- Выберите --</option>
            <!-- BEGIN: SELECT_LIST -->
            <option value="{FILTER_PARAM_OPTION_VALUE}" {FILTER_PARAM_OPTION_SELECTED}>{FILTER_PARAM_OPTION_VALUE}</option>
            <!-- END: SELECT_LIST -->
        </select>
        <!-- ENDIF -->

        <!-- IF {FILTER_PARAM_TYPE} == "checkbox" -->
        <div class="form-check-list">
            <!-- BEGIN: CHECKBOX_LIST -->
            <div class="form-check">
                <input type="checkbox" name="filter_{FILTER_PARAM_NAME}[]" value="{FILTER_PARAM_OPTION_VALUE}" class="form-check-input" {FILTER_PARAM_CHECKED}>
                <label class="form-check-label">{FILTER_PARAM_OPTION_VALUE}</label>
            </div>
            <!-- END: CHECKBOX_LIST -->
        </div>
        <!-- ENDIF -->

        <!-- IF {FILTER_PARAM_TYPE} == "radio" -->
        <div class="form-check-list">
            <!-- BEGIN: RADIO_LIST -->
            <div class="form-check">
                <input type="radio" name="filter_{FILTER_PARAM_NAME}" value="{FILTER_PARAM_OPTION_VALUE}" class="form-check-input" {FILTER_PARAM_CHECKED}>
                <label class="form-check-label">{FILTER_PARAM_OPTION_VALUE}</label>
            </div>
            <!-- END: RADIO_LIST -->
        </div>
        <!-- ENDIF -->
    </div>
    <!-- END: FILTER_PARAM -->

    <div class="row">
        <div class="col-md-6 col-12 mb-2">
            <input type="submit" name="search" class="btn btn-primary w-100" value="{PHP.L.CleanCot_StartSearch}" />
        </div>
        <div class="col-md-6 col-12 mb-2">
            <a class="btn btn-outline-danger w-100" href="{PHP|cot_url('mstore', ['c' => $c])}">{PHP.L.CleanCot_ReserFilter}</a>
        </div>
    </div>
</form>
</div>
<!-- END: FILTER_FORM -->
