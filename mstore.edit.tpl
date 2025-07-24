<!-- BEGIN: MAIN -->
<div class="border-bottom border-secondary py-3 px-3">
  <nav aria-label="breadcrumb">
    <div class="ps-container-breadcrumb">
      <ol class="breadcrumb d-flex mb-0">{MSTOREEDIT_BREADCRUMBS}</ol>
    </div>
  </nav>
</div>


  <!-- IF !{PHP.usr_can_publish} -->
  <div class="mb-3 mt-3">
    <div class="alert alert-info" role="alert">{PHP.L.mstore_formhint}</div>
  </div>
  <!-- ENDIF -->
  <div class="row justify-content-center">
    <div class="col-12 col-md-10 mx-auto">
      <div class="card mt-4 mb-4">
        <div class="card-header">
          <h2 class="h5 mb-0">{MSTOREEDIT_PAGETITLE} #{MSTOREEDIT_FORM_ID}</h2>
        </div>
        <div class="card-body">
          {FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/warnings.tpl"}
          <form action="{MSTOREEDIT_FORM_SEND}" enctype="multipart/form-data" method="post" name="mstoreform" class="needs-validation" novalidate>
            <div class="row g-3">
              <div class="col-12">
                <label for="mstoreCat" class="form-label fw-semibold">{PHP.L.Category}</label>
                <div class="input-group has-validation">{MSTOREEDIT_FORM_CAT}</div>
              </div>
              <div class="col-12">
                <label for="mstoreTitle" class="form-label fw-semibold">{PHP.L.Title}</label>
                <div class="input-group has-validation">{MSTOREEDIT_FORM_TITLE}</div>
              </div>
              <div class="col-12">
                <label for="mstoreDesc" class="form-label fw-semibold">{PHP.L.Description}</label>
                <div class="input-group has-validation">{MSTOREEDIT_FORM_DESCRIPTION}</div>
              </div>
              <div class="col-12">
                <label for="mstoreAuthor" class="form-label fw-semibold">{PHP.L.Author}</label>
                <div class="input-group has-validation">{MSTOREEDIT_FORM_AUTHOR}</div>
              </div>
              <div class="col-12">
                <label for="mstoreDate" class="form-label fw-semibold">{PHP.L.Date}</label>
                <div>{MSTOREEDIT_FORM_DATE}</div>
                <small class="form-text text-muted mt-1">{MSTOREEDIT_FORM_DATENOW} {PHP.L.mstore_date_now}</small>
              </div>
              <div class="col-12">
                <label for="mstoreBegin" class="form-label fw-semibold">{PHP.L.Begin}</label>
                <div>{MSTOREEDIT_FORM_BEGIN}</div>
              </div>
              <div class="col-12">
                <label for="mstoreExpire" class="form-label fw-semibold">{PHP.L.Expire}</label>
                <div>{MSTOREEDIT_FORM_EXPIRE}</div>
              </div>
              <div class="col-12">
                <label for="mstoreStatus" class="form-label fw-semibold">{PHP.L.Status}</label>
                <div class="input-group has-validation">{MSTOREEDIT_FORM_LOCAL_STATUS}</div>
              </div>
              <div class="col-12">
                <label for="mstoreAlias" class="form-label fw-semibold">{PHP.L.Alias}</label>
                <div class="input-group has-validation">{MSTOREEDIT_FORM_ALIAS}</div>
              </div>
              <div class="col-12">
                <label for="mstoreKeywords" class="form-label fw-semibold">{PHP.L.mstore_metakeywords}</label>
                <div class="input-group has-validation">{MSTOREEDIT_FORM_KEYWORDS}</div>
              </div>
              <div class="col-12">
                <label for="mstoreMetaTitle" class="form-label fw-semibold">{PHP.L.mstore_metatitle}</label>
                <div class="input-group has-validation">{MSTOREEDIT_FORM_METATITLE}</div>
              </div>
              <div class="col-12">
                <label for="mstoreMetaDesc" class="form-label fw-semibold">{PHP.L.mstore_metadesc}</label>
                <div class="input-group has-validation">{MSTOREEDIT_FORM_METADESC}</div>
              </div>
              <!-- BEGIN: TAGS -->
              <div class="col-12">
                <label for="mstoreTags" class="form-label fw-semibold">{MSTOREEDIT_TOP_TAGS}</label>
                <div class="input-group has-validation">{MSTOREEDIT_FORM_TAGS}</div>
                <small class="form-text text-muted">{MSTOREEDIT_TOP_TAGS_HINT}</small>
              </div>
              <!-- END: TAGS -->
              <!-- BEGIN: ADMIN -->
              <div class="col-12">
                <label for="mstoreOwner" class="form-label fw-semibold">{PHP.L.Owner}</label>
                <div class="input-group has-validation">{MSTOREEDIT_FORM_OWNER_ID}</div>
              </div>
              <div class="col-12">
                <label for="mstoreHits" class="form-label fw-semibold">{PHP.L.Hits}</label>
                <div class="input-group has-validation">{MSTOREEDIT_FORM_HITS}</div>
              </div>
              <!-- END: ADMIN -->
              <div class="col-12">
                <label for="mstoreParser" class="form-label fw-semibold">{PHP.L.Parser}</label>
                <div class="input-group has-validation">{MSTOREEDIT_FORM_PARSER}</div>
              </div>
              <div class="col-12">
                <label for="mstoreText" class="form-label fw-semibold">{PHP.L.Text}</label>
                {MSTOREEDIT_FORM_TEXT}
              </div>
{MSTORE_FORM_FILTER_PARAMS}

              <!-- IF {MSTOREEDIT_FORM_LINK_MAIN_IMAGE} -->
              <div class="col-12">
                <label for="mstoreLinkMainImage" class="form-label fw-semibold">{MSTOREEDIT_FORM_LINK_MAIN_IMAGE_TITLE}</label>
                <div class="input-group has-validation">{MSTOREEDIT_FORM_LINK_MAIN_IMAGE}</div>
                <small class="form-text text-muted">{PHP.L.2wd_mstore_LinkMainImage_hint}</small>
              </div>
              <!-- ENDIF -->
              <!-- IF {PHP|cot_module_active('pfs')} -->
              <div class="col-12">
                <label for="mstoreText" class="form-label fw-semibold">{PHP.L.2wd_PFS}</label>
                <div class="mt-2">
                  <!-- IF {MSTOREEDIT_FORM_PFS} -->{MSTOREEDIT_FORM_PFS}
                  <!-- ENDIF -->
                  <!-- IF {MSTOREEDIT_FORM_SFS} -->
                  <span class="me-2">{PHP.cfg.separator}</span>{MSTOREEDIT_FORM_SFS}
                  <!-- ENDIF -->
                </div>
              </div>
              <div class="col-12">
                <label for="mstoreFile" class="form-label fw-semibold">{PHP.L.mstore_file}</label>
                <div class="input-group has-validation">{MSTOREEDIT_FORM_FILE}</div>
                <small class="form-text text-muted">{PHP.L.mstore_filehint}</small>
              </div>
              <div class="col-12">
                <label for="mstoreUrl" class="form-label fw-semibold">{PHP.L.URL}</label>
                <div class="input-group has-validation">{MSTOREEDIT_FORM_URL}</div>
                <div class="mt-2">{MSTOREEDIT_FORM_URL_PFS}   {MSTOREEDIT_FORM_URL_SFS}</div>
                <small class="form-text text-muted">{PHP.L.mstore_urlhint}</small>
              </div>
              <div class="col-12">
                <label for="mstoreSize" class="form-label fw-semibold">{PHP.L.mstore_filesize}</label>
                <div class="input-group has-validation">{MSTOREEDIT_FORM_SIZE}</div>
                <small class="form-text text-muted">{PHP.L.mstore_filesizehint}</small>
              </div>
              <div class="col-12">
                <label for="mstoreDownloads" class="form-label fw-semibold">{PHP.L.mstore_filehitcount}</label>
                <div class="input-group has-validation">{MSTOREEDIT_FORM_FILE_DOWNLOADS}</div>
                <small class="form-text text-muted">{PHP.L.mstore_filehitcounthint}</small>
              </div>
              <!-- ENDIF -->
              <!-- IF {PHP|cot_plugin_active('attacher')} -->
              <!-- IF {PHP|cot_auth('plug', 'attacher', 'W')} -->
              <div class="col-12">
                <label class="form-label fw-semibold">{PHP.L.att_add_pict_files}</label>
                <div>
                  {MSTOREEDIT_FORM_ID|att_filebox('mstore', $this)}
                </div>
              </div>
              <!-- ENDIF -->
              <!-- ENDIF -->
              <div class="col-12">
                <label for="mstoreDelete" class="form-label fw-semibold">{PHP.L.mstore_deleteitem}</label>
                <div class="input-group has-validation">{MSTOREEDIT_FORM_DELETE}</div>
              </div>
              <div class="col-12">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                  <!-- IF {PHP.usr_can_publish} -->
                  <button type="submit" name="rmsitemstate" value="0" class="btn btn-success">{PHP.L.Publish}</button>
                  <!-- ENDIF -->
                  <button type="submit" name="rmsitemstate" value="2" class="btn btn-secondary">{PHP.L.Saveasdraft}</button>
                  <button type="submit" name="rmsitemstate" value="1" class="btn btn-warning">{PHP.L.Submitforapproval}</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- This is the name of the template for informing the administrator -->
  <!-- IF {PHP.usr.maingrp} == 5 AND {PHP.mskin} --> {FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/inc/mskin.tpl"}
  <!-- ENDIF -->
<!-- END: MAIN -->