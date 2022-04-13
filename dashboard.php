<?php

$session_redirect = '.';
require_once __DIR__ . '/src/require_all.php';
session_write_close(); // No write access needed anymore
require_once __DIR__ . '/check_timeout.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="description" content="<?php echo $db_owner ?>">
  <meta name="author" content="<?php echo $db_author ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $db_owner ?></title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css"
        integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css"
        integrity="sha512-Oy+sz5W86PK0ZIkawrG0iv7XwWhYecM3exvUtMKNJMekGFJtVAhibhRPTpmyTj8+lJCkmWfnpxKgT2OopquBHA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/css/bootstrap.min.css"
        integrity="sha512-T584yQ/tdRR5QwOpfvDfVQUidzfgc2339Lc8uBDtcp/wYu80d7jwBgAxbyMh0a9YM9F8N3tdErpFI8iaGx6x5g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid-theme.min.css"
        integrity="sha512-jx8R09cplZpW0xiMuNFEyJYiGXJM85GUL+ax5G3NlZT3w6qE7QgxR4/KE1YXhKxijdVTDNcQ7y6AJCtSpRnpGg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css"
        integrity="sha512-3Epqkjaaaxqq/lt5RLJsTzP6cCIFyipVRcY4BcPfjOiGM1ZyFCv4HHeWS7eCPVaAigY3Ha3rhRgOsWaWIClqQQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

  <link rel="stylesheet" href="css/portal.css"/>

  <script src="js/tinymce/tinymce.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
          integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
          crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"
          integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA=="
          crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/js/bootstrap.min.js"
          integrity="sha512-UR25UO94eTnCVwjbXozyeVd6ZqpaAE9naiEUBK/A+QDbfSTQFhPGj5lOR6d8tsgbBk84Ggb5A3EkjsOgPRPcKA=="
          crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"
          integrity="sha512-blBYtuTn9yEyWYuKLh8Faml5tT/5YPG0ir9XEABu5YCj7VGr2nb21WPFT9pnP4fcC3y0sSxJR1JqFTfTALGuPQ=="
          crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <script src="js/jsgrid-da.js"></script>
</head>
<body>

<!-- Image and text -->
<nav class="navbar navbar-light bg-light" id="main_header">
    <a class="navbar-brand" href=".">
        <img src="../favicon.ico" width="30" height="30" class="d-inline-block align-top" alt="">
        <?php echo $db_owner ?>
    </a>
    <div class="navbar-brand" id="mailstatus"></div>
    <div class="navbar-brand" id="username"><?php echo $_SESSION['account_name']; ?></div>
    <div class="navbar-brand" onclick="event.preventDefault(); logout()">
        <i class="bi-box-arrow-right"></i>
        &nbsp;<?php L('logout') ?>
    </div>
</nav>

<!-- Tab line -->
<ul class="nav nav-tabs" id="main_tab">
    <li class="nav-item">
        <a href="#help_pane" class="nav-link" data-toggle="tab" id="help_tab"><?php L('help') ?></a>
    </li>
    <li class="nav-item">
        <a href="#persons_pane" class="nav-link" data-toggle="tab" id="persons_tab"><?php L('persons') ?></a>
    </li>
    <li class="nav-item">
        <a href="#apartments_pane" class="nav-link active" data-toggle="tab" id="apartments_tab"><?php L('apartments') ?></a>
    </li>
    <li class="nav-item">
        <a href="#parkings_pane" class="nav-link" data-toggle="tab" id="parkings_tab"><?php L('parkings') ?></a>
    </li>
    <li class="nav-item">
        <a href="#depots_pane" class="nav-link" data-toggle="tab" id="depots_tab"><?php L('depots') ?></a>
    </li>
  <?php if ($_SESSION['role_mail']) { ?>
    <li class="nav-item">
        <a href="#draft_pane" class="nav-link" data-toggle="tab" id="draft_tab"><?php L('msg_draft') ?></a>
    </li>
    <li class="nav-item">
        <a href="#mails_pane" class="nav-link" data-toggle="tab" id="mails_tab"><?php L('msg_mails') ?></a>
    </li>
  <?php } ?>
  <?php if ($_SESSION['role_admin']) { ?>
    <li class="nav-item">
        <a href="#accounts_pane" class="nav-link" data-toggle="tab" id="accounts_tab"><?php L('accounts') ?></a>
    </li>
    <li class="nav-item">
        <a href="#test_pane" class="nav-link" data-toggle="tab" id="test_tab"><?php L('test') ?></a>
    </li>
  <?php } ?>
</ul>

<!-- Tab content -->
<div class="tab-content" id="main_content">
    <div class="tab-pane fade" id="help_pane">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="help-box">
                        <?php readfile(__DIR__ . '/src/help_da.html'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="persons_pane">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div>
                        <div class="form-check-inline"><?php L('rel_show') ?>:</div>
                        <div class="form-check-inline">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input persons_chk" id="persons_historical"><?php L('rel_hist') ?>
                          </label>
                        </div>
                    </div>
                    <div class="flex-grid" id="persons_grid"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade show active" id="apartments_pane">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div>
                        <div class="form-check-inline"><?php L('rel_show') ?>:</div>
                        <div class="form-check-inline">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input apartments_chk" id="apartments_owner" checked><?php L('rel_owner') ?>
                          </label>
                        </div>
                        <div class="form-check-inline">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input apartments_chk" id="apartments_extern" checked><?php L('rel_ext_owner') ?>
                          </label>
                        </div>
                        <div class="form-check-inline">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input apartments_chk" id="apartments_tenant" checked><?php L('rel_tenant') ?>
                          </label>
                        </div>
                        <div class="form-check-inline">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input apartments_chk" id="apartments_historical"><?php L('rel_hist') ?>
                          </label>
                        </div>
                    </div>
                    <div class="flex-grid" id="apartments_grid"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="parkings_pane">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div>
                        <div class="form-check-inline"><?php L('rel_show') ?>:</div>
                        <div class="form-check-inline">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input parkings_chk" id="parkings_owner" checked><?php L('rel_owner') ?>
                          </label>
                        </div>
                        <div class="form-check-inline">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input parkings_chk" id="parkings_extern" checked><?php L('rel_ext_owner') ?>
                          </label>
                        </div>
                        <div class="form-check-inline">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input parkings_chk" id="parkings_tenant" checked><?php L('rel_tenant') ?>
                          </label>
                        </div>
                        <div class="form-check-inline">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input parkings_chk" id="parkings_historical"><?php L('rel_hist') ?>
                          </label>
                        </div>
                    </div>
                    <div class="flex-grid" id="parkings_grid"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="depots_pane">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6">
                    <div>
                        <div class="form-check-inline"><?php L('rel_show') ?>:</div>
                        <div class="form-check-inline">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input depots_chk" id="depots_historical"><?php L('rel_hist') ?>
                          </label>
                        </div>
                    </div>
                    <div class="flex-grid" id="depots_grid"></div>
                </div>
                <div class="col-lg-6">
                    <div>
                        <div class="form-check-inline"><?php L('de_wait') ?>:</div>
                    </div>
                    <div class="flex-grid" id="depots_wait_grid"></div>
                </div>
            </div>
        </div>
    </div>
  <?php if ($_SESSION['role_mail']) { ?>
    <div class="tab-pane fade" id="draft_pane">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mail-line">
                        <strong>&nbsp;<?php L('msg_to') ?>:&nbsp;</strong>
                        <div class="form-check-inline">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input draft_chk" id="draft_persons"><?php L('persons') ?>
                          </label>
                        </div>
                        <div class="form-check-inline">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input draft_chk" id="draft_apartments"><?php L('apartments') ?>
                          </label>
                        </div>
                        <div class="form-check-inline">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input draft_chk" id="draft_parkings"><?php L('parkings') ?>
                          </label>
                        </div>
                        <div class="form-check-inline">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input draft_chk" id="draft_depots"><?php L('depots') ?>
                          </label>
                        </div>
                        <div class="form-check-inline">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input draft_chk" id="draft_board"><?php L('acc_role_board') ?>
                          </label>
                        </div>
                        <div class="form-check-inline">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input draft_chk" id="draft_caretaker"><?php L('acc_role_caretaker') ?>
                          </label>
                        </div>
                        <div class="form-check-inline">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input draft_chk" id="draft_administrator"><?php L('acc_role_administrator') ?>
                          </label>
                        </div>
                        <span id="draft_num_rx"></span>
                    </div>
                    <textarea class="fixed-ta" id="draft_to" rows="5" readonly></textarea>
                    <div class="mail-line">
                        <strong><?php L('msg_attachments') ?>:&nbsp;</strong>
                    </div>
                    <div class="attachments" id="draft_attachments"
                         onclick="$('#draft_files').click();"
                         ondragenter="draftDragEnter(event);"
                         ondragover="draftDragOver(event);"
                         ondragleave="draftDragLeave(event);"
                         ondrop="draftDragDrop(event);"
                         >
                    </div>
                    <input type="file" id="draft_files" multiple style="display:none">
                    <div class="mail-line">
                        <strong><?php L('msg_subject') ?>:&nbsp;</strong>
                    </div>
                    <input type="text" id="draft_subject">
                    <div class="mail-line">
                        <strong><?php L('msg_body') ?>:&nbsp;</strong>
                    </div>
                    <textarea id="draft_body"></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="mails_pane">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4">
                    <div class="mail-line">
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="mail_sent" name="mb_group" value="3"
                                       onclick="mailsGrid()" checked><?php L('msg_sent') ?>
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="mail_queue" name="mb_group" value="2"
                                       onclick="mailsGrid()"><?php L('msg_queue') ?>
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" id="mail_trash" name="mb_group" value="4"
                                       onclick="mailsGrid()"><?php L('msg_trash') ?>
                            </label>
                        </div>
                    </div>
                    <div class="flex-grid" id="mails_grid"></div>
                </div>
                <div class="col-lg-8">
                    <div class="mail-line">
                        <strong><?php L('msg_sent') ?>:&nbsp;</strong>
                        <span id="mail_date"></span>
                        <strong>&nbsp;<?php L('msg_by') ?>:&nbsp;</strong>
                        <span id="mail_from"></span>
                        <strong>&nbsp;<?php L('msg_to') ?>:&nbsp;</strong>
                        <span id="mail_num_rx"></span>
                    </div>
                    <textarea class="fixed-ta" id="mail_to" name="mail_to" rows="5" readonly></textarea>
                    <div class="mail-line">
                        <strong><?php L('msg_attachments') ?>:&nbsp;</strong>
                    </div>
                    <div class="attachments" id="mail_attachments">
                    </div>
                    <div class="mail-line">
                        <strong><?php L('msg_subject') ?>:&nbsp;</strong>
                    </div>
                    <input type="text" id="mail_subject" name="mail_subject" readonly>
                    <div class="mail-line">
                        <strong><?php L('msg_body') ?>:&nbsp;</strong>
                    </div>
                    <textarea id="mail_body" name="mail_body"></textarea>
                </div>
            </div>
        </div>
    </div>
  <?php } ?>
  <?php if ($_SESSION['role_admin']) { ?>
    <div class="tab-pane fade" id="accounts_pane">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="flex-grid" id="accounts_grid"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="test_pane">
        <p>screen.orientation.type: <span id="sot"></span></p>
        <p>screen.orientation.angle: <span id="soa"></span></p>
        <p>screen.height: <span id="sh"></span></p>
        <p>screen.width: <span id="sw"></span></p>
        <p>screen.availHeight: <span id="sah"></span></p>
        <p>screen.availWidth: <span id="saw"></span></p>
        <p>window.innerHeight: <span id="ih"></span></p>
        <p>window.innerWidth: <span id="iw"></span></p>
        <!--
        <p>Test tab content ...</p>
        <p>jQuery UI Icons:</p>
        <span class="ui-icon ui-icon-arrowthick-1-n"></span>
        <br>
        <span class="ui-icon ui-icon-check"></span>
        <br>
        <p>Bootstrap Icons:</p>
        <i class="bi-alarm" style="font-size: 2rem; color: cornflowerblue;"></i>
        <br>
        <i class="bi-alarm" style="color: cornflowerblue;"></i>
        <br>
        <span id="custom_send" class="bi-send"></span>
        <br>
        <p>PHP variables:</p>
        <p><?php echo __DIR__ ?></p>
        <p><?php echo print_r($_SERVER['HTTP_ACCEPT_LANGUAGE'], 1) ?></p>
        <p><?php echo print_r(locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE']), 1) ?></p>
        -->
        <!--
        <button type="button" onclick="event.preventDefault(); console.log(apartmentFilter());">ApartmentFilter</button>
        -->
        <br><br>
        <?php
            //phpinfo();
        ?>
    </div>
  <?php } ?>
</div>

<!-- Modal -->
<div class="modal fade" id="apartments_persons_modal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?php L('rel_apartment') ?><span id="apartments_persons_relations"></span>:</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div id="apartments_persons_grid"></div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="parkings_persons_modal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?php L('rel_parking') ?><span id="parkings_persons_relations"></span>:</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div id="parkings_persons_grid"></div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="depots_persons_modal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?php L('rel_depot') ?><span id="depots_persons_relations"></span>:</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div id="depots_persons_grid"></div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="persons_modal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?php L('rel_person') ?><span id="persons_relations"></span>:</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div id="persons_apartments_grid"></div><br><br>
        <div id="persons_parkings_grid"></div><br><br>
        <div id="persons_depots_grid"></div>
      </div>
    </div>
  </div>
</div>

<!-- JavaScript content -->
<script>

'use strict';

const account_id  = <?php echo json_encode($_SESSION['account_id']); ?>;
const role_admin  = <?php echo json_encode($_SESSION['role_admin']); ?>;
const role_update = <?php echo json_encode($_SESSION['role_update']); ?>;
const role_mail   = <?php echo json_encode($_SESSION['role_mail']); ?>;

// currentDraft contains mailid of current draft
var currentDraft;

// filterChanged is set to true when something has changed in
// either apartments, parkings, depots, persons or accounts.
var filterChanged = false;

const relations = [ { id: 0, relation: "<?php L('select') ?>" },
                    { id: 1, relation: "<?php L('rel_owner') ?>" },
                    { id: 2, relation: "<?php L('rel_ext_owner') ?>" },
                    { id: 3, relation: "<?php L('rel_tenant') ?>" } ];

const accountStates = [ { id: 0, state: "" },
                        { id: 1, state: "<?php L('acc_state_first') ?>" },
                        { id: 2, state: "<?php L('acc_state_enabled') ?>" },
                        { id: 3, state: "<?php L('acc_state_disabled') ?>" } ];

const accountRoles = [ { id: 2, role: "<?php L('acc_role_board') ?>" },
                       { id: 3, role: "<?php L('acc_role_caretaker') ?>" },
                       { id: 4, role: "<?php L('acc_role_administrator') ?>" },
                       { id: 5, role: "<?php L('acc_role_tester') ?>" },
                       { id: 1, role: "<?php L('acc_role_superman') ?>" } ];

const accountLang = [ { id: 1, value: "da" },
                      { id: 2, value: "en" } ];

function logout() {
    return $.ajax({
        type: "POST",
        url: "logout_action.php",
        data: { account_id: account_id },
    }).then(function() {
        location.reload();
    });
}

// Convert DATETIME from SQL to local time
function localTime(utc) {
    const t = new Date(utc + "Z");
    return t.toLocaleString('sv-SE'); // yyyy-mm-dd hh:mm:ss
}

function localDate(utc) {
    const t = new Date(utc + "Z");
    return t.toLocaleString('sv-SE').substring(0, 10); // yyyy-mm-dd
}

// Toggle highlight on jsGrid row
function toggleHighlight(obj, item) {
    // https://github.com/tabalinas/jsgrid/issues/194
    let selected = item;
    let $row = obj.rowByItem(item);
    if ($row.hasClass("highlight") === false) {
        for (let i = 0; i < obj.data.length; i++) {
            obj.rowByIndex(i).removeClass("highlight");
        }
    } else {
        selected = null;
    }
    $row.toggleClass("highlight");
    return selected;
}

function setHighlight(obj, item) {
    // https://github.com/tabalinas/jsgrid/issues/194
    let selected = item;
    let $row = obj.rowByItem(item);
    if ($row.hasClass("highlight") === false) {
        for (let i = 0; i < obj.data.length; i++) {
            obj.rowByIndex(i).removeClass("highlight");
        }
        $row.addClass("highlight");
    }
    return selected;
}

// Handle mail check status in navbar
function mailStatus() {
    $.ajax({
        type: "GET",
        url: "mail_status.php"
    }).then(function (a) {
        let diff = new Date() - new Date(a.checked + "Z");
        if (diff > (1000 * 60 * 6)) { // Mail is expected to be checked every 5 minutes.
            $('#mailstatus').css("color", "red"); // Mark red if check is older than 6 minutes
        } else {
            $('#mailstatus').css("color", "");
        }
        let sts = `<?php L('msg_latest') ?> ${localTime(a.lastsend)}`;
        if (a.queued) {
            sts += `, <?php L('msg_queued') ?> ${a.queued}`;
        }
        $('#mailstatus').html(sts);
        const title = `<?php L('msg_checked') ?> ${localTime(a.checked)} - ${a.remote} - <?php L('msg_code') ?> ${a.sendstatus}`;
        $('#mailstatus').attr('title', title);
    });
}

// Report account activity to database
function accountActivity() {
    $.ajax({
        type: "PUT",
        url: "account_activity.php",
        data: { account_id: account_id }
    });
}

var activity = 1;
var idleTime = 0;
const timeoutTime = 60; // minutes inactivity before auto logout

function tick() {
    if (activity > 0) {
        activity = 0;
        accountActivity();
    }

    idleTime += 1;
    if (idleTime > timeoutTime) {
        logout();
    }
    mailStatus();
}

//------------------------------------------------------------------------------
// Test and debug
//------------------------------------------------------------------------------
function test() {
    const sot_id = document.querySelector('#sot');
    const soa_id = document.querySelector('#soa');
    const sh_id = document.querySelector('#sh');
    const sw_id = document.querySelector('#sw');
    const sah_id = document.querySelector('#sah');
    const saw_id = document.querySelector('#saw');
    const ih_id = document.querySelector('#ih');
    const iw_id = document.querySelector('#iw');

    function reportWindowSize() {
        ih_id.textContent = window.innerHeight;
        iw_id.textContent = window.innerWidth;
    }
    window.onresize = reportWindowSize;

    sot_id.textContent = screen.orientation.type;
    soa_id.textContent = screen.orientation.angle;
    sh_id.textContent = screen.height;
    sw_id.textContent = screen.width;
    sah_id.textContent = screen.availHeight;
    saw_id.textContent = screen.availWidth;
    ih_id.textContent = window.innerHeight;
    iw_id.textContent = window.innerWidth;
}

//------------------------------------------------------------------------------
// Apartments
//------------------------------------------------------------------------------

// Indicates that a relation has changed and the grid must be reloaded
var apartmentRelationChanged = false;

var apartmentSelect; // Array of { id: <number>, name: <name> }

function apartmentSelectUpdate() {
    return $.ajax({
        type: "GET",
        url: "apartments_all.php"
    }).then(function(apartments) {
        apartmentSelect = [{ id: 0, name: "<?php L('select') ?>" }, { id: 1, name: "" }];
        for (const a of apartments) {
            apartmentSelect.push( { id: a["apartmentid"], name: `${a["number"]} ${a["floor"]} ${a["side"]} (#${a["apartmentid"]})` } );
        }
    });
}

function apartmentGet(id) {
    for (let i of apartmentSelect) {
       if (i.id === id) {
           return i.name;
       }
    }
    return "????";
}

function apartmentFilter(filter) {
    if (!filter) {
        filter = $("#apartments_grid").jsGrid("getFilter");
    }
    return { ...filter,
             owner: $('#apartments_owner').prop('checked'),
             extern: $('#apartments_extern').prop('checked'),
             tenant: $('#apartments_tenant').prop('checked'),
             historical: $('#apartments_historical').prop('checked')
           }
}

// (re)load the apartmentsgrid
function apartmentsGrid() {
    $('#apartments_grid').jsGrid("destroy");

    $("#apartments_grid").jsGrid({
        width: "100%",
        height: "100%",
        inserting: role_admin,
        editing: role_admin,
        filtering: true,
        sorting: true,
        autoload: true,
        deleteConfirm: "<?php L('del_apartment') ?>",
        controller: {
            loadData: function(filter) {
                return $.ajax({
                    type: "GET",
                    url: "apartments.php",
                    data: apartmentFilter(filter)
                });
            },
            insertItem: function(item) {
                return $.ajax({
                    type: "POST",
                    url: "apartments.php",
                    data: item
                });
            },
            updateItem: function(item) {
                return $.ajax({
                    type: "PUT",
                    url: "apartments.php",
                    data: item
                });
            },
            deleteItem: function(item) {
                return $.ajax({
                    type: "DELETE",
                    url: "apartments.php",
                    data: item
                });
            }
        },
        onDataLoaded: function(args) {
            filterChanged = true;
        },
        onItemInserted: function(args) {
            filterChanged = true;
        },
        onItemUpdated: function(args) {
            filterChanged = true;
        },
        onItemDeleted: function(args) {
            filterChanged = true;
        },
        onError: function(args) {
            alert(args.args[0].responseJSON);
        },
        rowClick: function(args) {
            setHighlight(this, args.item);
            $('#apartments_persons_relations').html(apartmentGet(args.item.apartmentid));
            apartmentsPersons('#apartments_persons_grid', '#apartments_historical', 0, args.item.apartmentid);
            $('#apartments_persons_modal').modal('show');
        },
        fields: [
            { width:  20, name: "apartmentid", title: "<?php L('apartment') ?>",    type: "text", sorter: "numberAsString", validate: "required", editing: false },
            { width:  20, name: "number",      title: "<?php L('ap_number') ?>",    type: "text", sorter: "numberAsString", validate: "required"},
            { width:  20, name: "floor",       title: "<?php L('ap_floor') ?>",     type: "text", validate: "required"},
            { width:  20, name: "side",        title: "<?php L('ap_side') ?>",      type: "text", validate: "required"},
            { width:  20, name: "type",        title: "<?php L('ap_type') ?>",      type: "text", validate: "required",
              itemTemplate: (v, i) => {
                  // stopPropagation() prevents opening the relations dialog
                  return v ? `<a href="files/${v.toUpperCase()}.pdf" onclick="event.stopPropagation()">${v}</a>` : v;
              }},
            { width:  20, name: "size",        title: "<?php L('ap_size') ?>",      type: "text", sorter: "numberAsString", validate: "required"},
            { width:  20, name: "reduction",   title: "<?php L('ap_reduction') ?>", type: "text", sorter: "numberAsString", validate: "required"},
            { width:  20, name: "tapshares",   title: "<?php L('ap_tapshares') ?>", type: "text", sorter: "numberAsString", validate: "required"},
            { width:  20, name: "shafts",      title: "<?php L('ap_shafts') ?>",    type: "text", validate: "required"},
            { width:  10, type: "control",     editButton: role_admin, deleteButton: role_admin, modeSwitchButton: role_admin}
        ]
    });
}

//------------------------------------------------------------------------------
// Parkings
//------------------------------------------------------------------------------

// Indicates that a relation has changed and the grid must be reloaded
var parkingRelationChanged = false;

var parkingSelect; // Array of { parkingid: <number>, name: <name> }

function parkingSelectUpdate() {
    return $.ajax({
        type: "GET",
        url: "parkings_all.php"
    }).then(function(parkings) {
        parkings.unshift({ parkingid: "<?php L('select') ?>" });
        parkingSelect = [...parkings];
    });
}

function parkingGet(id) {
    return id;
}

function parkingFilter(filter) {
    if (!filter) {
        filter = $("#parkings_grid").jsGrid("getFilter");
    }
    return { ...filter,
             owner: $('#parkings_owner').prop('checked'),
             extern: $('#parkings_extern').prop('checked'),
             tenant: $('#parkings_tenant').prop('checked'),
             historical: $('#parkings_historical').prop('checked')
           }
}

// (re)load the parkings grid
function parkingsGrid() {
    $('#parkings_grid').jsGrid("destroy");

    $("#parkings_grid").jsGrid({
        width: "100%",
        height: "100%",
        inserting: role_admin,
        editing: role_update,
        filtering: true,
        sorting: true,
        autoload: true,
        deleteConfirm: "<?php L('del_parking') ?>",
        controller: {
            loadData: function(filter) {
                return $.ajax({
                    type: "GET",
                    url: "parkings.php",
                    data: parkingFilter(filter)
                });
            },
            insertItem: function(item) {
                return $.ajax({
                    type: "POST",
                    url: "parkings.php",
                    data: item
                });
            },
            updateItem: function(item) {
                return $.ajax({
                    type: "PUT",
                    url: "parkings.php",
                    data: item
                });
            },
            deleteItem: function(item) {
                return $.ajax({
                    type: "DELETE",
                    url: "parkings.php",
                    data: item
                });
            }
        },
        onDataLoaded: function(args) {
            filterChanged = true;
        },
        onItemInserted: function(args) {
            filterChanged = true;
        },
        onItemUpdated: function(args) {
            filterChanged = true;
        },
        onItemDeleted: function(args) {
            filterChanged = true;
        },
        onError: function(args) {
            alert(args.args[0].responseJSON);
        },
        rowClick: function(args) {
            setHighlight(this, args.item);
            $('#parkings_persons_relations').html(parkingGet(args.item.parkingid));
            parkingsPersons('#parkings_persons_grid', '#parkings_historical', 0, args.item.parkingid);
            $('#parkings_persons_modal').modal('show');
        },
        fields: [
            { width:  20, name: "parkingid", title: "<?php L('parking') ?>",    type: "text",
              sorter: "numberAsString", validate: "required", editing: false},
            { width:  20, name: "depot",     title: "<?php L('pa_depot') ?>",   type: "checkbox", editing: role_admin },
            { width:  20, name: "charger",   title: "<?php L('pa_charger') ?>", type: "checkbox" },
            { width:  20, name: "power",     title: "<?php L('pa_power') ?>",   type: "select", validate: { validator: "min", param: 1 },
              items: apartmentSelect, valueField: "id", textField: "name", filtering: false },
            { width:  20, name: "ccharger",  title: "<?php L('pa_ccharger') ?>", type: "checkbox" },
            { width:  10, type: "control",   editButton: role_update, deleteButton: role_admin, modeSwitchButton: role_admin} 
        ]
    });
}

//------------------------------------------------------------------------------
// Depots
//------------------------------------------------------------------------------

// Indicates that a relation has changed and the grid must be reloaded
var depotRelationChanged = false;

var depotSelect; // Array of { depotid: <number>, name: <name> }

function depotSelectUpdate() {
    return $.ajax({
        type: "GET",
        url: "depots_all.php"
    }).then(function(depots) {
        depots.unshift({ depotid: "<?php L('select') ?>" });
        depotSelect = [...depots];
    });
}

function depotGet(id) {
    return id;
}

function depotFilter(filter) {
    if (!filter) {
        filter = $("#depots_grid").jsGrid("getFilter");
    }
    return { ...filter,
             historical: $('#depots_historical').prop('checked')
           }
}

// (re)load the depots grid
function depotsGrid() {
    $('#depots_grid').jsGrid("destroy");

    $("#depots_grid").jsGrid({
        width: "100%",
        height: "100%",
        inserting: role_update,
        editing: role_update,
        filtering: true,
        sorting: true,
        autoload: true,
        deleteConfirm: "<?php L('del_depot') ?>",
        controller: {
            loadData: function(filter) {
                return $.ajax({
                    type: "GET",
                    url: "depots.php",
                    data: depotFilter(filter)
                });
            },
            insertItem: function(item) {
                return $.ajax({
                    type: "POST",
                    url: "depots.php",
                    data: item
                });
            },
            updateItem: function(item) {
                return $.ajax({
                    type: "PUT",
                    url: "depots.php",
                    data: item
                });
            },
            deleteItem: function(item) {
                return $.ajax({
                    type: "DELETE",
                    url: "depots.php",
                    data: item
                });
            }
        },
        onDataLoaded: function(args) {
            filterChanged = true;
        },
        onItemInserted: function(args) {
            depotSelectUpdate();
            filterChanged = true;
        },
        onItemUpdated: function(args) {
            depotSelectUpdate();
            filterChanged = true;
        },
        onItemDeleted: function(args) {
            depotSelectUpdate();
            filterChanged = true;
        },
        onError: function(args) {
            alert(args.args[0].responseJSON);
        },
        rowClick: function(args) {
            setHighlight(this, args.item);
            $('#depots_persons_relations').html(depotGet(args.item.depotid));
            depotsPersons('#depots_persons_grid', '#depots_historical', 0, args.item.depotid);
            $('#depots_persons_modal').modal('show');
        },
        fields: [
            { width:  20, name: "depotid", title: "<?php L('depot') ?>",       type: "text", sorter: "numberAsString", validate: "required", editing: false },
            { width:  20, name: "number",  title: "<?php L('de_location') ?>", type: "text", validate: "required" },
            { width:  10, name: "isfree",  title: "<?php L('de_isfree') ?>",   type: "text", filtering: false, editing: false, inserting: false,
              itemTemplate: function(value, item) {
                  if (value) {
                      return '<span class="ui-icon ui-icon-check"></span>';
                  }
              }},
            { width:  10, type: "control", editButton: role_update, deleteButton: role_update, modeSwitchButton: role_update} 
        ]
    });
}

//------------------------------------------------------------------------------
// Depots waiting list
//------------------------------------------------------------------------------

// (re)load the depots wait grid
function depotsWaitGrid() {
    $('#depots_wait_grid').jsGrid("destroy");

    $("#depots_wait_grid").jsGrid({
        width: "100%",
        height: "100%",
        inserting: role_update,
        selecting: false,
        autoload: true,
        deleteConfirm: "<?php L('del_depotwait') ?>",
        controller: {
            loadData: function(filter) {
                return $.ajax({
                    type: "GET",
                    url: "depots_wait.php",
                    data: {}
                });
            },
            insertItem: function(item) {
                return $.ajax({
                    type: "POST",
                    url: "depots_wait.php",
                    data: item
                });
            },
            deleteItem: function(item) {
                return $.ajax({
                    type: "DELETE",
                    url: "depots_wait.php",
                    data: item
                });
            }
        },
        onError: function(args) {
            alert(args.args[0].responseJSON);
        },
        fields: [
            { width:  10, name: "waitid",   title: "<?php L('de_waitid') ?>", type: "text",
              filtering: false, editing: false, inserting: false, visible: role_admin },
            { width:  40, name: "modified", title: "<?php L('started') ?>",   type: "text",
              filtering: false, editing: false, inserting: false, itemTemplate: localTime },
            { width:  60, name: "personid", title: "<?php L('person') ?>",    type: "select", validate: { validator: "min", param: 1 },
              items: personSelect, valueField: "personid", textField: "name", editing: false },
            { width:  10, type: "control", editButton: false, deleteButton: role_update, modeSwitchButton: false}
        ]
    });
}

//------------------------------------------------------------------------------
// Persons
//------------------------------------------------------------------------------

// Indicates that a relation has changed and the grid must be reloaded
var personRelationChanged = false;

var personSelect; // Array of { personid: <number>, name: <name> }

function personSelectUpdate() {
    return $.ajax({
        type: "GET",
        url: "persons_all.php"
    }).then(function(persons) {
        persons.sort((a, b) => a.name.localeCompare(b.name));
        persons.unshift({ personid: 0, name: "<?php L('select') ?>" });
        personSelect = [...persons];
    });
}

function personGet(id) {
    for (let i of personSelect) {
       if (i.personid === id) {
           let txt = `${i.name} - ${i.email}`
           if (i.phone.length) {
               txt += ` - ${i.phone}`;
           }
           return txt;
       }
    }
    return "????";
}

function personFilter(filter) {
    if (!filter) {
        filter = $("#persons_grid").jsGrid("getFilter");
    }
    return { ...filter,
             historical: $('#persons_historical').prop('checked')
           }
}

// (re)load the persons grid
function personsGrid() {
    $('#persons_grid').jsGrid("destroy");

    $("#persons_grid").jsGrid({
        width: "100%",
        height: "100%",
        inserting: role_update,
        editing: role_update,
        filtering: true,
        sorting: true,
        autoload: true,
        deleteConfirm: "<?php L('del_person') ?>",
        controller: {
            loadData: function(filter) {
                const data = personFilter(filter);
                return $.ajax({
                    type: "GET",
                    url: "persons.php",
                    data: personFilter(filter)
                });
            },
            insertItem: function(item) {
                return $.ajax({
                    type: "POST",
                    url: "persons.php",
                    data: item,
                });
            },
            updateItem: function(item) {
                return $.ajax({
                    type: "PUT",
                    url: "persons.php",
                    data: item
                });
            },
            deleteItem: function(item) {
                return $.ajax({
                    type: "DELETE",
                    url: "persons.php",
                    data: item
                });
            }
        },
        onDataLoaded: function(args) {
            filterChanged = true;
        },
        onItemInserted: function(args) {
            personSelectUpdate();
            filterChanged = true;
        },
        onItemUpdated: function(args) {
            personSelectUpdate();
            filterChanged = true;
        },
        onItemDeleted: function(args) {
            personSelectUpdate();
            filterChanged = true;
        },
        onError: function(args) {
            alert(args.args[0].responseJSON);
        },
        rowClick: function(args) {
            setHighlight(this, args.item);
            $('#persons_relations').html(personGet(args.item.personid));
            apartmentsPersons('#persons_apartments_grid', '#persons_historical', args.item.personid, 0);
            parkingsPersons('#persons_parkings_grid', '#persons_historical', args.item.personid, 0);
            depotsPersons('#persons_depots_grid', '#persons_historical', args.item.personid, 0);
            $('#persons_modal').modal('show');
        },
        fields: [
            { width:  10, name: "personid", title: "Id",                        type: "number", filtering: false, editing: false, inserting: false },
            { width:  50, name: "name",     title: "<?php L('pe_name') ?>",     type: "text", validate: "required" },
            { width:  50, name: "address",  title: "<?php L('pe_address') ?>",  type: "text", validate: "required",
              itemTemplate: function(value, item) {
                  return value.replace('eidekampsgade', 'eidekamps<wbr>gade'); // Add line break oportunity on one of the most used street name
              }},
            { width:  50, name: "email",    title: "<?php L('pe_email') ?>",    type: "text", validate: "email",
              itemTemplate: function(value, item) {
                  return value.replace('@', '<wbr>@'); // Add line break oportunity
              }},
          //{ width:  10, name: "nomails",  title: '<span class="ui-icon ui-icon-mail-closed" title="Send mail to this person"></span>',
          //  type: "checkbox" },
            { width:  10, name: "nomails",  title: '<span title="<?php L('pe_noemail_tip') ?>"><?php L('pe_noemail') ?></span>', type: "checkbox" },
            { width:  20, name: "phone",    title: "<?php L('pe_phone') ?>",    type: "text",
              itemTemplate: function(value, item) {
                  let v = value.trim();
                  if (v.length == 8) { // Add line break oportunity on 'standard' phone numbers
                      return v.substr(0, 4) + '<wbr>' + v.substr(4);
                  } else {
                      return v;
                  }
              }},
            { width:  60, name: "modified", title: "<?php L('modified') ?>", type: "text",
              filtering: false, editing: false, inserting: false, itemTemplate: localTime, visible: role_admin },
            { width:  10, type: "control", editButton: role_update, deleteButton: role_update, modeSwitchButton: role_update} 
        ]
    });
}

//------------------------------------------------------------------------------
// Apartments <-> Persons
//------------------------------------------------------------------------------

// Setting either personId or id to zero means wildcard
function apartmentsPersons(gridId, historicalId, personId, id) {
    let count = 0;
    if (personId) {
        count++;
    }
    if (id) {
        count++;
    }
    if (count !== 1) {
        alert(`personId: ${personId} id: ${id}`);
    }

    function grid(fields) {
        $(gridId).jsGrid({
            width: "100%",
            inserting: role_update,
            editing: role_update,
            autoload: true,
            deleteConfirm: "<?php L('del_relation') ?>",
            controller: {
                loadData: function(filter) {
                    const historical = $(historicalId).prop('checked');
                    return $.ajax({
                        type: "GET",
                        url: "persons_apartments.php",
                        data: { personid: personId, id: id, historical: historical }
                    });
                },
                insertItem: function(item) {
                    let newItem = {};
                    if (personId) {
                        newItem = { personid: personId, ...item }; // don't modify original item 
                    } else {
                        newItem = { id: id, ...item }; // don't modify original item 
                    }
                    apartmentRelationChanged = true;
                    personRelationChanged = true;
                    return $.ajax({
                        type: "POST",
                        url: "persons_apartments.php",
                        data: newItem,
                    });
                },
                updateItem: function(item) {
                    apartmentRelationChanged = true;
                    personRelationChanged = true;
                    return $.ajax({
                        type: "PUT",
                        url: "persons_apartments.php",
                        data: item
                    });
                },
                deleteItem: function(item) {
                    apartmentRelationChanged = true;
                    personRelationChanged = true;
                    return $.ajax({
                        type: "DELETE",
                        url: "persons_apartments.php",
                        data: item
                    });
                }
            },
            onError: function(args) {
                alert(args.args[0].responseJSON);
            },
            fields: fields,
        });
    }

    if (personId) {
        grid([
            { width:  10, name: "personid", title: "<?php L('person') ?>",    type: "text",   visible: false },
            { width:  25, name: "id",       title: "<?php L('apartment') ?>", type: "select", validate: { validator: "min", param: 1 },
              items: apartmentSelect, valueField: "id", textField: "name", editing: false },
            { width:  25, name: "relation", title: "<?php L('relation') ?>",  type: "select", validate: { validator: "range", param: [1, 3] },
              items: relations, valueField: "id", textField: "relation" },
            { width:  25, name: "started",  title: "<?php L('started') ?>",   type: "date",   validate: "required" },
            { width:  25, name: "stopped",  title: "<?php L('stopped') ?>",   type: "date" },
          //{ width: 100, name: "modified", title: "<?php L('modified') ?>",  type: "text",
          //  filtering: false, editing: false, inserting: false, itemTemplate: localTime },
            { width:  10, type: "control", editButton: role_update, deleteButton: role_update, modeSwitchButton: false }
        ]);
    } else {
        grid([
            { width:  10, name: "id",       title: "<?php L('apartment') ?>", type: "text",   visible: false },
            { width:  25, name: "personid", title: "<?php L('person') ?>",    type: "select", validate: { validator: "min", param: 1 },
              items: personSelect, valueField: "personid", textField: "name", editing: false },
            { width:  25, name: "relation", title: "<?php L('relation') ?>",  type: "select", validate: { validator: "range", param: [1, 3] },
              items: relations, valueField: "id", textField: "relation" },
            { width:  25, name: "started",  title: "<?php L('started') ?>",   type: "date",   validate: "required" },
            { width:  25, name: "stopped",  title: "<?php L('stopped') ?>",   type: "date" },
          //{ width: 100, name: "modified", title: "<?php L('modified') ?>",  type: "text",
          //  filtering: false, editing: false, inserting: false, itemTemplate: localTime },
            { width:  10, type: "control", editButton: role_update, deleteButton: role_update, modeSwitchButton: false }
        ]);
    }
}

//------------------------------------------------------------------------------
// Parkings <-> Persons
//------------------------------------------------------------------------------

// Setting either personId or id to zero means wildcard
function parkingsPersons(gridId, historicalId, personId, id) {
    let count = 0;
    if (personId) {
        count++;
    }
    if (id) {
        count++;
    }
    if (count !== 1) {
        alert(`personId: ${personId} id: ${id}`);
    }

    function grid(fields) {
        $(gridId).jsGrid({
            width: "100%",
            inserting: role_update,
            editing: role_update,
            autoload: true,
            deleteConfirm: "<?php L('del_relation') ?>",
            controller: {
                loadData: function(filter) {
                    const historical = $(historicalId).prop('checked');
                    return $.ajax({
                        type: "GET",
                        url: "persons_parkings.php",
                        data: { personid: personId, id: id, historical: historical }
                    });
                },
                insertItem: function(item) {
                    parkingRelationChanged = true;
                    personRelationChanged = true;
                    let newItem = {};
                    if (personId) {
                        newItem = { personid: personId, ...item }; // don't modify original item 
                    } else {
                        newItem = { id: id, ...item }; // don't modify original item 
                    }
                    return $.ajax({
                        type: "POST",
                        url: "persons_parkings.php",
                        data: newItem,
                    });
                },
                updateItem: function(item) {
                    parkingRelationChanged = true;
                    personRelationChanged = true;
                    return $.ajax({
                        type: "PUT",
                        url: "persons_parkings.php",
                        data: item
                    });
                },
                deleteItem: function(item) {
                    parkingRelationChanged = true;
                    personRelationChanged = true;
                    return $.ajax({
                        type: "DELETE",
                        url: "persons_parkings.php",
                        data: item
                    });
                }
            },
            onError: function(args) {
                alert(args.args[0].responseJSON);
            },
            fields: fields,
        });
    }

    if (personId) {
        grid([
            { width:  10, name: "personid", title: "<?php L('person') ?>",   type: "text",   visible: false },
            { width:  25, name: "id",       title: "<?php L('parking') ?>",  type: "select", validate: { validator: "min", param: 1 },
              items: parkingSelect, valueField: "parkingid", textField: "parkingid", editing: false },
            { width:  25, name: "relation", title: "<?php L('relation') ?>", type: "select", validate: { validator: "range", param: [1, 3] },
              items: relations, valueField: "id", textField: "relation" },
            { width:  25, name: "started",  title: "<?php L('started') ?>",  type: "date",   validate: "required" },
            { width:  25, name: "stopped",  title: "<?php L('stopped') ?>",  type: "date" },
          //{ width: 100, name: "modified", title: "<?php L('modified') ?>", type: "text",
          //  filtering: false, editing: false, inserting: false, itemTemplate: localTime },
            { width:  10, type: "control", editButton: role_update, deleteButton: role_update, modeSwitchButton: false }
        ]);
    } else {
        grid([
            { width:  10, name: "id",       title: "<?php L('parking') ?>",  type: "text",   visible: false },
            { width:  25, name: "personid", title: "<?php L('person') ?>",   type: "select", validate: { validator: "min", param: 1 },
              items: personSelect, valueField: "personid", textField: "name", editing: false },
            { width:  25, name: "relation", title: "<?php L('relation') ?>", type: "select", validate: { validator: "range", param: [1, 3] },
              items: relations, valueField: "id", textField: "relation" },
            { width:  25, name: "started",  title: "<?php L('started') ?>",  type: "date",   validate: "required" },
            { width:  25, name: "stopped",  title: "<?php L('stopped') ?>",  type: "date" },
          //{ width: 100, name: "modified", title: "<?php L('modified') ?>", type: "text",
          //  filtering: false, editing: false, inserting: false, itemTemplate: localTime },
            { width:  10, type: "control", editButton: role_update, deleteButton: role_update, modeSwitchButton: false }
        ]);
    }
}

//------------------------------------------------------------------------------
// Depots <-> Persons
//------------------------------------------------------------------------------

// Setting either personId or id to zero means wildcard
function depotsPersons(gridId, historicalId, personId, id) {
    let count = 0;
    if (personId) {
        count++;
    }
    if (id) {
        count++;
    }
    if (count !== 1) {
        alert(`personId: ${personId} id: ${id}`);
    }

    function grid(fields) {
        $(gridId).jsGrid({
            width: "100%",
            inserting: role_update,
            editing: role_update,
            autoload: true,
            deleteConfirm: "<?php L('del_relation') ?>",
            controller: {
                loadData: function(filter) {
                    const historical = $(historicalId).prop('checked');
                    return $.ajax({
                        type: "GET",
                        url: "persons_depots.php",
                        data: { personid: personId, id: id, historical: historical }
                    });
                },
                insertItem: function(item) {
                    depotRelationChanged = true;
                    personRelationChanged = true;
                    let newItem = {};
                    if (personId) {
                        newItem = { personid: personId, ...item }; // don't modify original item 
                    } else {
                        newItem = { id: id, ...item }; // don't modify original item 
                    }
                    return $.ajax({
                        type: "POST",
                        url: "persons_depots.php",
                        data: newItem,
                    });
                },
                updateItem: function(item) {
                    depotRelationChanged = true;
                    personRelationChanged = true;
                    return $.ajax({
                        type: "PUT",
                        url: "persons_depots.php",
                        data: item
                    });
                },
                deleteItem: function(item) {
                    depotRelationChanged = true;
                    personRelationChanged = true;
                    return $.ajax({
                        type: "DELETE",
                        url: "persons_depots.php",
                        data: item
                    });
                }
            },
            onError: function(args) {
                alert(args.args[0].responseJSON);
            },
            fields: fields,
        });
    }

    if (personId) {
        grid([
            { width:  10, name: "personid",  title: "<?php L('person') ?>",   type: "text",   visible: false },
            { width:  34, name: "id",        title: "<?php L('depot') ?>",    type: "select", validate: { validator: "min", param: 1 },
              items: depotSelect, valueField: "depotid", textField: "depotid", editing: false },
            { width:  33, name: "started",   title: "<?php L('started') ?>",  type: "date",   validate: "required" },
            { width:  33, name: "stopped",   title: "<?php L('stopped') ?>",  type: "date" },
          //{ width: 100, name: "modified",  title: "<?php L('modified') ?>", type: "text",
          //  filtering: false, editing: false, inserting: false, itemTemplate: localTime },
            { width:  10, type: "control", editButton: role_update, deleteButton: role_update, modeSwitchButton: false }
        ]);
    } else {
        grid([
            { width:  10, name: "id",        title: "<?php L('depot') ?>",    type: "text",   visible: false },
            { width:  34, name: "personid",  title: "<?php L('person') ?>",   type: "select", validate: { validator: "min", param: 1 },
              items: personSelect, valueField: "personid", textField: "name", editing: false },
            { width:  33, name: "started",   title: "<?php L('started') ?>",  type: "date",   validate: "required" },
            { width:  33, name: "stopped",   title: "<?php L('stopped') ?>",  type: "date" },
          //{ width: 100, name: "modified",  title: "<?php L('modified') ?>", type: "text",
          //  filtering: false, editing: false, inserting: false, itemTemplate: localTime },
            { width:  10, type: "control", editButton: role_update, deleteButton: role_update, modeSwitchButton: false }
        ]);
    }
}

//------------------------------------------------------------------------------
// Draft
//------------------------------------------------------------------------------
// Input is an object with email as key and name as value. E.g.:
// { "alice@gmail.com": "Alice", "bob@hotmail.com": "Bob" }
function draftRecipientsToTxt(recipients) {
    let txt = '';
    const keys = Object.keys(recipients);
    keys.forEach((email, name) => {
        txt += `${recipients[email]} <${email}>, `;
    });
    return txt;
}

function draftResetFilters() {
    $('#draft_persons').prop('checked', false);
    $('#draft_apartments').prop('checked', false);
    $('#draft_parkings').prop('checked', false);
    $('#draft_depots').prop('checked', false);
    $('#draft_board').prop('checked', false);
    $('#draft_caretaker').prop('checked', false);
    $('#draft_administrator').prop('checked', false);
}

function draftGetFilters() {
    let filter = {};
    if ($('#draft_persons').prop('checked')) {
        filter['persons'] = personFilter();
    }
    if ($('#draft_apartments').prop('checked')) {
        filter['apartments'] = apartmentFilter();
    }
    if ($('#draft_parkings').prop('checked')) {
        filter['parkings'] = parkingFilter();
    }
    if ($('#draft_depots').prop('checked')) {
       filter['depots'] = depotFilter();
    }
    if ($('#draft_board').prop('checked')) {
        filter['board'] = true;
    }
    if ($('#draft_caretaker').prop('checked')) {
        filter['caretaker'] = true;
    }
    if ($('#draft_administrator').prop('checked')) {
        filter['administrator'] = true;
    }
    return filter;
}

// Fetch all filters and update database and 'to' field with emails found.
function draftSetRecipients() {
    const filter = draftGetFilters();
    const data = { mailid: currentDraft, accountid: account_id, ...filter };
    $.ajax({
        type: "POST",
        url: "mail_recipients.php",
        data: data
    }).then(function(a) {
        if (a) {
            const num_rcp = Object.keys(a).length;
            if (num_rcp == 1) {
                $('#draft_num_rx').html(`(${num_rcp}&nbsp;<?php L('msg_receiver') ?>)`);
            } else {
                $('#draft_num_rx').html(`(${num_rcp}&nbsp;<?php L('msg_receivers') ?>)`);
            }
            $('#draft_to').val(draftRecipientsToTxt(a));
        } else {
            $('#draft_num_rx').html(`(0&nbsp;<?php L('msg_receivers') ?>)`);
            $('#draft_to').val('');
        }
    });
}

// Input is an array of attachments. E.g.:
// [ { id: 23, mailid: 33, name: "guide.pdf", size: 356, type: "application/pdf" },
//   { id: 24, mailid: 33, name: "todo.txt",  size: 245, type: "text/plain"      } ]
function draftAttachmentsToTxt(attachments) {
    let txt = '';
    for (const a of attachments) {
        txt += `<span title="${a.type}, ${a.size} bytes" style="background-color: WhiteSmoke" `;
        txt += `onclick="event.preventDefault(); event.stopPropagation();">${a.name}`;
        txt += `<i class="bi-trash" onclick="draftDeleteAttachEv(event, ${a.id});"></i></span> &nbsp;`;
    }
    return txt;
}

function draftUpdate(draft) {
    currentDraft = draft.mailid;
    $('#draft_subject').val(draft.subject);
    tinymce.get('draft_body').resetContent(draft.body);
    draftSetRecipients();
    $.ajax({
        type: "GET",
        url: "attachments.php",
        data: { mailid: draft.mailid }
    }).then(function(a) {
        $('#draft_attachments').html(draftAttachmentsToTxt(a));
    });
}

function draftGet() {
    $.ajax({
        type: "GET",
        url: "draft.php",
        data: { accountid: account_id }
    }).then(function(draft) {
        draftUpdate(draft);
    });

}

function draftSave() {
    $.ajax({
        type: "PUT",
        url: "draft.php",
        data: { mailid: currentDraft, accountid: account_id,
                subject: $('#draft_subject').val(),
                body: tinymce.get('draft_body').getContent() }
    }).then(function(a) {
        $('#draft_subject').val(a.subject);
        tinymce.get('draft_body').resetContent(a.body);
    });
}

function draftClear() {
    if (confirm("<?php L('msg_del_conf') ?>")) {
        $.ajax({
            type: "PUT",
            url: "draft.php",
            data: { mailid: currentDraft, accountid: account_id, subject: '', body: '' }
        }).then(function(a) {
            $('#draft_subject').val(a.subject);
            tinymce.get('draft_body').resetContent(a.body);
            draftDeleteAttach(0); // Zero means delete all
        });
    }
}

function draftUploadFiles(files) {
    var formData = new FormData();
    formData.append('mailid', currentDraft);
    for (const f of files) {
        formData.append('file[]', f);
    }
    $.ajax({
        type: "POST",
        url: "attachments.php",
        processData: false,
        contentType: false,
        data: formData,
    }).then(function(a) {
        $('#draft_attachments').html(draftAttachmentsToTxt(a));
    });
}

function draftDeleteAttach(id) {
    $.ajax({
        type: "DELETE",
        url: "attachments.php",
        data: { mailid: currentDraft, id: id }
    }).then(function(a) {
        $('#draft_attachments').html(draftAttachmentsToTxt(a));
    });
}

function draftDeleteAttachEv(e, id) {
    e.preventDefault();
    e.stopPropagation();
    draftDeleteAttach(id);
}

function draftAttach() {
    draftUploadFiles(this.files);
}

function draftDragEnter(e) {
    e.preventDefault();
    e.target.classList.add('drag-over');
}

function draftDragOver(e) {
    e.preventDefault();
    e.target.classList.add('drag-over');
}

function draftDragLeave(e) {
    e.preventDefault();
    e.target.classList.remove('drag-over');
}

function draftDragDrop(e) {
    e.preventDefault();
    e.target.classList.remove('drag-over');

    if (e.dataTransfer && e.dataTransfer.files) {
        draftUploadFiles(e.dataTransfer.files);
    } else {
        alert("Could not access file(s)!");
    }
}

function draftSend() {
    const subject = $('#draft_subject').val();
    if (subject.trim().length === 0) {
        alert('<?php L('msg_no_subject') ?>');
        return;
    }
    const bodytext = tinymce.get('draft_body').getContent({format: 'text'});
    if (bodytext.trim().length === 0) {
        alert('<?php L('msg_no_body') ?>');
        return;
    }
    if (confirm("<?php L('msg_send_conf') ?>")) {
        const body = tinymce.get('draft_body').getContent();
        $.ajax({
            type: "POST",
            url: "draft.php",
            data: { mailid: currentDraft, accountid: account_id, subject: subject, body: body }
        }).then(function(draft) { // Returns a new draft
            draftResetFilters();
            draftUpdate(draft);
            // Start send operation here!
            $.ajax({
                type: "POST",
                url: "mails.php",
                data: {}
            });
        });
    }
}

function draftTabEnter() {
    if (filterChanged) {
        draftSetRecipients();
        filterChanged = false;
    }
}

function draftTab() {
    tinymce.init({
      selector: '#draft_body',
      setup: function(editor) {
          editor.ui.registry.addButton('customSaveButton', {
              text: '<?php L('msg_save') ?>',
              onAction: function(_) {
                  draftSave();
              }
          });
          editor.ui.registry.addButton('customClearButton', {
              text: '<?php L('msg_clear') ?>',
              onAction: function(_) {
                  draftClear();
              }
          });
          editor.ui.registry.addButton('customSendButton', {
              text: '<?php L('msg_send') ?>',
              onAction: function(_) {
                  draftSend();
              }
          });
          editor.on('init', function(event) {
              draftGet();
          });
      },
      language: 'da',
      menubar: false,
      plugins: 'autosave autolink code',
      paste_block_drop: true,
      toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | ' +
               'outdent indent | customSaveButton customClearButton | customSendButton ',
      statusbar: false,
    });

    // Add event handlers on file input
    $('#draft_files').on('change', draftAttach);

}

//------------------------------------------------------------------------------
// Mails
//------------------------------------------------------------------------------
function mailRecipientsToTxt(recipients) {
   let txt = '';
   for (const r of recipients) {
       txt += `${r.name} <${r.email}>, `;
   }
   return txt;
}

// Input is an array of attachments. E.g.:
// [ { id: 23, mailid: 33, name: "guide.pdf", size: 356, type: "application/pdf" },
//   { id: 24, mailid: 33, name: "todo.txt",  size: 245, type: "text/plain"      } ]
function mailAttachmentsToTxt(attachments) {
    let txt = '';
    for (const a of attachments) {
        txt += `<a title="${a.type}, ${a.size} bytes" style="background-color: WhiteSmoke" `;
        txt += ` href="attachment_show.php?id=${a.id}" target="_blank">${a.name}</a> &nbsp;`;
    }
    return txt;
}

function updateMailItems(item) {
    const tm = tinymce.get('mail_body');
    if (!tm) {
        return; // tinymce not ready yet
    }

    if (!item) {
        $('#mail_date').html('');
        $('#mail_from').html('');
        $('#mail_num_rx').html('');
        $('#mail_to').val('');
        $('#mail_subject').val('');
        tm.resetContent('');
        return;
    }
    $('#mail_date').html(localTime(item.sent));
    $('#mail_from').html(item.accountname);
    $('#mail_subject').val(item.subject);
    tm.resetContent(item.body);
    $.ajax({
        type: "GET",
        url: "mail_recipients.php",
        data: { mailid: item.mailid }
    }).then(function(a) {
        if (a.length == 1) {
            $('#mail_num_rx').html(`(${a.length}&nbsp;<?php L('msg_receiver') ?>)`);
        } else {
            $('#mail_num_rx').html(`(${a.length}&nbsp;<?php L('msg_receivers') ?>)`);
        }
        $('#mail_to').val(mailRecipientsToTxt(a));
    });
    $.ajax({
        type: "GET",
        url: "attachments.php",
        data: { mailid: item.mailid }
    }).then(function(a) {
        $('#mail_attachments').html(mailAttachmentsToTxt(a));
    });
}

function mailsGrid() {
    // The radio buttons returns the same value as state in database
    const state = parseInt($("input:radio[name=mb_group]:checked").val());

    $('#mails_grid').jsGrid("destroy");

    $("#mails_grid").jsGrid({
        width: "100%",
        height: "100%",
        autoload: true,
        deleteConfirm: (state === 4) ? '<?php L('msg_del_conf') ?>' : '<?php L('msg_trash_conf') ?>',
        controller: {
            loadData: function(filter) {
                const data = { state: state };
                return $.ajax({
                    type: "GET",
                    url: "mails.php",
                    data: data
                });
            },
            deleteItem: function(item) {
                updateMailItems(null);
                return $.ajax({
                    type: "DELETE",
                    url: "mails.php",
                    data: item
                });
            }
        },
        onDataLoaded: function(args) {
            updateMailItems(args.data[0]); // Show newest mail
            if (args.data[0]) {
                setHighlight(this, args.data[0]);
            }
        },
        onError: function(args) {
            alert(args.args[0].responseJSON);
        },
        rowClick: function(args) {
            updateMailItems(args.item);
            setHighlight(this, args.item);
        },
        fields: [
            { width:  20, name: "modified",    type: "text",    visible: false },
            { width:  20, name: "sent",        type: "text",    visible: false },
            { width: 100, name: "subject",     type: "text",    title: "<?php L('msg_subject') ?>" },
            { width:  20, name: "body",        type: "text",    visible: false },
            { width:  20, name: "mailid",      type: "number",  visible: false },
            { width:  20, name: "accountid",   type: "number",  visible: false },
            { width:  20, name: "accountname", type: "text",    visible: false },
            { width:  20, name: "state",       type: "number",  visible: false },
            { width:   5,                      type: "control", editButton: false }
        ]
    });
}

function mailsTabEnter() {
    mailsGrid();
}

function mailsTab() {
    tinymce.init({
      selector: '#mail_body',
      setup: function(editor) {
          editor.on('init', function(e) {
              mailsGrid();
          });
      },
      language: 'da',
      readonly: true,
      menubar: false,
      toolbar: false,
      statusbar: false,
    });
}

//------------------------------------------------------------------------------
// Accounts
//------------------------------------------------------------------------------
function accountsGrid() {
    $("#accounts_grid").jsGrid({
        width: "100%",
        height: "100%",
        inserting: role_admin,
        editing: role_admin,
        autoload: true,
        deleteConfirm: "<?php L('del_account') ?>",
        controller: {
            loadData: function(filter) {
                return $.ajax({
                    type: "GET",
                    url: "accounts.php",
                    data: filter
                });
            },
            insertItem: function(item) {
                return $.ajax({
                    type: "POST",
                    url: "accounts.php",
                    data: item,
                }).then(function(a) {
                    const c = confirm(`<?php L('ot_password') ?>`);
                    if (c) {
                        const res = $.ajax({
                            type: "POST",
                            url: "invite_user.php",
                            data: a,
                        }).then(function(b) {
                           // Maybe popup on error
                           console.log("From b: ", b)
                        });
                    }
                    return a; // Does not work unless a is returned
                });
            },
            updateItem: function(item) {
                return $.ajax({
                    type: "PUT",
                    url: "accounts.php",
                    data: item
                });
            },
            deleteItem: function(item) {
                return $.ajax({
                    type: "DELETE",
                    url: "accounts.php",
                    data: item
                });
            }
        },
        onDataLoaded: function(args) {
            filterChanged = true;
        },
        onItemInserted: function(args) {
            filterChanged = true;
        },
        onItemUpdated: function(args) {
            filterChanged = true;
        },
        onItemDeleted: function(args) {
            filterChanged = true;
        },
        onError: function(args) {
            alert(args.args[0].responseJSON);
        },
        rowClick: function(args) {
            if (args.item.state === 1) {
                const a = args.item;
                const c = confirm(`<?php L('ot_password') ?>`);
                if (c) {
                    const res = $.ajax({
                        type: "POST",
                        url: "invite_user.php",
                        data: a,
                    }).then(function(b) {
                       // Maybe popup on error
                       console.log("From b: ", b)
                    });
                }
            }
        },
        fields: [
            { width:  10, name: "accountid",  title: "Id",                         type: "text", editing: false, inserting: false },
            { width:  60, name: "name",       title: "<?php L('acc_name') ?>",     type: "text", validate: "required" },
            { width:  80, name: "email",      title: "<?php L('acc_email') ?>",    type: "text", validate: "email", editing: false },
            { width:  30, name: "otp",        title: "<?php L('acc_otp') ?>",      type: "text", editing: false, inserting: false },
            { width:  30, name: "state",      title: "<?php L('acc_state') ?>",    type: "select",
              validate: { validator: "range", param: [1, 3] }, filtering: false,
              items: accountStates, valueField: "id", textField: "state", inserting: false  },
            { width:  30, name: "role",       title: "<?php L('acc_role') ?>",     type: "select",
              validate: { validator: "range", param: [1, 5] }, filtering: false,
              items: accountRoles, valueField: "id", textField: "role" },
            { width:  20, name: "lang",       title: "<?php L('acc_lang') ?>",     type: "select",
              validate: { validator: "range", param: [1, 2] }, filtering: false,
              items: accountLang, valueField: "id", textField: "value" },
            { width:  40, name: "activity",   title: "<?php L('acc_activity') ?>", type: "text",
              itemTemplate: localTime, editing: false, inserting: false },
            { type: "control", modeSwitchButton: false,  }
        ]
    });
}

//------------------------------------------------------------------------------
// Wait for document ready
//------------------------------------------------------------------------------
$(function() {

    $(this).mousemove(function (e) {
        activity = 1;
        idleTime = 0;
    });

    $(this).keypress(function (e) {
        activity = 1;
        idleTime = 0;
    });

    tick();
    setInterval(tick, 1000 * 60); // Call tick every minute

    // Track tab change
    $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
        // Find previous and current tab
        // https://www.tutorialrepublic.com/twitter-bootstrap-4-tutorial/bootstrap-tabs.php
        const preTabId = $(e.relatedTarget).attr('id');
        const curTabId = $(e.target).attr('id');
        const curTabRef = $(e.target).attr('href');
        //console.log(`${preTabId} -> ${curTabId} (${curTabRef})`);

        // Tab actions
        switch (curTabId) {
            case 'draft_tab':
                draftTabEnter();
                break;
            case 'mails_tab':
                mailsTabEnter();
                break;
            default:
                break;
        }

        // Save active tab
        // https://www.tutorialrepublic.com/faq/how-to-keep-the-current-tab-active-on-page-reload-in-bootstrap.php
        localStorage.setItem('activeTab', curTabRef);
    });

    // Restore active tab
    let activeTab = localStorage.getItem('activeTab');
    if (activeTab) {
        $('#main_tab a[href="' + activeTab + '"]').tab('show');
    }

    // Action on modal close - update modified grids
    $('.modal').on('hidden.bs.modal', function (e) {
        // Destroy all relations grids
        $('#apartments_persons_grid').jsGrid("destroy");
        $('#parkings_persons_grid').jsGrid("destroy");
        $('#depots_persons_grid').jsGrid("destroy");
        $('#persons_apartments_grid').jsGrid("destroy");
        $('#persons_parkings_grid').jsGrid("destroy");
        $('#persons_depots_grid').jsGrid("destroy");

        if (apartmentRelationChanged) {
            apartmentRelationChanged = false;
            apartmentsGrid();
        }
        if (parkingRelationChanged) {
            parkingRelationChanged = false;
            parkingsGrid();
        }
        if (depotRelationChanged) {
            depotRelationChanged = false;
            depotsGrid();
        }
        if (personRelationChanged) {
            personRelationChanged = false;
            personsGrid();
        }

    });

    jsGrid.validators.email = {
        message: "<?php L('invalid_email') ?>",
        validator: function(value, item) {
            let validRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
            return !!value.match(validRegex);
        }
    }

    // Helper for toggleHighlight and setHighlight
    // https://github.com/tabalinas/jsgrid/issues/194
    // this._content.find("tr")[arg] returns a DOM element instead of a jQuery object
    // Pass the DOM element to the find method to get a jQuery object representing it
    jsGrid.Grid.prototype.rowByIndex = function(arg) {
        return this._content.find(this._content.find("tr")[arg]);
    }

    // Set defaults for all jQuery datepickers
    $.datepicker.setDefaults({
        dateFormat: 'yy-mm-dd', // Actually 'yyyy-mm-dd'
        changeMonth: true,
        changeYear: true,
        yearRange: "2006:c+02",
    });
    
    // Custom date field using jQuery datepicker
    var MyDateField = function(config) {
        jsGrid.Field.call(this, config);
    };

    MyDateField.prototype = new jsGrid.Field({

        css: "date-field",            // redefine general property 'css'
        align: "center",              // redefine general property 'align'

        myCustomProperty: "foo",      // custom property

        sorter: function(date1, date2) {
            return new Date(date1) - new Date(date2);
        },

        itemTemplate: function(value, item) {
            return value;
        },

        insertTemplate: function() {
            return this._insertPicker = $("<input>").datepicker({ defaultDate: new Date() });
        },

        editTemplate: function(value, item) {
            if (value && value.length > 0) {
                return this._editPicker = $("<input>").datepicker().datepicker("setDate", new Date(value));
            } else {
                return this._editPicker = $("<input>").datepicker({ defaultDate: new Date() });
            }
        },

        insertValue: function() {
            if (this._insertPicker.datepicker("getDate")) {
                return this._insertPicker.datepicker("getDate").toLocaleString('sv-SE').substring(0, 10); //'sv-SE' => 'yyyy-mm-dd'
            } else {
                return '';
            }
        },

        editValue: function() {
            if (this._editPicker.datepicker("getDate")) {
                return this._editPicker.datepicker("getDate").toLocaleString('sv-SE').substring(0, 10);
            } else {
                return '';
            }
        }
    });

    jsGrid.fields.date = MyDateField;

    jsGrid.locale("da");

    // Wait until all xxxSelectUpdate() has been resolved.
    // Many of the grids depends on these
    $.when(apartmentSelectUpdate(),
           parkingSelectUpdate(),
           depotSelectUpdate(),
           personSelectUpdate()
    ).then(function() {
        apartmentsGrid();

        // Run if one of the checkboxes on apartments tab is changed
        $('.apartments_chk').change(function() {
            apartmentsGrid();
        });

        parkingsGrid();

        // Run if one of the checkboxes on parkings tab is changed
        $('.parkings_chk').change(function() {
            parkingsGrid();
        });

        depotsGrid();

        // Run if one of the checkboxes on depots tab is changed
        $('.depots_chk').change(function() {
            depotsGrid();
        });

        depotsWaitGrid();

        personsGrid();

        // Run if one of the checkboxes on persons tab is changed
        $('.persons_chk').change(function() {
            personsGrid();
        });

        if (role_admin) {
            accountsGrid();
        }

        if (role_mail) {
            draftTab();

            // Run if one of the checkboxes on draft tab is changed
            $('.draft_chk').change(function() {
                draftSetRecipients();
            });

            mailsTab();
        }
    });

    if (role_admin) {
        test();
    }
});
    
</script>

</body>
</html>
