<!-- BEGIN: FILTER_FORM -->
<div class="p-3 mb-4 rounded-2 border border-warning-subtle">
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
				<div class="w-100">
					<div class="d-flex justify-content-between mb-2">
						<span>До: <span id="value_{FILTER_PARAM_NAME}">{FILTER_PARAM_VALUE_MAX}</span></span>
					</div>
					<input type="range" 
					name="filter_{FILTER_PARAM_NAME}" 
					id="range_{FILTER_PARAM_NAME}" 
					min="0" 
					max="{FILTER_PARAM_MAX}" 
					value="{FILTER_PARAM_VALUE_MAX}" 
					class="form-range" 
					oninput="updateRangeValue('{FILTER_PARAM_NAME}', this.value)">
				</div>
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
				<input type="submit" name="search" class="btn btn-primary w-100" value="{PHP.L.mstorefilter_apply}" />
			</div>
			<div class="col-md-6 col-12 mb-2">
				<a class="btn btn-outline-danger w-100" href="{FILTER_RESET_URL}">{PHP.L.mstorefilter_reset}</a>
			</div>
		</div>
	</form>
</div>
<script>
	function updateRangeValue(paramName, value) {
		const display = document.getElementById(`value_${paramName}`);
		const rangeInput = document.getElementById(`range_${paramName}`);
		display.textContent = value;
		
		// Вычисляем процент текущего значения относительно максимума
		const max = parseFloat(rangeInput.max);
		const currentValue = parseFloat(value);
		const percentage = (currentValue / max) * 100;
		
		// Устанавливаем процент для CSS-градиента
		rangeInput.style.setProperty('--value-percentage', `${percentage}%`);
	}
</script>
<style>
	/* Стили для ползунка */
	.range-slider {
    display: flex;
    align-items: center;
    gap: 10px;
	}
	
	.form-range {
    width: 100%;
    -webkit-appearance: none;
    appearance: none;
    height: 6px;
    background: #ddd; /* Серый цвет для непройденной части дорожки */
    border-radius: 5px;
    outline: none;
	}
	
	/* Стили для thumb (бегунка) */
	.form-range::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 20px;
    height: 20px;
    background: #007bff; /* Синий бегунок (Bootstrap) */
    border-radius: 50%;
    cursor: pointer;
	}
	
	.form-range::-moz-range-thumb {
    width: 20px;
    height: 20px;
    background: #007bff;
    border-radius: 50%;
    cursor: pointer;
	}
	
	/* Стили для дорожки от 0 до текущего значения */
	.form-range::-webkit-slider-runnable-track {
    background: linear-gradient(to right, #ffc107 calc(var(--value-percentage, 0%)), #ddd calc(var(--value-percentage, 0%)), #ddd 100%);
	}
	
	.form-range::-moz-range-progress {
    background: #ffc107; /* Желтый цвет для пройденной части в Firefox */
    height: 6px;
    border-radius: 5px;
	}
	
	.form-range::-moz-range-track {
    background: #ddd; /* Серый цвет для непройденной части в Firefox */
    height: 6px;
    border-radius: 5px;
	}
</style>
<!-- END: FILTER_FORM -->
