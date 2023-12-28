<?php

$lang_array = [
    // index.php
    "change_pw_update"   => "Opdater adgangskode",
    "change_pw_current"  => "Nuværende adgangskode",
    "change_pw_new"      => "Ny adgangskode på mellem 8 og 32 tegn",
    "change_pw_repeat"   => "Gentag ny adgangskode",
    "change_pw_same"     => "Ny adgangskode må ikke være den samme som før!",
    "change_pw_diff"     => "Adgangskoderne er forskellige!",
    "change_pw_wrong"    => "Forkert adgangskode!",
    "change_pw_continue" => "Udfør",

    "forgot_pw_unknown"  => "Email adressen kendes ikke i systemet!",
    "forgot_pw_sent"     => "Ny midlertidig adgangskode sendt!",
    "forgot_pw_not_sent" => "Kunne ikke sende midlertidig adgangskode!",
    "forgot_pw_continue" => "Udfør",
    "forgot_pw_back"     => "Tilbage til log ind",

    "invalid_account"  => "Ukendt email eller adgangskode!",
    "invalid_email"    => "Ugyldig email adresse!",
    "invalid_password" => "Adgangskode skal være mellem 8 og 32 tegn!",

    "login_email"    => "Email",
    "login_password" => "Adgangskode",
    "login_login"    => "Log ind",
    "login_forgot"   => "Glemt adgangskode",
    "login_new_pw"   => "Log ind med ny adgangskode",
    "login_disabled" => "Konto er deaktiveret!",

    "ot_password" => 'Bruger oprettet med midlertidig adgangskode: ${a.otp}\n\n'.
                     'Send midlertidig adgangkode til ${a.name} <${a.email}>?',

    // passwordmail used in invite_user and forgot_pw:
    "pwm_subject" => "Invitation",
    "pwm_body"    => "<p>Hej %s</p>".
                     "<p>Log ind på <b>%s</b> med email: <b>%s</b> og midlertidig adgangskode: <b>%s</b></p>".
                     "<p>Du vil blive bedt om at lave en ny adgangskode, som skal være forskellig fra den midlertidige.</p>".
                     "<p>Den nye adgangskode skal være på mellem 8 og 32 tegn.</p>",

    // dashboard.php
    "logout" => "Log ud",
    "help"   => "Hjælp",
    "noclip" => "Kan ikke kopiere data til udklipsholder i denne browser!",

    "del_apartment" => "Vil du slette denne lejlighed?",
    "del_depot"     => "Vil du slette dette depot?",
    "del_depotwait" => "Vil du slette denne person fra ventelisten?",
    "del_parking"   => "Vil du slette denne P-plads?",
    "del_person"    => "Vil du slette denne person?",
    "del_relation"  => "Vil du slette denne relation?",
    "del_account"   => "Vil du slette denne bruger?",
    "del_account_1" => "Denne bruger kan ikke slettes!",
    "put_account_1" => "Status kan ikke ændres for denne bruger!",

    "modified" => "Ændret",
    "select"   => "Vælg...",
    "started"  => "Startet",
    "stopped"  => "Stoppet",

    "apartment"    => "Lej<wbr>lig<wbr>hed",
    "apartments"   => "Lejligheder",
    "ap_number"    => "Op<wbr>gang",
    "ap_floor"     => "Sal",
    "ap_side"      => "Side",
    "ap_type"      => "Type",
    "ap_size"      => "M<sup>2</sup>",
    "ap_reduction" => "Re<wbr>duk<wbr>tion",
    "ap_tapshares" => "Ha<wbr>ne<wbr>andel",
    "ap_shafts"    => "Skakt",

    "parking"     => "P-plads",
    "parkings"    => "P-pladser",
    "pa_depot"    => "Tremme<wbr>rum",
    "pa_charger"  => "Privat oplader",
    "pa_power"    => "El forsyning",
    "pa_ccharger" => "AG oplader",
    "pa_overview" => "Parkeringsoversigt",

    "depot"       => "Depot",
    "depots"      => "Depoter",
    "de_location" => "Placering",
    "de_isfree"   => "Fri",
    "de_waitid"   => "ID",
    "de_wait"     => "Venteliste til ekstra depotrum",

    "person"         => "Person",
    "persons"        => "Personer",
    "pe_name"        => "Navn",
    "pe_address"     => "Adresse",
    "pe_email"       => "Email",
    "pe_noemail"     => "Nej",
    "pe_noemail_tip" => "Personen vil/kan ikke modtage e-mails",
    "pe_phone"       => "Tele<wbr>fon",

    "msg_mails"       => "Se beskeder",
    "msg_draft"       => "Ny besked",
    "msg_subject"     => "Emne",
    "msg_body"        => "Besked",
    "msg_attachments" => "Vedhæftninger",
    "msg_attach"      => "Vedhæft",
    "msg_from"        => "Fra",
    "msg_to"          => "Til",
    "msg_by"          => "Af",
    "msg_receivers"   => "modtagere",
    "msg_receiver"    => "modtager",
    "msg_clear"       => "Slet",
    "msg_save"        => "Gem",
    "msg_send"        => "Send",
    "msg_sent"        => "Sendt",
    "msg_queue"       => "Kø",
    "msg_trash"       => "Papirkurv",
    "msg_del_conf"    => "Vil du slette denne besked permanent?",
    "msg_trash_conf"  => "Vil du flytte denne besked til papirkurv?",
    "msg_send_conf"   => "Vil du sende denne besked?",
    "msg_no_subject"  => "Emnefelt må ikke være tomt!",
    "msg_no_body"     => "Beskedfelt må ikke være tomt!",
    "msg_header"      => '<div style="text-align: center; padding: 0px; margin: 0px; color: white; background-color: cornflowerblue;">'.
                         'Besked fra Bestyrelsen i Admiralens Gård'.
                         '</div>',
    "msg_footer"      => '<hr style="width: 100%; padding: 0px; margin: 0px; border-bottom: 1px solid cornflowerblue;">'.
                         '<div style="text-align: center; padding: 0px; margin: 0px; color: cornflowerblue;">'.
                         '<i>Såfremt du ikke ønsker at modtage beskeder fra Admiralens Gård, så svar på denne e-mail og oplys hvorfor</i>'.
                         '</div>',
    "msg_queued"      => "i kø",
    "msg_checked"     => "Kontrolleret",
    "msg_latest"      => "Seneste besked sendt",
    "msg_code"        => "Kode",

    "rel_apartment" => "Relationer til lejlighed ",
    "rel_parking"   => "Relationer til p-plads ",
    "rel_depot"     => "Relationer til depot ",
    "rel_person"    => "Relationer til ",
    "relation"      => "Relation",
    "rel_owner"     => "Ejer",
    "rel_ext_owner" => "Ekstern ejer",
    "rel_tenant"    => "Lejer",
    "rel_show"      => "Vis relationer",
    "rel_hist"      => "Ophørte",

    "accounts"     => "Brugere",
    "acc_name"     => "Navn",
    "acc_email"    => "Email",
    "acc_otp"      => "Engangskode",
    "acc_state"    => "Status",
    "acc_role"     => "Rolle",
    "acc_lang"     => "Sprog",
    "acc_admin"    => "Admin",
    "acc_edit"     => "Rediger",
    "acc_sendmail" => "Send Email",
    "acc_testmode" => "Test",
    "acc_activity" => "Aktivitet",

    "acc_state_first"    => "Nyt login",
    "acc_state_enabled"  => "Aktiveret",
    "acc_state_disabled" => "Deaktiveret",

    "acc_role_administrator" => "Administration",
    "acc_role_board"         => "Bestyrelse",
    "acc_role_superman"      => "Superman",
    "acc_role_caretaker"     => "Vicevært",
    "acc_role_tester"        => "Tester",

    "settings"        => "Indstillinger",
    "smtp_username"   => "SMTP Brugernavn",
    "smtp_password"   => "SMTP Adgangskode",

    "test"  => "Test",

    "l" => "l"
];

function L($key) { // echo value
    global $lang_array;
    echo $lang_array[$key];
}

function _L($key) { // return value
    global $lang_array;
    return $lang_array[$key];
}

?>
