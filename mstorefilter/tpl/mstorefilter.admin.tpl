<!-- BEGIN: MAIN -->
<div class="container-fluid">
    <h2>{PHP.L.mstorefilter_admin_title}</h2>

    <!-- BEGIN: ADD_FORM -->
    <div class="p-3 mb-4 bg-primary-subtle text-primary-emphasis">
        <h3>{PHP.L.mstorefilter_add_param}</h3>
        <form action="{FORM_ACTION}" method="POST">
            <div class="mb-2">{FORM_FIELDS}</div>
            <button type="submit" class="btn btn-success">{PHP.L.Add}</button>
        </form>
    </div>
    <!-- END: ADD_FORM -->

    <!-- BEGIN: EDIT_FORM -->
    <div class="p-3 mb-4 bg-warning-subtle text-warning-emphasis">
        <h3>{PHP.L.mstorefilter_edit_param} #{FORM_PARAM_ID}</h3>
		<input type="hidden" name="id" value="{FORM_PARAM_ID}" />
        <form action="{FORM_ACTION}" method="POST">
            <div class="mb-2">{FORM_FIELDS}</div>
            <button type="submit" class="btn btn-primary">{PHP.L.Save}</button>
            <a href="{CANCEL_URL}" class="btn btn-secondary">{PHP.L.Cancel}</a>
        </form>
    </div>
    <!-- END: EDIT_FORM -->

    <h3>{PHP.L.mstorefilter_existing_params}</h3>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>{PHP.L.mstorefilter_id}</th>
                <th>{PHP.L.mstorefilter_param_name}</th>
                <th>{PHP.L.mstorefilter_param_title}</th>
                <th>{PHP.L.mstorefilter_param_type}</th>
                <th>{PHP.L.mstorefilter_param_values}</th>
                <th>{PHP.L.mstorefilter_param_active}</th>
                <th>{PHP.L.mstorefilter_actions}</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: PARAM_ROW -->
            <tr>
                <td>{PARAM_ID}</td>
                <td>{PARAM_NAME}</td>
                <td>{PARAM_TITLE}</td>
                <td>{PARAM_TYPE}</td>
                <td>{PARAM_VALUES}</td>
                <td>{PARAM_ACTIVE}</td>
                <td>
                    <a href="{PARAM_EDIT_URL}" class="btn btn-sm btn-primary">{PHP.L.Edit}</a>
                    <a href="{PARAM_DELETE_URL}" class="btn btn-sm btn-danger" onclick="return confirm('{PHP.L.mstorefilter_confirm_delete}')">{PHP.L.Delete}</a>
                </td>
            </tr>
            <!-- END: PARAM_ROW -->
        </tbody>
    </table>
</div>
<!-- END: MAIN -->
