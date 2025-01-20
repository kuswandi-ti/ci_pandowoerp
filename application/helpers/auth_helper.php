<?php

function is_logged_in()
{
    $ci = get_instance();
    if (!$ci->session->userdata('impsys_nik')) {
        $ci->session->set_flashdata('error', "Harap login terlebih dahulu");
        redirect('Auth');
    } else {
        true;
    }
}
