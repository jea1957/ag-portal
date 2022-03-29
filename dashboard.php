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
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- -->
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
        <a href="#apartments_pane" class="nav-link active" data-toggle="tab" id="apartments_tab"><?php L('apartments') ?></a>
    </li>
    <li class="nav-item">
        <a href="#parkings_pane" class="nav-link" data-toggle="tab" id="parkings_tab"><?php L('parkings') ?></a>
    </li>
    <li class="nav-item">
        <a href="#depots_pane" class="nav-link" data-toggle="tab" id="depots_tab"><?php L('depots') ?></a>
    </li>
    <li class="nav-item">
        <a href="#persons_pane" class="nav-link" data-toggle="tab" id="persons_tab"><?php L('persons') ?></a>
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
    <div class="tab-pane fade show active" id="apartments_pane">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div>
                        <span><?php L('rel_show') ?>:</span>
                        <label for="apartments_owner">&nbsp;&nbsp;<?php L('rel_owner') ?></label>
                        <input type="checkbox" class="apartments_chk" id="apartments_owner" checked>
                        <label for="apartments_extern">&nbsp;&nbsp;<?php L('rel_ext_owner') ?></label>
                        <input type="checkbox" class="apartments_chk" id="apartments_extern" checked>
                        <label for="apartments_tenant">&nbsp;&nbsp;<?php L('rel_tenant') ?></label>
                        <input type="checkbox" class="apartments_chk" id="apartments_tenant" checked>
                        <label for="apartments_historical">&nbsp;&nbsp;<?php L('rel_hist') ?></label>
                        <input type="checkbox" class="apartments_chk" id="apartments_historical">
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
                        <span><?php L('rel_show') ?>:</span>
                        <label for="parkings_owner">&nbsp;&nbsp;<?php L('rel_owner') ?></label>
                        <input type="checkbox" class="parkings_chk" id="parkings_owner" checked>
                        <label for="parkings_extern">&nbsp;&nbsp;<?php L('rel_ext_owner') ?></label>
                        <input type="checkbox" class="parkings_chk" id="parkings_extern" checked>
                        <label for="parkings_tenant">&nbsp;&nbsp;<?php L('rel_tenant') ?></label>
                        <input type="checkbox" class="parkings_chk" id="parkings_tenant" checked>
                        <label for="parkings_historical">&nbsp;&nbsp;<?php L('rel_hist') ?></label>
                        <input type="checkbox" class="parkings_chk" id="parkings_historical">
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
                        <span><?php L('rel_show') ?>:</span>
                        <label for="depots_historical">&nbsp;&nbsp;<?php L('rel_hist') ?></label>
                        <input type="checkbox" class="depots_chk" id="depots_historical">
                    </div>
                    <div class="flex-grid" id="depots_grid"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="persons_pane">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div>
                        <span><?php L('rel_show') ?>:</span>
                        <label for="persons_historical">&nbsp;&nbsp;<?php L('rel_hist') ?></label>
                        <input type="checkbox" class="persons_chk" id="persons_historical">
                    </div>
                    <div class="flex-grid" id="persons_grid"></div>
                </div>
            </div>
        </div>
    </div>
  <?php if ($_SESSION['role_mail']) { ?>
    <div class="tab-pane fade" id="draft_pane">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-2">
                    <div>
                        <h5><?php echo ucfirst(_L('msg_receivers')) ?>:</h5>
                    </div>
                    <div>
                        <input type="checkbox" id="draft_apartments" checked>
                        <label for="draft_apartments"><?php L('apartments') ?></label>
                    </div>
                    <div>
                        <input type="checkbox" id="draft_parkings">
                        <label for="draft_parkings"><?php L('parkings') ?></label>
                    </div>
                    <div>
                        <input type="checkbox" id="draft_depots">
                        <label for="draft_depots"><?php L('depots') ?></label>
                    </div>
                    <div>
                        <input type="checkbox" id="draft_persons">
                        <label for="draft_persons"><?php L('persons') ?></label>
                    </div>
                    <hr class="w-100">
                    <div>
                        <input type="checkbox" id="draft_board" checked>
                        <label for="draft_board"><?php L('acc_role_board') ?></label>
                    </div>
                    <div>
                        <input type="checkbox" id="draft_caretaker">
                        <label for="draft_caretaker"><?php L('acc_role_caretaker') ?></label>
                    </div>
                    <div>
                        <input type="checkbox" id="draft_administrator">
                        <label for="draft_administrator"><?php L('acc_role_administrator') ?></label>
                    </div>
                </div>
                <div class="col-lg-10">
                    <div class="mail-line">
                        <strong>&nbsp;<?php L('msg_to') ?>:&nbsp;</strong>
                        <span id="draft_num_rx"></span>
                    </div>
                    <textarea id="draft_to" name="draft_to" readonly></textarea>
                    <div class="mail-line">
                        <strong><?php L('msg_subject') ?>:&nbsp;</strong>
                    </div>
                    <input type="text" id="draft_subject" name="draft_subject">
                    <div class="mail-line">
                        <strong><?php L('msg_body') ?>:&nbsp;</strong>
                    </div>
                    <textarea id="draft_body" name="draft_body"></textarea>
                    <div class="mail-line">
                        <strong><?php L('msg_attachments') ?>:&nbsp;</strong>
                        <span id="draft_attachments"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="mails_pane">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-2">
                    <div class="flex-grid" id="mails_grid"></div>
                </div>
                <div class="col-lg-10">
                    <div class="mail-line">
                        <strong><?php L('msg_sent') ?>:&nbsp;</strong>
                        <span id="mail_date"></span>
                        <strong>&nbsp;<?php L('msg_by') ?>:&nbsp;</strong>
                        <span id="mail_from"></span>
                        <strong>&nbsp;<?php L('msg_to') ?>:&nbsp;</strong>
                        <span id="mail_num_rx"></span>
                    </div>
                    <textarea id="mail_to" name="mail_to" placeholder="<?php L('msg_to') ?>" readonly></textarea>
                    <div class="mail-line">
                        <strong><?php L('msg_subject') ?>:&nbsp;</strong>
                    </div>
                    <input type="text" id="mail_subject" name="mail_subject" readonly>
                    <div class="mail-line">
                        <strong><?php L('msg_body') ?>:&nbsp;</strong>
                    </div>
                    <textarea id="mail_body" name="mail_body"></textarea>
                    <div class="mail-line">
                        <strong><?php L('msg_attachments') ?>:&nbsp;</strong>
                        <span id="mail_attachments"></span>
                    </div>
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
        <p>Test tab content ...</p>
        <span class="ui-icon ui-icon-arrowthick-1-n"></span>
        <span class="ui-icon ui-icon-check"></span>
        <p><?php echo __DIR__ ?></p>
        <p><?php echo print_r($_SERVER['HTTP_ACCEPT_LANGUAGE'], 1) ?></p>
        <p><?php echo print_r(locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE']), 1) ?></p>
        <i class="bi-alarm" style="font-size: 2rem; color: cornflowerblue;"></i>
        <br>
        <span id="custom_send" class="bi-send"></span>
        <button type="button" onclick="event.preventDefault(); console.log(apartmentFilter());">ApartmentFilter</button>
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
// either apartments, parkings, depots or persons.
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
                       { id: 1, role: "<?php L('acc_role_superman') ?>" } ];

const accountLang = [ { id: 1, value: "da" },
                      { id: 2, value: "en" } ];

function logout() {
    return $.ajax({
        type: "POST",
        url: "logout_action.php",
        data: { account_id: account_id },
    }).done(function() { location.reload(); });
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

/* Not used for now
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
*/

// Handle mail check status in navbar
function mailStatus() {
    $.ajax({
        type: "GET",
        url: "mailstatus.php"
    }).then(function (a) {
        let diff = new Date() - new Date(a.modified + "Z");
        if (diff > (1000 * 60 * 6)) { // Mail is expected to be checked every 5 minutes.
            $('#mailstatus').css("color", "red"); // Mark red if check is older than 6 minutes
        } else {
            $('#mailstatus').css("color", "");
        }
        $('#mailstatus').html(`<?php L('mq_check') ?> ${localTime(a.modified)}`);
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
// Apartments
//------------------------------------------------------------------------------

// Indicates that a relation has changed and the grid must be reloaded
var apartmentRelationChanged = false;

var apartmentSelect; // Array of { id: <number>, name: <name> }

function apartmentSelectUpdate() {
    return $.ajax({
        type: "GET",
        url: "apartments_all.php"
    }).done(function(result) {
        apartmentSelect = [{ id: 0, name: "<?php L('select') ?>" }];
        for (const r of result) {
            apartmentSelect.push( { id: r["apartmentid"], name: `${r["number"]} ${r["floor"]} ${r["side"]} (#${r["apartmentid"]})` } );
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
        onRefreshed: function(args) {
            filterChanged = true;
        },
        onError: function(args) {
            alert(args.args[0].responseJSON);
        },
        rowClick: function(args) {
            $('#apartments_persons_relations').html(apartmentGet(args.item.apartmentid));
            apartmentsPersons('#apartments_persons_grid', '#apartments_historical', 0, args.item.apartmentid);
            $('#apartments_persons_modal').modal('show');
        },
        fields: [
            { width:  20, name: "apartmentid", title: "<?php L('apartment') ?>",    type: "text", validate: "required", editing: false},
            { width:  20, name: "number",      title: "<?php L('ap_number') ?>",    type: "text", validate: "required"},
            { width:  20, name: "floor",       title: "<?php L('ap_floor') ?>",     type: "text", validate: "required"},
            { width:  20, name: "side",        title: "<?php L('ap_side') ?>",      type: "text", validate: "required"},
            { width:  20, name: "type",        title: "<?php L('ap_type') ?>",      type: "text", validate: "required"},
            { width:  20, name: "size",        title: "<?php L('ap_size') ?>",      type: "text", validate: "required"},
            { width:  20, name: "reduction",   title: "<?php L('ap_reduction') ?>", type: "text", validate: "required"},
            { width:  20, name: "tapshares",   title: "<?php L('ap_tapshares') ?>", type: "text", validate: "required"},
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
    }).done(function(parkings) {
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
        onRefreshed: function(args) {
            filterChanged = true;
        },
        onError: function(args) {
            alert(args.args[0].responseJSON);
        },
        rowClick: function(args) {
            $('#parkings_persons_relations').html(parkingGet(args.item.parkingid));
            parkingsPersons('#parkings_persons_grid', '#parkings_historical', 0, args.item.parkingid);
            $('#parkings_persons_modal').modal('show');
        },
        fields: [
            { width:  10, name: "parkingid", title: "<?php L('parking') ?>",    type: "text", validate: "required", editing: false},
            { width:  10, name: "depot",     title: "<?php L('pa_depot') ?>",   type: "checkbox", editing: role_admin },
            { width:  10, name: "charger",   title: "<?php L('pa_charger') ?>", type: "checkbox" },
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
    }).done(function(depots) {
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
        onItemInserted: function(args) {
            depotSelectUpdate();
        },
        onItemUpdated: function(args) {
            depotSelectUpdate();
        },
        onItemDeleted: function(args) {
            depotSelectUpdate();
        },
        onRefreshed: function(args) {
            filterChanged = true;
        },
        onError: function(args) {
            alert(args.args[0].responseJSON);
        },
        rowClick: function(args) {
            $('#depots_persons_relations').html(depotGet(args.item.depotid));
            depotsPersons('#depots_persons_grid', '#depots_historical', 0, args.item.depotid);
            $('#depots_persons_modal').modal('show');
        },
        fields: [
            { width:  10, name: "depotid", title: "<?php L('depot') ?>",       type: "text", validate: "required", editing: false },
            { width:  10, name: "number",  title: "<?php L('de_location') ?>", type: "text", validate: "required" },
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
// Persons
//------------------------------------------------------------------------------

// Indicates that a relation has changed and the grid must be reloaded
var personRelationChanged = false;

var personSelect; // Array of { personid: <number>, name: <name> }

function personSelectUpdate() {
    return $.ajax({
        type: "GET",
        url: "persons_all.php"
    }).done(function(persons) {
        persons.sort((a, b) => a.name.localeCompare(b.name));
        persons.unshift({ personid: 0, name: "<?php L('select') ?>" });
        personSelect = [...persons];
    });
}

function personGet(id) {
    for (let i of personSelect) {
       if (i.personid === id) {
           return i.name;
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
                console.log("Persons filter:", filter);
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
        onItemInserted: function(args) {
            personSelectUpdate();
        },
        onItemUpdated: function(args) {
            personSelectUpdate();
        },
        onItemDeleted: function(args) {
            personSelectUpdate();
        },
        onRefreshed: function(args) {
            filterChanged = true;
        },
        onError: function(args) {
            alert(args.args[0].responseJSON);
        },
        rowClick: function(args) {
            $('#persons_relations').html(personGet(args.item.personid));
            apartmentsPersons('#persons_apartments_grid', '#persons_historical', args.item.personid, 0);
            parkingsPersons('#persons_parkings_grid', '#persons_historical', args.item.personid, 0);
            depotsPersons('#persons_depots_grid', '#persons_historical', args.item.personid, 0);
            $('#persons_modal').modal('show');
        },
        fields: [
            { width:  10, name: "personid", title: "Id",                        type: "number", filtering: false, editing: false, inserting: false },
            { width: 100, name: "name",     title: "<?php L('pe_name') ?>",     type: "text", validate: "required" },
            { width: 100, name: "address",  title: "<?php L('pe_address') ?>",  type: "text", validate: "required" },
            { width: 100, name: "email",    title: "<?php L('pe_email') ?>",    type: "text", validate: "email" },
          //{ width:  10, name: "nomails",  title: '<span class="ui-icon ui-icon-mail-closed" title="Send mail to this person"></span>',
          //  type: "checkbox" },
            { width:  10, name: "nomails",  title: '<span title="<?php L('pe_noemail_tip') ?>"><?php L('pe_noemail') ?></span>', type: "checkbox" },
            { width:  30, name: "phone",    title: "<?php L('pe_phone') ?>",    type: "text" },
            { width:  60, name: "modified", title: "<?php L('modified') ?>", type: "text",
              filtering: false, editing: false, inserting: false, itemTemplate: localTime, visible: role_admin },
            { width:  20, type: "control", editButton: role_update, deleteButton: role_update, modeSwitchButton: role_update} 
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
            { width:  40, name: "personid", title: "<?php L('person') ?>",    type: "text",   visible: false },
            { width:  10, name: "id",       title: "<?php L('apartment') ?>", type: "select", validate: { validator: "min", param: 1 },
              items: apartmentSelect, valueField: "id", textField: "name", editing: false },
            { width:  30, name: "relation", title: "<?php L('relation') ?>",  type: "select", validate: { validator: "range", param: [1, 3] },
              items: relations, valueField: "id", textField: "relation" },
            { width:  20, name: "started",  title: "<?php L('started') ?>",   type: "date" },
            { width:  20, name: "stopped",  title: "<?php L('stopped') ?>",   type: "date" },
          //{ width: 100, name: "modified", title: "<?php L('modified') ?>",  type: "text",
          //  filtering: false, editing: false, inserting: false, itemTemplate: localTime },
            { width:  10, type: "control", editButton: role_update, deleteButton: role_update, modeSwitchButton: false }
        ]);
    } else {
        grid([
            { width:  10, name: "id",       title: "<?php L('apartment') ?>", type: "text",   visible: false },
            { width:  40, name: "personid", title: "<?php L('person') ?>",    type: "select", validate: { validator: "min", param: 1 },
              items: personSelect, valueField: "personid", textField: "name", editing: false },
            { width:  30, name: "relation", title: "<?php L('relation') ?>",  type: "select", validate: { validator: "range", param: [1, 3] },
              items: relations, valueField: "id", textField: "relation" },
            { width:  20, name: "started",  title: "<?php L('started') ?>",   type: "date" },
            { width:  20, name: "stopped",  title: "<?php L('stopped') ?>",   type: "date" },
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
            { width:  40, name: "personid", title: "<?php L('person') ?>",   type: "text",   visible: false },
            { width:  10, name: "id",       title: "<?php L('parking') ?>",  type: "select", validate: { validator: "min", param: 1 },
              items: parkingSelect, valueField: "parkingid", textField: "parkingid", editing: false },
            { width:  30, name: "relation", title: "<?php L('relation') ?>", type: "select", validate: { validator: "range", param: [1, 3] },
              items: relations, valueField: "id", textField: "relation" },
            { width:  20, name: "started",  title: "<?php L('started') ?>",  type: "date" },
            { width:  20, name: "stopped",  title: "<?php L('stopped') ?>",  type: "date" },
          //{ width: 100, name: "modified", title: "<?php L('modified') ?>", type: "text",
          //  filtering: false, editing: false, inserting: false, itemTemplate: localTime },
            { width:  10, type: "control", editButton: role_update, deleteButton: role_update, modeSwitchButton: false }
        ]);
    } else {
        grid([
            { width:  10, name: "id",       title: "<?php L('parking') ?>",  type: "text",   visible: false },
            { width:  40, name: "personid", title: "<?php L('person') ?>",   type: "select", validate: { validator: "min", param: 1 },
              items: personSelect, valueField: "personid", textField: "name", editing: false },
            { width:  30, name: "relation", title: "<?php L('relation') ?>", type: "select", validate: { validator: "range", param: [1, 3] },
              items: relations, valueField: "id", textField: "relation" },
            { width:  20, name: "started",  title: "<?php L('started') ?>",  type: "date" },
            { width:  20, name: "stopped",  title: "<?php L('stopped') ?>",  type: "date" },
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
            { width:  40, name: "personid",  title: "<?php L('person') ?>",   type: "text",   visible: false },
            { width:  10, name: "id",        title: "<?php L('depot') ?>",    type: "select", validate: { validator: "min", param: 1 },
              items: depotSelect, valueField: "depotid", textField: "depotid", editing: false },
            { width:  20, name: "started",   title: "<?php L('started') ?>",  type: "date" },
            { width:  20, name: "stopped",   title: "<?php L('stopped') ?>",  type: "date" },
          //{ width: 100, name: "modified",  title: "<?php L('modified') ?>", type: "text",
          //  filtering: false, editing: false, inserting: false, itemTemplate: localTime },
            { width:  10, type: "control", editButton: role_update, deleteButton: role_update, modeSwitchButton: false }
        ]);
    } else {
        grid([
            { width:  10, name: "id",        title: "<?php L('depot') ?>",    type: "text",   visible: false },
            { width:  40, name: "personid",  title: "<?php L('person') ?>",   type: "select", validate: { validator: "min", param: 1 },
              items: personSelect, valueField: "personid", textField: "name", editing: false },
            { width:  20, name: "started",   title: "<?php L('started') ?>",  type: "date" },
            { width:  20, name: "stopped",   title: "<?php L('stopped') ?>",  type: "date" },
          //{ width: 100, name: "modified",  title: "<?php L('modified') ?>", type: "text",
          //  filtering: false, editing: false, inserting: false, itemTemplate: localTime },
            { width:  10, type: "control", editButton: role_update, deleteButton: role_update, modeSwitchButton: false }
        ]);
    }
}

//------------------------------------------------------------------------------
// Draft
//------------------------------------------------------------------------------
function draftGetFilters() {
    let filter = {};
    if ($('#draft_apartments').prop('checked')) {
        filter['apartments'] = apartmentFilter();
    }
    if ($('#draft_parkings').prop('checked')) {
        filter['parkings'] = parkingFilter();
    }
    if ($('#draft_depots').prop('checked')) {
       filter['depots'] = depotFilter();
    }
    if ($('#draft_persons').prop('checked')) {
        filter['persons'] = personFilter();
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

function draftSetRecipients() {
    const filter = draftGetFilters();
    const data = { mailid: currentDraft, accountid: account_id, ...filter };
    $.ajax({
        type: "POST",
        url: "mail_recipients.php",
        data: data
    }).then(function(a) {
        console.log("recipients: ");
        console.dir(a);
    });
}

function draftGet() {
    console.log("draftGet()");
    $.ajax({
        type: "GET",
        url: "draft.php",
        data: { accountid: account_id }
    }).then(function(a) {
        currentDraft = a.mailid;
        console.log("currentDraft: " + currentDraft);
        $('#draft_subject').val(a.subject);
        tinymce.get('draft_body').resetContent(a.body);
    });

}

function draftSave() {
    console.log("draftSave()");
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
    console.log("draftClear()");
    if (confirm("<?php L('msg_del_confirm') ?>")) {
        $.ajax({
            type: "PUT",
            url: "draft.php",
            data: { mailid: currentDraft, accountid: account_id, subject: '', body: '' }
        }).then(function(a) {
            $('#draft_subject').val(a.subject);
            tinymce.get('draft_body').resetContent(a.body);
        });
    }
}

function draftAttach() {
    console.log("draftAttach()");
    draftSetRecipients();
    //alert("Attach not implemented!");
}

function draftSend() {
    console.log("draftSend()");
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
    const body = tinymce.get('draft_body').getContent();
    $.ajax({
        type: "POST",
        url: "draft.php",
        data: { mailid: currentDraft, accountid: account_id, subject: subject, body: body }
    }).then(function(a) { // Returns a new draft
        currentDraft = a.mailid;
        console.log("currentDraft: " + currentDraft);
        $('#draft_subject').val(a.subject);
        tinymce.get('draft_body').resetContent(a.body);
    });
}

function draftTabEnter() {
    if (filterChanged) {
        console.log("filterChanged");
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
          editor.ui.registry.addButton('customAttachButton', {
              text: '<?php L('msg_attach') ?>',
              onAction: function(_) {
                  draftAttach();
              }
          });
          editor.ui.registry.addButton('customSendButton', {
              text: '<?php L('msg_send') ?>',
              onAction: function(_) {
                  draftSend();
              }
          });
          editor.on('init', function(e) {
              draftGet();
          });
      },
      language: 'da',
      menubar: false,
      plugins: 'autosave autolink',
      toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | ' +
               'outdent indent | customSaveButton customClearButton customAttachButton | customSendButton',
      statusbar: false,
    });
}

//------------------------------------------------------------------------------
// Mails
//------------------------------------------------------------------------------
function mailRecipientsToTxt(recipients) {
   let txt = "";
   for (const r of recipients) {
       txt += `${r.name} <${r.email}>; `;
   }
   return txt;
}

function mailRecipientsToHtml(recipients) {
   let txt = "";
   for (const r of recipients) {
       txt += `${r.name} &lt;${r.email}&gt;; `;
   }
   return txt;
}

function updateMailItems(item) {
    $('#mail_date').html(item.modified);
    $('#mail_from').html(item.accountname);
    $('#mail_subject').val(item.subject);
    tinymce.get('mail_body').resetContent(item.body);
    $.ajax({
        type: "GET",
        url: "mail_recipients.php",
        data: { mailid: item.mailid }
    }).then(function(a) {
        if (a.length == 1) {
            $('#mail_num_rx').html(`(${a.length} <?php L('msg_receiver') ?>)`);
        } else {
            $('#mail_num_rx').html(`(${a.length} <?php L('msg_receivers') ?>)`);
        }
        $('#mail_to').val(mailRecipientsToTxt(a));
    });
}

function mailsGrid() {
    $('#mails_grid').jsGrid("destroy");

    $("#mails_grid").jsGrid({
        width: "100%",
        height: "100%",
        heading: false,
        autoload: true,
        deleteConfirm: "<?php L('del_account') ?>",
        controller: {
            loadData: function(filter) {
                const data = { state: 3 }; // Only sent mails
                return $.ajax({
                    type: "GET",
                    url: "mails.php",
                    data: data
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
            updateMailItems(args.data[0]); // Show newest mail
        },
        onError: function(args) {
            alert(args.args[0].responseJSON);
        },
        rowClick: function(args) {
            updateMailItems(args.item);
        },
        fields: [
          //{ width:  40, name: "modified",    type: "text", itemTemplate: localDate },
            { width:  20, name: "modified",    type: "text",   visible: false },
            { width: 100, name: "subject",     type: "text" },
            { width:  20, name: "body",        type: "text",   visible: false },
            { width:  20, name: "mailid",      type: "number", visible: false },
            { width:  20, name: "accountid",   type: "number", visible: false },
            { width:  20, name: "accountname", type: "text",   visible: false },
            { width:  20, name: "state",       type: "number", visible: false },
        ]
    });
}

function mailsTabEnter() {
   console.log("mailsTabEnter()");
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
                    console.log("accounts insert:", a);
                    let c = confirm(`<?php L('ot_password') ?>`);
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
        onError: function(args) {
            alert(args.args[0].responseJSON);
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
              validate: { validator: "range", param: [1, 4] }, filtering: false,
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

/* Not used for now
    // Helper for toggleHighlight
    // https://github.com/tabalinas/jsgrid/issues/194
    // this._content.find("tr")[arg] returns a DOM element instead of a jQuery object
    // Pass the DOM element to the find method to get a jQuery object representing it
    jsGrid.Grid.prototype.rowByIndex = function(arg) {
        return this._content.find(this._content.find("tr")[arg]);
    }
*/
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
        mailsTab();
    }

    // These can wait
    apartmentSelectUpdate();
    parkingSelectUpdate();
    depotSelectUpdate();
    personSelectUpdate();
});
    
</script>

</body>
</html>
